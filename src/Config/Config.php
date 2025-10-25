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
        if (!self::$instance instanceof \Tico\Config\Config) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function loadEnvironment(): void
    {
        $rootPath = dirname(__DIR__, 2);
        if (file_exists($rootPath . '/.env')) {
            $dotenv = Dotenv::createImmutable($rootPath);
            $dotenv->safeLoad(); // Don't overwrite existing environment variables
        }
    }

    private function loadConfiguration(): void
    {
        // Helper function to get environment variables from either $_SERVER or $_ENV
        $env = function(string $key, $default = null) {
            return $_SERVER[$key] ?? $_ENV[$key] ?? getenv($key) ?: $default;
        };

        $this->config = [
            'app' => [
                'name' => $env('APP_NAME', 'TICO - Tickets Control Center'),
                'env' => $env('APP_ENV', 'production'),
                'debug' => filter_var($env('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN),
                'url' => $env('APP_URL', 'http://localhost'),
                'timezone' => $env('APP_TIMEZONE', 'America/Costa_Rica'),
                'key' => $env('APP_KEY'),
            ],
            'database' => [
                'default' => [
                    'driver' => $env('DB_CONNECTION', 'pgsql'),
                    'host' => $env('DB_HOST', 'localhost'),
                    'port' => (int)$env('DB_PORT', 5432),
                    'database' => $env('DB_DATABASE', 'tico_db'),
                    'username' => $env('DB_USERNAME', 'tico_user'),
                    'password' => $env('DB_PASSWORD', 'tico_password'),
                    'charset' => 'utf8',
                    'options' => [
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                        \PDO::ATTR_EMULATE_PREPARES => false,
                    ],
                ],
            ],
            'session' => [
                'lifetime' => (int)$env('SESSION_LIFETIME', 120),
                'secure' => filter_var($env('SESSION_SECURE', false), FILTER_VALIDATE_BOOLEAN),
                'httponly' => filter_var($env('SESSION_HTTP_ONLY', true), FILTER_VALIDATE_BOOLEAN),
                'samesite' => $env('SESSION_SAME_SITE', 'lax'),
            ],
            'logging' => [
                'channel' => $env('LOG_CHANNEL', 'file'),
                'level' => $env('LOG_LEVEL', 'debug'),
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

        foreach ($keys as $key) {
            if (!isset($value[$key])) {
                return $default;
            }
            $value = $value[$key];
        }

        return $value;
    }

    public function set(string $key, $value): void
    {
        $keys = explode('.', $key);
        $config = &$this->config;

        foreach ($keys as $key) {
            if (!isset($config[$key])) {
                $config[$key] = [];
            }
            $config = &$config[$key];
        }

        $config = $value;
    }

    public function all(): array
    {
        return $this->config;
    }
}
