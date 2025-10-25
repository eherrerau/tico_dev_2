<?php

declare(strict_types=1);

namespace Tico\Services;

use Tico\Models\TestUser;
use Tico\Security\InputValidator;
use Tico\Security\CsrfManager;
use Tico\Config\Config;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class AuthService
{
    private readonly TestUser $testUser;
    private readonly InputValidator $inputValidator;
    private readonly CsrfManager $csrfManager;
    private Logger $logger;
    private readonly Config $config;

    public function __construct()
    {
        $this->testUser = new TestUser();
        $this->inputValidator = new InputValidator();
        $this->csrfManager = new CsrfManager();
        $this->config = Config::getInstance();

        $this->initializeLogger();
        $this->initializeSession();
    }

    private function initializeLogger(): void
    {
        $this->logger = new Logger('auth');
        $logPath = $this->config->get('logging.path') . '/auth.log';
        $this->logger->pushHandler(new StreamHandler($logPath, Logger::DEBUG));
    }

    private function initializeSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $sessionConfig = $this->config->get('session');

            ini_set('session.cookie_lifetime', $sessionConfig['lifetime'] * 60);
            ini_set('session.cookie_secure', $sessionConfig['secure'] ? '1' : '0');
            ini_set('session.cookie_httponly', $sessionConfig['httponly'] ? '1' : '0');
            ini_set('session.cookie_samesite', $sessionConfig['samesite']);
            ini_set('session.use_strict_mode', '1');

            session_start();

            // Regenerate session ID periodically for security
            if (!isset($_SESSION['last_regeneration'])) {
                $this->regenerateSession();
            } elseif (time() - $_SESSION['last_regeneration'] > 300) { // 5 minutes
                $this->regenerateSession();
            }
        }
    }

    public function login(array $credentials): array
    {
        try {
            // Validate and sanitize input
            $validatedData = $this->inputValidator->validateLogin($credentials);

            // Check for too many failed attempts
            if ($this->isAccountLocked($validatedData['username'])) {
                $this->logger->warning('Login attempt on locked account', [
                    'username' => $validatedData['username'],
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                ]);

                return [
                    'success' => false,
                    'message' => 'Account temporarily locked due to too many failed attempts',
                ];
            }

            // Attempt authentication
            $user = $this->testUser->authenticate(
                $validatedData['username'],
                $validatedData['password'],
                (int)$validatedData['team_id']
            );

            if ($user) {
                // Successful login
                $this->createUserSession($user);
                $this->clearFailedAttempts($validatedData['username']);

                $this->logger->info('User logged in successfully', [
                    'user_id' => $user['usrId'],
                    'username' => $user['usrName'],
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                ]);

                return [
                    'success' => true,
                    'message' => 'Login successful',
                    'user' => $this->sanitizeUserData($user),
                ];
            }
            // Failed login
            $this->recordFailedAttempt($validatedData['username']);
            $this->logger->warning('Login attempt failed', [
                'username' => $validatedData['username'],
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            ]);
            return [
                'success' => false,
                'message' => 'Invalid username or password',
            ];
        } catch (\Exception $e) {
            $this->logger->error('Login error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred during login',
            ];
        }
    }

    public function logout(): bool
    {
        if ($this->isAuthenticated()) {
            $this->logger->info('User logged out', [
                'user_id' => $_SESSION['user']['usrId'] ?? null,
                'username' => $_SESSION['user']['usrName'] ?? null,
            ]);
        }

        // Clear all session data
        $_SESSION = [];

        // Delete the session cookie
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                ['expires' => time() - 42000, 'path' => $params['path'], 'domain' => $params['domain'], 'secure' => $params['secure'], 'httponly' => $params['httponly']]
            );
        }

        // Destroy the session
        session_destroy();

        return true;
    }

    public function isAuthenticated(): bool
    {
        return isset($_SESSION['user']) &&
               isset($_SESSION['authenticated']) &&
               $_SESSION['authenticated'] === true;
    }

    public function getCurrentUser(): ?array
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        return $_SESSION['user'] ?? null;
    }

    public function getCurrentUserId(): ?int
    {
        $user = $this->getCurrentUser();
        return $user ? (int)$user['usrId'] : null;
    }

    public function hasRole(int $roleId): bool
    {
        if (!$this->isAuthenticated()) {
            return false;
        }

        $userId = $this->getCurrentUserId();
        if (!$userId) {
            return false;
        }

        return $this->testUser->hasRole($userId, $roleId);
    }

    public function requireAuthentication(): void
    {
        if (!$this->isAuthenticated()) {
            header('Location: /login.php');
            exit;
        }
    }

    public function requireRole(int $roleId): void
    {
        $this->requireAuthentication();

        if (!$this->hasRole($roleId)) {
            header('HTTP/1.1 403 Forbidden');
            echo 'Access denied';
            exit;
        }
    }

    private function createUserSession(array $user): void
    {
        $this->regenerateSession();

        $_SESSION['authenticated'] = true;
        $_SESSION['user'] = $this->sanitizeUserData($user);
        $_SESSION['login_time'] = time();
        $_SESSION['last_activity'] = time();

        // Store user roles and products in session for quick access
        $_SESSION['user_roles'] = $this->testUser->getUserRoles($user['usrId']);
        $_SESSION['user_products'] = $this->testUser->getUserProducts($user['usrId']);
    }

    private function sanitizeUserData(array $user): array
    {
        // Remove sensitive data from session
        unset($user['password_hash']);
        return $user;
    }

    private function regenerateSession(): void
    {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }

    private function isAccountLocked(string $username): bool
    {
        $maxAttempts = $this->config->get('security.max_login_attempts', 5);
        $lockoutDuration = $this->config->get('security.lockout_duration', 900); // 15 minutes

        $key = 'failed_attempts_' . md5($username);
        $attempts = $_SESSION[$key] ?? [];

        // Remove old attempts
        $cutoff = time() - $lockoutDuration;
        $attempts = array_filter($attempts, fn ($time): bool => $time > $cutoff);
        $_SESSION[$key] = $attempts;

        return count($attempts) >= $maxAttempts;
    }

    private function recordFailedAttempt(string $username): void
    {
        $key = 'failed_attempts_' . md5($username);
        $attempts = $_SESSION[$key] ?? [];
        $attempts[] = time();
        $_SESSION[$key] = $attempts;
    }

    private function clearFailedAttempts(string $username): void
    {
        $key = 'failed_attempts_' . md5($username);
        unset($_SESSION[$key]);
    }

    public function validateCsrfToken(string $token): bool
    {
        return $this->csrfManager->validateToken($token);
    }

    public function getCsrfToken(): string
    {
        return $this->csrfManager->getToken();
    }

    public function getCsrfTokenField(): string
    {
        return $this->csrfManager->getTokenField();
    }

    public function updateLastActivity(): void
    {
        if ($this->isAuthenticated()) {
            $_SESSION['last_activity'] = time();
        }
    }

    public function checkSessionTimeout(): bool
    {
        if (!$this->isAuthenticated()) {
            return false;
        }

        $sessionLifetime = $this->config->get('session.lifetime', 120) * 60; // Convert to seconds
        $lastActivity = $_SESSION['last_activity'] ?? 0;

        if (time() - $lastActivity > $sessionLifetime) {
            $this->logout();
            return false;
        }

        $this->updateLastActivity();
        return true;
    }
}
