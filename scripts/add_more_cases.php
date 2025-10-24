<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

use Tico\Database\DatabaseManager;

try {
    $dbManager = DatabaseManager::getInstance();
    $connection = $dbManager->getConnection('test');
    
    echo "🎯 AGREGANDO MÁS CASOS DE PRUEBA REALISTAS\n";
    echo "==========================================\n\n";
    
    // Obtener usuarios e IDs de productos para casos realistas
    $users = $connection->query("SELECT id, username, full_name FROM users WHERE role LIKE '%Engineer%'")->fetchAll(PDO::FETCH_ASSOC);
    $products = $connection->query("SELECT id, name FROM products")->fetchAll(PDO::FETCH_ASSOC);
    
    // Casos adicionales más realistas y variados
    $additionalCases = [
        // Casos críticos
        [
            'case_number' => 'HP-2024-009',
            'title' => 'Complete system failure - production printer offline',
            'description' => 'Main production LaserJet Pro 9015 completely unresponsive. Error code 49.4C02 displayed. Business operations severely impacted as this handles all invoice printing.',
            'severity' => 'Critical',
            'status' => 'Open',
            'customer_name' => 'Acme Manufacturing Corp',
            'created_days_ago' => 0,
            'updated_hours_ago' => 2
        ],
        [
            'case_number' => 'HP-2024-010',
            'title' => 'Security breach - printer accessible from external network',
            'description' => 'OfficeJet Pro X Series detected sending data to unknown external IP. Potential security vulnerability. IT security team alerted.',
            'severity' => 'Critical',
            'status' => 'Escalated',
            'priority' => 'P1',
            'customer_name' => 'SecureBank Financial',
            'customer_email' => 'security@securebank.com',
            'customer_phone' => '+1-555-0123',
            'created_days_ago' => 1,
            'updated_hours_ago' => 1
        ],
        
        // Casos de alta prioridad
        [
            'case_number' => 'HP-2024-011',
            'title' => 'Network printer queue backing up - 200+ jobs pending',
            'description' => 'LaserJet Enterprise MFP M528 processing jobs extremely slowly. Print queue has 200+ jobs backed up affecting entire accounting department.',
            'severity' => 'High',
            'status' => 'In Progress',
            'priority' => 'P2',
            'customer_name' => 'GlobalTech Solutions',
            'customer_email' => 'helpdesk@globaltech.com',
            'customer_phone' => '+1-555-0156',
            'created_days_ago' => 2,
            'updated_hours_ago' => 4
        ],
        [
            'case_number' => 'HP-2024-012',
            'title' => 'Color calibration severely off - marketing materials unusable',
            'description' => 'HP DesignJet T1700 producing colors way off specification. Marketing team cannot print brochures for tomorrow\'s trade show. Urgent calibration needed.',
            'severity' => 'High',
            'status' => 'Open',
            'priority' => 'P2',
            'customer_name' => 'Creative Design Studio',
            'customer_email' => 'production@creativedesign.com',
            'customer_phone' => '+1-555-0187',
            'created_days_ago' => 0,
            'updated_hours_ago' => 3
        ],
        [
            'case_number' => 'HP-2024-013',
            'title' => 'Frequent paper jams in tray 2 - 15+ times daily',
            'description' => 'LaserJet Pro 4301dw constantly jamming in tray 2 with standard 20lb paper. Rollers appear worn. Affecting daily operations significantly.',
            'severity' => 'High',
            'status' => 'Open',
            'priority' => 'P2',
            'customer_name' => 'MidSize Business Corp',
            'customer_email' => 'it-support@midsizebiz.com',
            'customer_phone' => '+1-555-0143',
            'created_days_ago' => 3,
            'updated_hours_ago' => 6
        ],
        
        // Casos normales
        [
            'case_number' => 'HP-2024-014',
            'title' => 'Toner replacement procedure clarification needed',
            'description' => 'Customer needs guidance on replacing toner cartridge in LaserJet Pro M404dn. First time replacement, wants to avoid damage to printer.',
            'severity' => 'Normal',
            'status' => 'Open',
            'priority' => 'P3',
            'customer_name' => 'Small Office Solutions',
            'customer_email' => 'admin@smalloffice.com',
            'customer_phone' => '+1-555-0167',
            'created_days_ago' => 1,
            'updated_hours_ago' => 8
        ],
        [
            'case_number' => 'HP-2024-015',
            'title' => 'Setup wireless printing for new employee laptops',
            'description' => 'Need to configure 5 new Dell laptops to print to existing HP OfficeJet Pro 9010. Windows 11 systems, need driver installation guidance.',
            'severity' => 'Normal',
            'status' => 'In Progress',
            'priority' => 'P3',
            'customer_name' => 'Growing Startup Inc',
            'customer_email' => 'hr@growingstartup.com',
            'customer_phone' => '+1-555-0134',
            'created_days_ago' => 4,
            'updated_hours_ago' => 12
        ],
        [
            'case_number' => 'HP-2024-016',
            'title' => 'Monthly maintenance schedule setup required',
            'description' => 'Customer wants to establish preventive maintenance schedule for 3 LaserJet Enterprise printers. Need recommendation on frequency and procedures.',
            'severity' => 'Normal',
            'status' => 'Open',
            'priority' => 'P3',
            'customer_name' => 'Reliable Services LLC',
            'customer_email' => 'facilities@reliableservices.com',
            'customer_phone' => '+1-555-0178',
            'created_days_ago' => 5,
            'updated_hours_ago' => 10
        ],
        
        // Casos resueltos recientemente
        [
            'case_number' => 'HP-2024-017',
            'title' => 'Driver update resolved connectivity issues',
            'description' => 'Updated printer drivers on Windows 10 systems resolved intermittent connection drops. Customer confirmed stable operation for 48 hours.',
            'severity' => 'Normal',
            'status' => 'Resolved',
            'priority' => 'P3',
            'customer_name' => 'Steady Business Co',
            'customer_email' => 'support@steadybusiness.com',
            'customer_phone' => '+1-555-0145',
            'created_days_ago' => 7,
            'updated_hours_ago' => 24
        ],
        [
            'case_number' => 'HP-2024-018',
            'title' => 'Replaced faulty duplexer unit - testing complete',
            'description' => 'Duplexer unit replacement completed on LaserJet Enterprise M507. Double-sided printing now working correctly. Customer signed off on repair.',
            'severity' => 'High',
            'status' => 'Resolved',
            'priority' => 'P2',
            'customer_name' => 'Important Client Ltd',
            'customer_email' => 'operations@importantclient.com',
            'customer_phone' => '+1-555-0189',
            'created_days_ago' => 10,
            'updated_hours_ago' => 48
        ],
        
        // Casos escalados
        [
            'case_number' => 'HP-2024-019',
            'title' => 'Firmware update caused boot loop - engineering review needed',
            'description' => 'LaserJet Enterprise M612 stuck in boot loop after firmware update. Standard recovery procedures failed. Escalating to engineering team for advanced diagnostics.',
            'severity' => 'High',
            'status' => 'Escalated',
            'priority' => 'P2',
            'customer_name' => 'TechCorp Enterprises',
            'customer_email' => 'critical-support@techcorp.com',
            'customer_phone' => '+1-555-0192',
            'created_days_ago' => 6,
            'updated_hours_ago' => 2
        ],
        
        // Casos en progreso con diferentes ingenieros
        [
            'case_number' => 'HP-2024-020',
            'title' => 'Large format poster printing alignment issues',
            'description' => 'HP DesignJet T650 producing posters with misaligned graphics. Customer is architecture firm needing precise blueprints. Investigating media settings.',
            'severity' => 'Normal',
            'status' => 'In Progress',
            'priority' => 'P3',
            'customer_name' => 'Precision Architecture',
            'customer_email' => 'drafting@precisionarch.com',
            'customer_phone' => '+1-555-0176',
            'created_days_ago' => 2,
            'updated_hours_ago' => 6
        ],
        [
            'case_number' => 'HP-2024-021',
            'title' => 'Envelope printing setup - multiple sizes needed',
            'description' => 'Law firm needs to print various envelope sizes (#10, #9, legal size). HP LaserJet Pro 4301dw configuration assistance required for different paper trays.',
            'severity' => 'Normal',
            'status' => 'In Progress',
            'priority' => 'P3',
            'customer_name' => 'Legal Eagles LLP',
            'customer_email' => 'admin@legaleagles.com',
            'customer_phone' => '+1-555-0165',
            'created_days_ago' => 1,
            'updated_hours_ago' => 4
        ],
        
        // Casos críticos adicionales
        [
            'case_number' => 'HP-2024-022',
            'title' => 'Print server communication failure - 50+ printers affected',
            'description' => 'Enterprise print server lost communication with entire floor of printers. 50+ HP LaserJet devices showing offline. Network team investigating.',
            'severity' => 'Critical',
            'status' => 'Escalated',
            'priority' => 'P1',
            'customer_name' => 'MegaCorp International',
            'customer_email' => 'emergency@megacorp.com',
            'customer_phone' => '+1-555-0111',
            'created_days_ago' => 0,
            'updated_hours_ago' => 1
        ],
        [
            'case_number' => 'HP-2024-023',
            'title' => 'Ink system contamination - multicolor bleeding',
            'description' => 'HP OfficeJet Pro X Series showing severe color bleeding. All colors printing muddy brown. Suspected ink system contamination. Urgent replacement needed.',
            'severity' => 'Critical',
            'status' => 'Open',
            'priority' => 'P1',
            'customer_name' => 'Print Shop Express',
            'customer_email' => 'urgent@printshopexpress.com',
            'customer_phone' => '+1-555-0188',
            'created_days_ago' => 0,
            'updated_hours_ago' => 3
        ]
    ];
    
    $insertedCount = 0;
    
    foreach ($additionalCases as $caseData) {
        // Asignar ingeniero al azar
        $assignedUser = !empty($users) ? $users[array_rand($users)] : null;
        $assignedUserId = $assignedUser ? $assignedUser['id'] : null;
        
        // Asignar producto al azar
        $assignedProduct = !empty($products) ? $products[array_rand($products)] : null;
        $productId = $assignedProduct ? $assignedProduct['id'] : 1;
        
        // Calcular fechas
        $createdAt = date('Y-m-d H:i:s', strtotime("-{$caseData['created_days_ago']} days"));
        $updatedAt = date('Y-m-d H:i:s', strtotime("-{$caseData['updated_hours_ago']} hours"));
        
        try {
            $stmt = $connection->prepare("
                INSERT INTO cases (
                    case_number, title, description, severity, status,
                    customer_name, assigned_to, product_id, created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $caseData['case_number'],
                $caseData['title'],
                $caseData['description'],
                $caseData['severity'],
                $caseData['status'],
                $caseData['customer_name'],
                $assignedUserId,
                $productId,
                $createdAt,
                $updatedAt
            ]);
            
            $insertedCount++;
            echo "✅ Caso creado: {$caseData['case_number']} - {$caseData['title']}\n";
            
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'UNIQUE constraint failed') !== false) {
                echo "⚠️ Caso ya existe: {$caseData['case_number']}\n";
            } else {
                echo "❌ Error creando caso {$caseData['case_number']}: {$e->getMessage()}\n";
            }
        }
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "🎉 CASOS ADICIONALES AGREGADOS EXITOSAMENTE!\n";
    echo "Nuevos casos insertados: $insertedCount\n";
    
    // Mostrar resumen actualizado
    $totalCases = $connection->query('SELECT COUNT(*) FROM cases')->fetchColumn();
    $criticalCases = $connection->query("SELECT COUNT(*) FROM cases WHERE severity = 'Critical'")->fetchColumn();
    $highCases = $connection->query("SELECT COUNT(*) FROM cases WHERE severity = 'High'")->fetchColumn();
    $openCases = $connection->query("SELECT COUNT(*) FROM cases WHERE status = 'Open'")->fetchColumn();
    $inProgressCases = $connection->query("SELECT COUNT(*) FROM cases WHERE status = 'In Progress'")->fetchColumn();
    $escalatedCases = $connection->query("SELECT COUNT(*) FROM cases WHERE status = 'Escalated'")->fetchColumn();
    $resolvedCases = $connection->query("SELECT COUNT(*) FROM cases WHERE status = 'Resolved'")->fetchColumn();
    
    echo "\n📊 RESUMEN DE CASOS EN LA BASE DE DATOS:\n";
    echo "Total de casos: $totalCases\n";
    echo "Por severidad:\n";
    echo "  - Críticos: $criticalCases\n";
    echo "  - Alta prioridad: $highCases\n";
    echo "Por estado:\n";
    echo "  - Abiertos: $openCases\n";
    echo "  - En progreso: $inProgressCases\n";
    echo "  - Escalados: $escalatedCases\n";
    echo "  - Resueltos: $resolvedCases\n";
    
    echo "\n🚀 ¡Ahora la aplicación tendrá datos mucho más ricos para visualizar!\n";
    echo "Refresca la página en http://localhost:8080 para ver los nuevos casos.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
