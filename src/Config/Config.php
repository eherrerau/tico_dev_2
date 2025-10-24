<?php

declare(strict_types=1);

namespace Tico\Config;

use Dotenv\Dotenv;

class Config
{
    private static ?self $instance = null;
    private array $config = [];

    private function __construct()
    {
        $this->loadEnvironment();
        $this->loadConfiguration();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function loadEnvironment(): void
    {
        $rootPath = dirname(__DIR__, 2);
        if (file_exists($rootPath . '/.env')) {
            $dotenv = Dotenv::createImmutable($rootPath);
            $dotenv->load();
        }
    }

    private function loadConfiguration(): void
    {
        $this->config = [
            'app' => [
                'name' => $_ENV['APP_NAME'] ?? 'TICO - Tickets Control Center',
                'env' => $_ENV['APP_ENV'] ?? 'production',
                'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'url' => $_ENV['APP_URL'] ?? 'http://localhost',
                'timezone' => $_ENV['APP_TIMEZONE'] ?? 'America/Costa_Rica',
                'key' => $_ENV['APP_KEY'] ?? null,
            ],
            'database' => [
                'default' => [
                    'driver' => $_ENV['DB_CONNECTION'] ?? 'sqlsrv',
                    'host' => $_ENV['DB_HOST'] ?? 'localhost',
                    'port' => (int)($_ENV['DB_PORT'] ?? 1433),
                    'database' => $_ENV['DB_DATABASE'] ?? 'TICO_DB',
                    'username' => $_ENV['DB_USERNAME'] ?? '',
                    'password' => $_ENV['DB_PASSWORD'] ?? '',
                    'charset' => 'utf8',
                    'options' => [
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                        \PDO::ATTR_EMULATE_PREPARES => false,
                    ],
                ],
                'test' => [
                    'driver' => 'sqlite',
                    'database' => __DIR__ . '/../../data/tico_test.db',
                    'username' => '',
                    'password' => '',
                    'charset' => 'utf8',
                    'options' => [
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                        \PDO::ATTR_EMULATE_PREPARES => false,
                    ],
                ],
                'global' => [
                    'driver' => $_ENV['DB_CONNECTION'] ?? 'sqlsrv',
                    'host' => $_ENV['DB_HOST'] ?? 'localhost',
                    'port' => (int)($_ENV['DB_PORT'] ?? 1433),
                    'database' => $_ENV['DB_GLOBAL_DATABASE'] ?? 'TICO_Global',
                    'username' => $_ENV['DB_USERNAME'] ?? '',
                    'password' => $_ENV['DB_PASSWORD'] ?? '',
                    'charset' => 'utf8',
                    'options' => [
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                        \PDO::ATTR_EMULATE_PREPARES => false,
                    ],
                ],
            ],
            'session' => [
                'lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 120),
                'secure' => filter_var($_ENV['SESSION_SECURE'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'httponly' => filter_var($_ENV['SESSION_HTTP_ONLY'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'samesite' => $_ENV['SESSION_SAME_SITE'] ?? 'lax',
            ],
            'logging' => [
                'channel' => $_ENV['LOG_CHANNEL'] ?? 'file',
                'level' => $_ENV['LOG_LEVEL'] ?? 'debug',
                'path' => dirname(__DIR__, 2) . '/logs',
            ],
            'security' => [
                'csrf_token_name' => 'csrf_token',
                'password_cost' => 12,
                'max_login_attempts' => 5,
                'lockout_duration' => 900, // 15 minutes
            ],
        ];
    }

    public function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }

    public function set(string $key, $value): void
    {
        $keys = explode('.', $key);
        $config = &$this->config;

        foreach ($keys as $k) {
            if (!isset($config[$k])) {
                $config[$k] = [];
            }
            $config = &$config[$k];
        }

        $config = $value;
    }

    public function all(): array
    {
        return $this->config;
    }
}
