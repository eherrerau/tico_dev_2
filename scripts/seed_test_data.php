<?php

declare(strict_types=1);
/**
 * Test Data Seeder for TICO Application
 *
 * This script populates the database with realistic test data
 * for engineers, cases, schedules, and other entities.
 */

require_once __DIR__ . '/../src/bootstrap.php';

use Tico\Database\DatabaseManager;
use Tico\Security\PasswordManager;

echo "🌱 TICO Test Data Seeder\n";
echo "========================\n\n";

try {
    $dbManager = DatabaseManager::getInstance();
    $passwordManager = new PasswordManager();
    
    // Get SQLite connection
    $connection = $dbManager->getConnection('test');
    
    echo "📊 Seeding database with test data...\n";
    
    // Create additional tables if they don't exist
    $createTables = "
        -- Teams table
        CREATE TABLE IF NOT EXISTS teams (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            active INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        -- Products table  
        CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            active INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        -- Cases table
        CREATE TABLE IF NOT EXISTS cases (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            case_number VARCHAR(50) UNIQUE NOT NULL,
            title VARCHAR(200) NOT NULL,
            description TEXT,
            severity VARCHAR(20) DEFAULT 'Normal',
            status VARCHAR(20) DEFAULT 'Open',
            assigned_to INTEGER,
            product_id INTEGER,
            customer_name VARCHAR(100),
            created_by INTEGER,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (assigned_to) REFERENCES users(id),
            FOREIGN KEY (product_id) REFERENCES products(id),
            FOREIGN KEY (created_by) REFERENCES users(id)
        );
        
        -- Schedule exceptions table
        CREATE TABLE IF NOT EXISTS schedule_exceptions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            exception_type VARCHAR(50) NOT NULL,
            date_from DATE NOT NULL,
            date_to DATE NOT NULL,
            all_day INTEGER DEFAULT 0,
            start_time TIME,
            end_time TIME,
            reason TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        );
        
        -- News/announcements table
        CREATE TABLE IF NOT EXISTS news (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(200) NOT NULL,
            content TEXT NOT NULL,
            author_id INTEGER,
            published INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (author_id) REFERENCES users(id)
        );
    ";
    
    $connection->exec($createTables);
    echo "✅ Database tables created/verified\n";
    
    // Seed teams
    $teams = [
        ['Support', 'Technical Support Team'],
        ['IT', 'Information Technology Team'], 
        ['Engineering', 'Product Engineering Team'],
        ['QA', 'Quality Assurance Team'],
        ['Management', 'Management Team']
    ];
    
    $teamIds = [];
    foreach ($teams as $team) {
        $stmt = $connection->prepare("INSERT OR IGNORE INTO teams (name, description) VALUES (?, ?)");
        $stmt->execute($team);
        
        // Get the team ID
        $stmt = $connection->prepare("SELECT id FROM teams WHERE name = ?");
        $stmt->execute([$team[0]]);
        $teamIds[$team[0]] = $stmt->fetchColumn();
    }
    echo "✅ Teams seeded\n";
    
    // Seed products
    $products = [
        ['HP LaserJet Pro', 'Professional laser printing solutions'],
        ['HP DeskJet Plus', 'All-in-one inkjet printers for home and office'],
        ['HP PageWide Pro', 'High-speed business inkjet printers'],
        ['HP OfficeJet Pro', 'Professional color inkjet printers'],
        ['HP Envy Photo', 'Photo printing and creative projects'],
        ['HP Smart Tank', 'Refillable ink tank printers'],
        ['HP Color LaserJet', 'Professional color laser printers'],
        ['HP DesignJet', 'Large format printing solutions']
    ];
    
    $productIds = [];
    foreach ($products as $product) {
        $stmt = $connection->prepare("INSERT OR IGNORE INTO products (name, description) VALUES (?, ?)");
        $stmt->execute($product);
        
        // Get the product ID
        $stmt = $connection->prepare("SELECT id FROM products WHERE name = ?");
        $stmt->execute([$product[0]]);
        $productIds[] = $stmt->fetchColumn();
    }
    echo "✅ Products seeded\n";
    
    // Seed additional users
    $additionalUsers = [
        [
            'username' => 'sarah_tech',
            'password' => 'sarah123',
            'full_name' => 'Sarah Johnson',
            'email' => 'sarah.johnson@tico.local',
            'team' => 'Support',
            'role' => 'engineer'
        ],
        [
            'username' => 'mike_senior',
            'password' => 'mike123', 
            'full_name' => 'Michael Chen',
            'email' => 'mike.chen@tico.local',
            'team' => 'Engineering',
            'role' => 'senior_engineer'
        ],
        [
            'username' => 'lisa_qa',
            'password' => 'lisa123',
            'full_name' => 'Lisa Rodriguez',
            'email' => 'lisa.rodriguez@tico.local',
            'team' => 'QA',
            'role' => 'qa_engineer'
        ],
        [
            'username' => 'david_mgr',
            'password' => 'david123',
            'full_name' => 'David Smith',
            'email' => 'david.smith@tico.local',
            'team' => 'Management',
            'role' => 'manager'
        ],
        [
            'username' => 'anna_it',
            'password' => 'anna123',
            'full_name' => 'Anna Wilson',
            'email' => 'anna.wilson@tico.local',
            'team' => 'IT',
            'role' => 'engineer'
        ]
    ];
    
    $userIds = [1, 2, 3]; // Existing admin, engineer1, user1
    
    foreach ($additionalUsers as $user) {
        $hashedPassword = $passwordManager->hash($user['password']);
        
        $stmt = $connection->prepare("
            INSERT OR IGNORE INTO users 
            (username, password, full_name, email, team, role, status) 
            VALUES (?, ?, ?, ?, ?, ?, 'active')
        ");
        
        $stmt->execute([
            $user['username'],
            $hashedPassword,
            $user['full_name'],
            $user['email'],
            $user['team'],
            $user['role']
        ]);
        
        // Get the user ID
        $stmt = $connection->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$user['username']]);
        $userId = $stmt->fetchColumn();
        if ($userId) {
            $userIds[] = $userId;
        }
    }
    echo "✅ Additional users seeded\n";
    
    // Seed cases
    $caseData = [
        [
            'HP-2024-001',
            'Printer not responding to print jobs',
            'Customer reports that HP LaserJet Pro 400 is not processing print jobs from Windows 11. Jobs appear in queue but never print.',
            'High',
            'Open',
            'John Customer Corp'
        ],
        [
            'HP-2024-002', 
            'Paper jam error persists after clearing',
            'HP DeskJet Plus 4155 shows paper jam error even after clearing all paper. Error code 13.20.00 displayed.',
            'Critical',
            'In Progress',
            'Tech Solutions LLC'
        ],
        [
            'HP-2024-003',
            'Print quality issues - streaking',
            'HP Color LaserJet Pro M479 producing prints with vertical streaks. Toner levels appear normal.',
            'Normal',
            'Open', 
            'ABC Marketing'
        ],
        [
            'HP-2024-004',
            'WiFi connection drops frequently',
            'HP OfficeJet Pro 9015 loses WiFi connection every few hours. Requires manual reconnection.',
            'High',
            'Escalated',
            'Small Business Inc'
        ],
        [
            'HP-2024-005',
            'Scanner not detected by software',
            'HP Envy Photo 7855 scanner function not recognized by HP Smart app on macOS Sonoma.',
            'Normal',
            'Resolved',
            'Home User'
        ],
        [
            'HP-2024-006',
            'Ink system failure after refill',
            'HP Smart Tank 7005 showing ink system failure after customer refilled tanks.',
            'Critical',
            'Open',
            'Educational Services'
        ],
        [
            'HP-2024-007',
            'Large format print alignment issues',
            'HP DesignJet T650 producing misaligned prints on A1 paper size. Calibration attempted.',
            'High',
            'In Progress',
            'Architecture Firm'
        ],
        [
            'HP-2024-008',
            'Duplex printing mechanism jammed',
            'HP PageWide Pro 577 duplex unit stuck. Manual attempts to clear unsuccessful.',
            'High',
            'Open',
            'Legal Services'
        ]
    ];
    
    foreach ($caseData as $index => $case) {
        $stmt = $connection->prepare("
            INSERT OR IGNORE INTO cases 
            (case_number, title, description, severity, status, assigned_to, product_id, customer_name, created_by, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, datetime('now', '-' || ? || ' days'), datetime('now', '-' || ? || ' hours'))
        ");
        
        $daysAgo = rand(1, 30);
        $hoursAgo = rand(1, 72);
        $assignedTo = $userIds[array_rand($userIds)];
        $productId = $productIds[array_rand($productIds)];
        $createdBy = $userIds[array_rand($userIds)];
        
        $stmt->execute([
            $case[0], $case[1], $case[2], $case[3], $case[4],
            $assignedTo, $productId, $case[5], $createdBy, $daysAgo, $hoursAgo
        ]);
    }
    echo "✅ Cases seeded\n";
    
    // Seed schedule exceptions
    $exceptionTypes = ['Vacation', 'Sick Leave', 'Training', 'Meeting', 'Personal', 'On-call'];
    
    for ($i = 0; $i < 15; $i++) {
        $userId = $userIds[array_rand($userIds)];
        $type = $exceptionTypes[array_rand($exceptionTypes)];
        $startDate = date('Y-m-d', strtotime('-' . rand(1, 60) . ' days'));
        $endDate = date('Y-m-d', strtotime($startDate . ' +' . rand(1, 5) . ' days'));
        $allDay = rand(0, 1);
        
        $stmt = $connection->prepare("
            INSERT OR IGNORE INTO schedule_exceptions 
            (user_id, exception_type, date_from, date_to, all_day, start_time, end_time, reason) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $startTime = $allDay ? null : sprintf('%02d:%02d:00', rand(8, 16), rand(0, 59));
        $endTime = $allDay ? null : sprintf('%02d:%02d:00', rand(17, 20), rand(0, 59));
        $reason = "Scheduled $type - automated entry";
        
        $stmt->execute([$userId, $type, $startDate, $endDate, $allDay, $startTime, $endTime, $reason]);
    }
    echo "✅ Schedule exceptions seeded\n";
    
    // Seed news/announcements
    $newsData = [
        [
            'New HP LaserJet Pro Series Released',
            'We are excited to announce the launch of the new HP LaserJet Pro 400 series. Enhanced security features and improved print speeds make this our best professional printer yet. Training sessions will be scheduled for all support staff.',
            1
        ],
        [
            'System Maintenance Window - This Weekend',
            'Please note that our internal systems will undergo maintenance this Saturday from 2 AM to 6 AM EST. During this time, case management system may be unavailable. Plan your work accordingly.',
            1  
        ],
        [
            'Q4 Performance Review Cycle Begins',
            'The Q4 performance review cycle has officially started. Please ensure all case documentation is up to date and schedule meetings with your team leads by the end of next week.',
            4
        ],
        [
            'New Troubleshooting Guide Available',
            'A comprehensive troubleshooting guide for WiFi connectivity issues has been published in our knowledge base. This covers the most common scenarios we encounter with HP wireless printers.',
            2
        ],
        [
            'Customer Satisfaction Survey Results',
            'Great news! Our customer satisfaction scores have improved by 15% this quarter. Special thanks to the support team for their dedication to excellent customer service.',
            4
        ]
    ];
    
    foreach ($newsData as $news) {
        $stmt = $connection->prepare("
            INSERT OR IGNORE INTO news (title, content, author_id, created_at) 
            VALUES (?, ?, ?, datetime('now', '-' || ? || ' hours'))
        ");
        
        $hoursAgo = rand(6, 168); // Between 6 hours and 1 week ago
        $stmt->execute([$news[0], $news[1], $news[2], $hoursAgo]);
    }
    echo "✅ News articles seeded\n";
    
    // Display summary
    $stats = [];
    $tables = ['users', 'teams', 'products', 'cases', 'schedule_exceptions', 'news'];
    
    foreach ($tables as $table) {
        $stmt = $connection->prepare("SELECT COUNT(*) FROM $table");
        $stmt->execute();
        $stats[$table] = $stmt->fetchColumn();
    }
    
    echo "\n📈 Database Summary:\n";
    echo "==================\n";
    foreach ($stats as $table => $count) {
        echo sprintf("%-20s: %d records\n", ucfirst(str_replace('_', ' ', $table)), $count);
    }
    
    echo "\n🎯 Test Credentials:\n";
    echo "===================\n";
    echo "Admin:        admin / admin123\n";
    echo "Engineer:     engineer1 / engineer123\n";
    echo "User:         user1 / user123\n";
    echo "Support:      sarah_tech / sarah123\n";
    echo "Senior Eng:   mike_senior / mike123\n";
    echo "QA:           lisa_qa / lisa123\n";
    echo "Manager:      david_mgr / david123\n";
    echo "IT:           anna_it / anna123\n";
    
    echo "\n✅ Test data seeding completed successfully!\n";
    echo "🚀 The application now has realistic data for testing.\n";
    
} catch (Exception $e) {
    echo "❌ Error seeding test data: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
