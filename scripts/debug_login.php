<?php

declare(strict_types=1);

use Tico\Security\PasswordManager;
use Tico\Database\DatabaseManager;
/**
 * Login Debug Script
 */

require_once __DIR__ . '/../src/bootstrap.php';

use Tico\Services\AuthService;

echo "TICO Login Debug\n";
echo "================\n\n";

// Test database connection first
echo "1. Testing database connection...\n";
try {
    $dbManager = DatabaseManager::getInstance();
    $connection = $dbManager->getConnection('default');
    
    $stmt = $connection->prepare("SELECT id, username, email, role, password FROM users WHERE username = :username");
    $stmt->execute(['username' => 'admin']);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "✅ Database connection OK - Found admin user\n";
        echo '   User ID: ' . $user['id'] . "\n";
        echo '   Username: ' . $user['username'] . "\n";
        echo '   Email: ' . $user['email'] . "\n";
        echo '   Role: ' . $user['role'] . "\n";
    } else {
        echo "❌ Database connection failed - No admin user found\n";
        exit(1);
    }
} catch (Exception $e) {
    echo '❌ Database error: ' . $e->getMessage() . "\n";
    exit(1);
}

echo "\n2. Testing password verification...\n";
try {
    $passwordManager = new PasswordManager();
    $isValid = $passwordManager->verify('admin123', $user['password']);
    if ($isValid) {
        echo "✅ Password verification OK - Use password: admin123\n";
    } else {
        echo "❌ Password verification failed\n";
        echo '   Stored hash: ' . $user['password'] . "\n";
        echo "   Trying to verify 'admin123' against hash\n";
    }
} catch (Exception $e) {
    echo '❌ Password verification error: ' . $e->getMessage() . "\n";
}

echo "\n3. Testing AuthService login...\n";
try {
    $authService = new AuthService();
    $result = $authService->login(['username' => 'admin', 'password' => 'admin123', 'team_id' => '1']);
    echo 'Login result: ' . json_encode($result, JSON_PRETTY_PRINT) . "\n";
} catch (Exception $e) {
    echo '❌ AuthService login error: ' . $e->getMessage() . "\n";
    echo '   Trace: ' . $e->getTraceAsString() . "\n";
}
