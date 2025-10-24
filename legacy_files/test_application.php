<?php

declare(strict_types=1);

// Test login functionality
echo "🧪 TESTING TICO APPLICATION LOGIN\n";
echo "================================\n\n";

// Test login with cURL
function testLogin($username, $password) {
    $loginUrl = 'http://localhost:8080/login.php';
    
    // First, get the login page to extract CSRF token
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $loginUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
    curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
    
    $loginPageContent = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "📄 Login page response: HTTP $httpCode\n";
    
    if ($httpCode === 200) {
        echo "✅ Login page loads successfully\n";
        
        // Extract CSRF token from the page
        preg_match('/name="csrf_token" value="([^"]+)"/', $loginPageContent, $matches);
        $csrfToken = $matches[1] ?? '';
        
        if ($csrfToken) {
            echo "🔑 CSRF token extracted: " . substr($csrfToken, 0, 10) . "...\n";
            
            // Attempt login
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $loginUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'username' => $username,
                'password' => $password,
                'csrf_token' => $csrfToken
            ]));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
            curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
            
            $loginResult = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
            curl_close($ch);
            
            echo "📨 Login POST response: HTTP $httpCode\n";
            
            if ($httpCode === 302 && strpos($redirectUrl, 'index.php') !== false) {
                echo "✅ Login successful! Redirecting to dashboard\n";
                return true;
            } else {
                echo "❌ Login failed\n";
                echo "Redirect URL: $redirectUrl\n";
                return false;
            }
        } else {
            echo "❌ Could not extract CSRF token\n";
            return false;
        }
    } else {
        echo "❌ Login page failed to load\n";
        return false;
    }
}

// Test database connectivity
echo "🗄️ Testing database connectivity...\n";
try {
    require_once __DIR__ . '/src/bootstrap.php';
    $db = Tico\Database\DatabaseManager::getInstance()->getConnection('test');
    $userCount = $db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    echo "✅ Database connected successfully - $userCount users found\n\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test credentials
$testCredentials = [
    ['admin', 'admin123'],
    ['jdoe', 'password123'],
    ['asmith', 'password123']
];

foreach ($testCredentials as [$username, $password]) {
    echo "👤 Testing login for user: $username\n";
    $success = testLogin($username, $password);
    echo ($success ? "✅ SUCCESS" : "❌ FAILED") . "\n\n";
}

// Test application modules
echo "🧩 Testing application modules...\n";
$modules = [
    '/modules/dashboardStats.php',
    '/modules/casesList.php',
    '/modules/engineersGrid.php',
    '/modules/newsBoxModern.php'
];

foreach ($modules as $module) {
    if (file_exists(__DIR__ . $module)) {
        echo "✅ Module exists: $module\n";
    } else {
        echo "❌ Module missing: $module\n";
    }
}

echo "\n🎉 APPLICATION TEST COMPLETE!\n";
echo "Access the application at: http://localhost:8080\n";
echo "Use credentials: admin/admin123 or jdoe/password123\n";
