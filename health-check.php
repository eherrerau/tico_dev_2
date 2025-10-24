<?php

declare(strict_types=1);

require_once __DIR__ . '/src/bootstrap.php';

use Tico\Config\Config;

$config = Config::getInstance();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TICO - System Health Check</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .status { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .status.success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .status.error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .status.warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
        .section { margin: 20px 0; }
        h1 { color: #0096D6; }
        h2 { color: #333; border-bottom: 2px solid #0096D6; padding-bottom: 5px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .info-item { background: #f8f9fa; padding: 15px; border-radius: 4px; }
        .info-item strong { color: #0096D6; }
        .test-button { background: #0096D6; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin: 10px 5px; }
        .test-button:hover { background: #007bb8; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($config->get('app.name')) ?> - System Health Check</h1>
        
        <div class="section">
            <h2>Environment Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Environment:</strong> <?= htmlspecialchars($config->get('app.env')) ?>
                </div>
                <div class="info-item">
                    <strong>Debug Mode:</strong> <?= $config->get('app.debug') ? 'Enabled' : 'Disabled' ?>
                </div>
                <div class="info-item">
                    <strong>PHP Version:</strong> <?= PHP_VERSION ?>
                </div>
                <div class="info-item">
                    <strong>Timezone:</strong> <?= date_default_timezone_get() ?>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>PHP Extensions</h2>
            
            <?php
            $requiredExtensions = [
                'pdo' => 'PDO Database Extension',
                'mbstring' => 'Multibyte String Extension',
                'json' => 'JSON Extension',
                'curl' => 'cURL Extension',
                'openssl' => 'OpenSSL Extension',
                'zip' => 'ZIP Extension'
            ];
            
            $optionalExtensions = [
                'pdo_sqlsrv' => 'SQL Server PDO Extension (for production)',
                'pdo_mysql' => 'MySQL PDO Extension (alternative)',
                'gd' => 'GD Graphics Extension',
                'intl' => 'Internationalization Extension'
            ];
            
            foreach ($requiredExtensions as $ext => $name) {
                $loaded = extension_loaded($ext);
                $statusClass = $loaded ? 'success' : 'error';
                $statusText = $loaded ? 'Loaded' : 'Missing (Required)';
                echo "<div class='status {$statusClass}'><strong>{$name}:</strong> {$statusText}</div>";
            }
            
            foreach ($optionalExtensions as $ext => $name) {
                $loaded = extension_loaded($ext);
                $statusClass = $loaded ? 'success' : 'warning';
                $statusText = $loaded ? 'Loaded' : 'Not Available (Optional)';
                echo "<div class='status {$statusClass}'><strong>{$name}:</strong> {$statusText}</div>";
            }
            ?>
        </div>

        <div class="section">
            <h2>Directory Permissions</h2>
            
            <?php
            $directories = [
                'logs' => 'Logs Directory',
                'templates_c' => 'Template Cache Directory',
                'vendor' => 'Vendor Directory',
                'src' => 'Source Directory'
            ];
            
            foreach ($directories as $dir => $name) {
                $path = __DIR__ . '/' . $dir;
                $exists = is_dir($path);
                $writable = $exists && is_writable($path);
                
                if ($exists && $writable) {
                    $statusClass = 'success';
                    $statusText = 'Exists and Writable';
                } elseif ($exists) {
                    $statusClass = 'warning';
                    $statusText = 'Exists but Not Writable';
                } else {
                    $statusClass = 'error';
                    $statusText = 'Does Not Exist';
                }
                
                echo "<div class='status {$statusClass}'><strong>{$name} ({$dir}):</strong> {$statusText}</div>";
            }
            ?>
        </div>

        <div class="section">
            <h2>Configuration</h2>
            
            <?php
            $configChecks = [
                'app.key' => 'Application Key',
                'database.default.host' => 'Database Host',
                'database.default.database' => 'Database Name'
            ];
            
            foreach ($configChecks as $key => $name) {
                $value = $config->get($key);
                $hasValue = !empty($value);
                $statusClass = $hasValue ? 'success' : 'warning';
                $statusText = $hasValue ? 'Configured' : 'Not Set';
                
                echo "<div class='status {$statusClass}'><strong>{$name}:</strong> {$statusText}</div>";
            }
            ?>
        </div>

        <div class="section">
            <h2>Database Connection Test</h2>
            
            <?php
            try {
                // Try to initialize database manager
                $dbManager = \Tico\Database\DatabaseManager::getInstance();
                
                // Test if we can get configuration
                $dbConfig = $config->get('database.default');
                
                if (empty($dbConfig['host']) || empty($dbConfig['database'])) {
                    echo "<div class='status warning'><strong>Database Configuration:</strong> Incomplete - Please configure database settings in .env file</div>";
                } else {
                    echo "<div class='status success'><strong>Database Configuration:</strong> Complete</div>";
                    
                    // Try to connect if SQL Server extension is available
                    if (extension_loaded('pdo_sqlsrv')) {
                        try {
                            $connection = $dbManager->getConnection();
                            echo "<div class='status success'><strong>Database Connection:</strong> Successful</div>";
                        } catch (Exception $e) {
                            echo "<div class='status error'><strong>Database Connection:</strong> Failed - " . htmlspecialchars($e->getMessage()) . "</div>";
                        }
                    } else {
                        echo "<div class='status warning'><strong>Database Connection:</strong> Cannot test - SQL Server extension not available on this system</div>";
                    }
                }
            } catch (Exception $e) {
                echo "<div class='status error'><strong>Database System:</strong> Error - " . htmlspecialchars($e->getMessage()) . "</div>";
            }
            ?>
        </div>

        <div class="section">
            <h2>Security Checks</h2>
            
            <?php
            $securityChecks = [
                'Display Errors' => ini_get('display_errors') ? 'Enabled (should be disabled in production)' : 'Disabled ✓',
                'Session Security' => session_get_cookie_params()['httponly'] ? 'HttpOnly cookies enabled ✓' : 'HttpOnly cookies disabled',
                'HTTPS' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'Enabled ✓' : 'Disabled (enable for production)',
                'Upload Max Size' => ini_get('upload_max_filesize'),
                'Memory Limit' => ini_get('memory_limit'),
                'Max Execution Time' => ini_get('max_execution_time') . ' seconds'
            ];
            
            foreach ($securityChecks as $check => $result) {
                $isGood = strpos($result, '✓') !== false || ($check === 'Upload Max Size' || $check === 'Memory Limit' || $check === 'Max Execution Time');
                $statusClass = $isGood ? 'success' : 'warning';
                echo "<div class='status {$statusClass}'><strong>{$check}:</strong> {$result}</div>";
            }
            ?>
        </div>

        <div class="section">
            <h2>Next Steps</h2>
            <div class="status warning">
                <strong>To complete the setup:</strong>
                <ol>
                    <li>Configure your database connection in the .env file</li>
                    <li>Install SQL Server and configure the connection</li>
                    <li>Run database migrations to create tables</li>
                    <li>Set up your web server (Apache/Nginx) to point to the /public directory</li>
                    <li>Configure SSL certificates for production</li>
                    <li>Test the login functionality</li>
                </ol>
            </div>
            
            <div style="margin-top: 20px;">
                <a href="public/login.php" class="test-button">Test Login Page</a>
                <a href="public/index.php" class="test-button">Test Dashboard</a>
            </div>
        </div>

        <div class="section">
            <h2>Quick Actions</h2>
            <p>Run these commands to finalize the setup:</p>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 4px; font-family: monospace;">
                # Set proper permissions<br>
                chmod 755 public/<br>
                chmod 777 logs/ templates_c/<br><br>
                
                # Start PHP development server<br>
                php -S localhost:8000 -t public/<br><br>
                
                # Or use Docker<br>
                docker-compose up --build
            </div>
        </div>
    </div>
</body>
</html>
