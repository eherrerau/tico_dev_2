<?php
/**
 * Test login functionality via HTTP request
 */

$url = 'http://localhost:8080/login.php';

// First, get the login page to extract CSRF token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');

$loginPage = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "Login page HTTP status: $httpCode\n";

if ($httpCode === 200) {
    echo "✅ Login page loads successfully\n";
    
    // Extract CSRF token
    if (preg_match('/name="csrf_token" value="([^"]+)"/', $loginPage, $matches)) {
        $csrfToken = $matches[1];
        echo "✅ CSRF token extracted: " . substr($csrfToken, 0, 10) . "...\n";
        
        // Test login with valid credentials
        $postData = [
            'action' => 'login',
            'csrf_token' => $csrfToken,
            'username' => 'admin',
            'password' => 'admin123',
            'team_id' => 'IT'
        ];
        
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_HEADER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        echo "\nLogin attempt HTTP status: $httpCode\n";
        
        if (strpos($response, 'Location:') !== false) {
            echo "✅ Login successful - got redirect response\n";
            
            // Extract redirect location
            if (preg_match('/Location: (.+)/i', $response, $matches)) {
                $redirectUrl = trim($matches[1]);
                echo "Redirect URL: $redirectUrl\n";
            }
        } else {
            echo "Login response:\n";
            echo substr($response, 0, 500) . "...\n";
        }
        
    } else {
        echo "❌ Could not extract CSRF token from login page\n";
        echo "Page content preview:\n";
        echo substr($loginPage, 0, 500) . "...\n";
    }
} else {
    echo "❌ Failed to load login page\n";
}

curl_close($ch);

// Clean up
@unlink('/tmp/cookies.txt');
