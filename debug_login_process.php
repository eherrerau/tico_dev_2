<?php
/**
 * Debug login process to see what error is occurring
 */

declare(strict_types=1);

// Simulate the POST request that would be made by the form
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['action'] = 'login';
$_POST['username'] = 'admin';
$_POST['password'] = 'admin123';
$_POST['team_id'] = 'IT';

// Start output buffering to capture any output
ob_start();

try {
    // Include the bootstrap to set up dependencies
    require_once __DIR__ . '/src/bootstrap.php';
    
    echo "Creating AuthService...\n";
    $authService = new \Tico\Services\AuthService();
    
    echo "Generating CSRF token...\n";
    // Start session to get CSRF token
    session_start();
    $_POST['csrf_token'] = $authService->getCsrfToken();
    
    echo "Testing login with credentials:\n";
    echo "Username: " . $_POST['username'] . "\n";
    echo "Team: " . $_POST['team_id'] . "\n";
    
    $result = $authService->login($_POST);
    
    echo "Login result:\n";
    print_r($result);
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

$output = ob_get_clean();
echo $output;
