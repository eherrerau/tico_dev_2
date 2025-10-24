<?php
/**
 * Login Test Script
 * 
 * This script tests the login functionality with test credentials
 */

require_once __DIR__ . '/../src/bootstrap.php';

use Tico\Services\AuthService;

echo "TICO Login Test\n";
echo "===============\n\n";

$authService = new AuthService();

// Test credentials
$testLogins = [
    [
        'username' => 'admin',
        'password' => 'admin123',
        'team_id' => '1'
    ],
    [
        'username' => 'engineer1', 
        'password' => 'engineer123',
        'team_id' => '1'
    ],
    [
        'username' => 'user1',
        'password' => 'user123', 
        'team_id' => '1'
    ]
];

foreach ($testLogins as $i => $credentials) {
    echo "Test " . ($i + 1) . ": Testing login for {$credentials['username']}\n";
    
    try {
        $result = $authService->login($credentials);
        
        if ($result['success']) {
            echo "✅ SUCCESS: User {$credentials['username']} logged in successfully\n";
            
            // Test getting current user
            $currentUser = $authService->getCurrentUser();
            if ($currentUser) {
                echo "   User details: {$currentUser['nameToDisplay']} ({$currentUser['usrName']})\n";
            }
            
            // Logout for next test
            $authService->logout();
            echo "   Logged out successfully\n";
        } else {
            echo "❌ FAILED: {$result['message']}\n";
        }
    } catch (Exception $e) {
        echo "❌ ERROR: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}

echo "Login test completed!\n";
?>
