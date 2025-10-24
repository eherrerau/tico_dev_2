<?php

declare(strict_types=1);

// Autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use Tico\Config\Config;
use Tico\Database\DatabaseManager;

// Error reporting based on environment
$config = Config::getInstance();

if ($config->get('app.debug')) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Set timezone
date_default_timezone_set($config->get('app.timezone'));

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

if (!$config->get('app.debug')) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}

// Content Security Policy
$csp = [
    "default-src 'self'",
    "script-src 'self' 'unsafe-inline'", // Note: Remove unsafe-inline in production
    "style-src 'self' 'unsafe-inline'",
    "img-src 'self' data:",
    "font-src 'self'",
    "connect-src 'self'",
    "frame-ancestors 'none'",
    "base-uri 'self'",
    "form-action 'self'",
];
header('Content-Security-Policy: ' . implode('; ', $csp));

// Global exception handler
set_exception_handler(function (Throwable $throwable) use ($config): void {
    error_log($throwable->getMessage() . "\n" . $throwable->getTraceAsString());

    if ($config->get('app.debug')) {
        echo '<h1>Error</h1>';
        echo '<p>' . htmlspecialchars($throwable->getMessage()) . '</p>';
        echo '<pre>' . htmlspecialchars($throwable->getTraceAsString()) . '</pre>';
    } else {
        http_response_code(500);
        echo '<h1>Internal Server Error</h1>';
        echo '<p>An error occurred. Please try again later.</p>';
    }
});

// Initialize database connection
try {
    DatabaseManager::getInstance();
} catch (Exception $e) {
    error_log('Database connection failed: ' . $e->getMessage());

    if ($config->get('app.debug')) {
        die('Database connection failed: ' . $e->getMessage());
    }
    die('Service temporarily unavailable. Please try again later.');
}

// Create logs directory if it doesn't exist
$logsPath = $config->get('logging.path');
if (!is_dir($logsPath)) {
    mkdir($logsPath, 0755, true);
}
