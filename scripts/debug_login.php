<?php
/**
 * Login Debug Script
 */

require_once __DIR__ . '/../src/bootstrap.php';

use Tico\Services\AuthService;
use Tico\Models\TestUser;

echo "TICO Login Debug\n";
echo "================\n\n";

// Test database connection first
echo "1. Testing database connection...\n";
try {
    $userModel = new TestUser();
    $user = $userModel->findByUsername('admin');
    if ($user) {
        echo "✅ Database connection OK - Found admin user\n";
        echo "   User details: " . json_encode($user, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo "❌ Database connection failed - No admin user found\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n2. Testing password verification...\n";
try {
    $passwordManager = new \Tico\Security\PasswordManager();
    $isValid = $passwordManager->verify('admin123', $user['password_hash']);
    if ($isValid) {
        echo "✅ Password verification OK\n";
    } else {
        echo "❌ Password verification failed\n";
        echo "   Stored hash: " . $user['password_hash'] . "\n";
    }
} catch (Exception $e) {
    echo "❌ Password verification error: " . $e->getMessage() . "\n";
}

echo "\n3. Testing AuthService login...\n";
try {
    $authService = new AuthService();
    $result = $authService->login(['username' => 'admin', 'password' => 'admin123', 'team_id' => '1']);
    echo "Login result: " . json_encode($result, JSON_PRETTY_PRINT) . "\n";
} catch (Exception $e) {
    echo "❌ AuthService login error: " . $e->getMessage() . "\n";
    echo "   Trace: " . $e->getTraceAsString() . "\n";
}
?>
