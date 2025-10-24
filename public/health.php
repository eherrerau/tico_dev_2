<?php

declare(strict_types=1);
/**
 * TICO Health Check Endpoint
 *
 * This file provides a simple health check for the application
 * without requiring authentication.
 */

// Set JSON content type
header('Content-Type: application/json');

// Include configuration and basic dependencies
require_once __DIR__ . '/../src/bootstrap.php';

use TICO\Config\Config;
use TICO\Database\DatabaseManager;

$health = [
    'status' => 'ok',
    'timestamp' => date('c'),
    'version' => '2.0.0-modernized',
    'checks' => [],
];

$overallStatus = true;

try {
    // Check configuration
    $config = Config::getInstance();
    $health['checks']['config'] = [
        'status' => 'ok',
        'message' => 'Configuration loaded successfully',
    ];
} catch (Exception $e) {
    $health['checks']['config'] = [
        'status' => 'error',
        'message' => 'Configuration error: ' . $e->getMessage(),
    ];
    $overallStatus = false;
}

try {
    // Check database connection
    $dbManager = DatabaseManager::getInstance();
    $connection = $dbManager->getConnection('test');

    if ($connection) {
        $health['checks']['database'] = [
            'status' => 'ok',
            'message' => 'Database connection successful',
        ];
    } else {
        $health['checks']['database'] = [
            'status' => 'warning',
            'message' => 'Database connection could not be established',
        ];
        $overallStatus = false;
    }
} catch (Exception $e) {
    $health['checks']['database'] = [
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage(),
    ];
    $overallStatus = false;
}

// Check if required directories exist
$requiredDirs = [
    'templates_c' => __DIR__ . '/../templates_c',
    'logs' => __DIR__ . '/../logs',
];

foreach ($requiredDirs as $name => $path) {
    if (is_dir($path) && is_writable($path)) {
        $health['checks']['directory_' . $name] = [
            'status' => 'ok',
            'message' => ucfirst($name) . ' directory is writable',
        ];
    } else {
        $health['checks']['directory_' . $name] = [
            'status' => 'warning',
            'message' => ucfirst($name) . ' directory is not writable or does not exist',
        ];
    }
}

// Check PHP requirements
$phpVersion = PHP_VERSION;
$minPhpVersion = '8.1.0';

if (version_compare($phpVersion, $minPhpVersion, '>=')) {
    $health['checks']['php_version'] = [
        'status' => 'ok',
        'message' => "PHP version $phpVersion is compatible (minimum: $minPhpVersion)",
    ];
} else {
    $health['checks']['php_version'] = [
        'status' => 'error',
        'message' => "PHP version $phpVersion is too old (minimum: $minPhpVersion)",
    ];
    $overallStatus = false;
}

// Check required PHP extensions
$requiredExtensions = ['pdo', 'pdo_sqlsrv', 'openssl', 'json'];
foreach ($requiredExtensions as $requiredExtension) {
    if (extension_loaded($requiredExtension)) {
        $health['checks']['extension_' . $requiredExtension] = [
            'status' => 'ok',
            'message' => "Extension {$requiredExtension} is loaded",
        ];
    } else {
        $health['checks']['extension_' . $requiredExtension] = [
            'status' => 'error',
            'message' => "Extension {$requiredExtension} is not loaded",
        ];
        $overallStatus = false;
    }
}

// Set overall status
$health['status'] = $overallStatus ? 'healthy' : 'unhealthy';

// Set appropriate HTTP status code
http_response_code($overallStatus ? 200 : 503);

// Output JSON response
echo json_encode($health, JSON_PRETTY_PRINT);
