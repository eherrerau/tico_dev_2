<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

use Tico\Services\AuthService;
use Tico\Config\Config;

$authService = new AuthService();
$config = Config::getInstance();
$error = '';
$success = '';

// Redirect if already authenticated
if ($authService->isAuthenticated()) {
    header('Location: /index.php');
    exit;
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    try {
        // Validate CSRF token
        if (!$authService->validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $error = 'Security token validation failed. Please try again.';
        } else {
            $result = $authService->login($_POST);

            if ($result['success']) {
                header('Location: /index.php');
                exit;
            }
            $error = $result['message'] ?? 'Login failed';
        }
    } catch (Exception $e) {
        $error = 'An error occurred during login: ' . $e->getMessage();
        // Log the error for debugging
        error_log('Login error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    }
}

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $authService->logout();
    header('Location: /login.php?logout=1');
    exit;
}

// Show logout success message
if (isset($_GET['logout']) && $_GET['logout'] === '1') {
    $success = 'You have been successfully logged out.';
}

// Handle session timeout
if (isset($_GET['timeout']) && $_GET['timeout'] === '1') {
    $error = 'Your session has expired. Please log in again.';
}

$csrfToken = $authService->getCsrfToken();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars((string) $config->get('app.name')) ?> - Login</title>
    
    <!-- Security meta tags -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    
    <!-- Stylesheets -->
    <link href="assets/css/header.css" rel="stylesheet" />
    <link href="assets/css/login.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="assets/css/globalStyle.css" rel="stylesheet" type="text/css" />
    
    <link rel="shortcut icon" type="image/x-icon" href="assets/media/images/favicon.ico">
</head>
<body>
    <div id="header">
        <div id="headerImage">
            <img src="assets/media/images/HPR_White_RGB_150_SM.png" alt="<?= htmlspecialchars((string) $config->get('app.name')) ?>">
        </div>
        <div id="headerTitleH1">
            <h1><?= htmlspecialchars((string) $config->get('app.name')) ?></h1>
        </div>
    </div>
    
    <div id="principal">
        <div id="mainContentLogin">   
            <div id="MainLoginBox">
                <div id="LoginForm">
                    <?php if ($error): ?>
                        <div id="errorMessage" class="alert alert-error">
                            <i class="icon-exclamation-sign"></i>
                            <?= htmlspecialchars((string) $error) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($success !== '' && $success !== '0'): ?>
                        <div id="successMessage" class="alert alert-success">
                            <i class="icon-ok-sign"></i>
                            <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="login.php" method="post" name="loginForm" id="loginForm">
                        <input type="hidden" name="action" value="login">
                        <?= $authService->getCsrfTokenField() ?>
                        
                        <div id="loginFieldsContainer">
                            <div id="usernameContainer">
                                <label for="username">Username:</label>
                                <input type="text" 
                                       name="username" 
                                       id="username" 
                                       required 
                                       autofocus 
                                       autocomplete="username"
                                       maxlength="50"
                                       pattern="[a-zA-Z0-9]+"
                                       title="Username should contain only letters and numbers">
                            </div>
                            
                            <div id="passwordContainer">
                                <label for="password">Password:</label>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       required 
                                       autocomplete="current-password"
                                       maxlength="255">
                            </div>
                            
                            <div id="teamContainer">
                                <label for="team_id">Team:</label>
                                <select name="team_id" id="team_id" required>
                                    <option value="">Select Team</option>
                                    <?php
                                    // Get teams from database
                                    try {
                                        $userModel = new \Tico\Models\TestUser();
                                        $teams = $userModel->getTeams();
                                        foreach ($teams as $team) {
                                            $teamId = $team['teamID'] ?? $team['team'] ?? '1';
                                            $teamName = $team['teamName'] ?? $team['team'] ?? 'Default Team';
                                            echo '<option value="' . htmlspecialchars((string) $teamId) . '">' .
                                                 htmlspecialchars((string) $teamName) . '</option>';
                                        }
                                    } catch (Exception) {
                                        echo '<option value="1">Support</option>';
                                        echo '<option value="2">IT</option>';
                                        echo '<option value="3">Admin</option>';
                                    }
?>
                                </select>
                            </div>
                        </div>
                        
                        <div id="loginButtonContainer">
                            <input type="submit" value="Sign In" id="loginButton" class="btn btn-primary">
                        </div>
                    </form>
                    
                    <div id="loginInfo">
                        <p><small>Please contact your administrator if you have trouble logging in.</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Form validation
            $('#loginForm').on('submit', function(e) {
                const username = $('#username').val().trim();
                const password = $('#password').val();
                const teamId = $('#team_id').val();
                
                if (!username || !password || !teamId) {
                    e.preventDefault();
                    alert('Please fill in all fields.');
                    return false;
                }
                
                // Basic username validation
                if (!/^[a-zA-Z0-9]+$/.test(username)) {
                    e.preventDefault();
                    alert('Username should contain only letters and numbers.');
                    return false;
                }
                
                // Disable submit button to prevent double submission
                $('#loginButton').prop('disabled', true).val('Signing In...');
            });
            
            // Auto-hide messages after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 5000);
            
            // Clear password field on page load (security)
            $('#password').val('');
        });
    </script>
</body>
</html>
