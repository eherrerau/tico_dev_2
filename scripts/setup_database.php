<?php
/**
 * Database Setup Script
 * 
 * This script creates a local SQLite database for testing purposes
 * when MSSQL is not available.
 */

require_once __DIR__ . '/../src/bootstrap.php';

use Tico\Config\Config;
use Tico\Security\PasswordManager;

echo "TICO Database Setup Script\n";
echo "==========================\n\n";

$config = Config::getInstance();

// Create SQLite database for testing
$dbPath = __DIR__ . '/../data/tico_test.db';
$dataDir = dirname($dbPath);

if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
    echo "Created data directory: $dataDir\n";
}

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to SQLite database: $dbPath\n";
    
    // Create users table
    $createUsersTable = "
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            full_name VARCHAR(100) NOT NULL,
            email VARCHAR(100),
            team VARCHAR(50),
            role VARCHAR(20) DEFAULT 'user',
            status VARCHAR(20) DEFAULT 'active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ";
    
    $pdo->exec($createUsersTable);
    echo "Created users table\n";
    
    // Create sessions table
    $createSessionsTable = "
        CREATE TABLE IF NOT EXISTS sessions (
            id VARCHAR(128) PRIMARY KEY,
            user_id INTEGER NOT NULL,
            data TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )
    ";
    
    $pdo->exec($createSessionsTable);
    echo "Created sessions table\n";
    
    // Create test users
    $passwordManager = new PasswordManager();
    
    $testUsers = [
        [
            'username' => 'admin',
            'password' => 'admin123',
            'full_name' => 'Administrator',
            'email' => 'admin@tico.local',
            'team' => 'IT',
            'role' => 'admin'
        ],
        [
            'username' => 'engineer1',
            'password' => 'engineer123',
            'full_name' => 'John Engineer',
            'email' => 'john@tico.local',
            'team' => 'Support',
            'role' => 'engineer'
        ],
        [
            'username' => 'user1',
            'password' => 'user123',
            'full_name' => 'Jane User',
            'email' => 'jane@tico.local',
            'team' => 'Support',
            'role' => 'user'
        ]
    ];
    
    $insertUser = $pdo->prepare("
        INSERT OR REPLACE INTO users 
        (username, password, full_name, email, team, role) 
        VALUES (:username, :password, :full_name, :email, :team, :role)
    ");
    
    foreach ($testUsers as $user) {
        $hashedPassword = $passwordManager->hash($user['password']);
        
        $insertUser->execute([
            'username' => $user['username'],
            'password' => $hashedPassword,
            'full_name' => $user['full_name'],
            'email' => $user['email'],
            'team' => $user['team'],
            'role' => $user['role']
        ]);
        
        echo "Created test user: {$user['username']} (password: {$user['password']})\n";
    }
    
    echo "\nDatabase setup completed successfully!\n";
    echo "\nTest credentials:\n";
    echo "- Admin: admin / admin123\n";
    echo "- Engineer: engineer1 / engineer123\n";
    echo "- User: user1 / user123\n";
    echo "\nYou can now test the login functionality.\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
