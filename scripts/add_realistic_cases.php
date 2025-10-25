<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

use Tico\Database\DatabaseManager;

try {
    $dbManager = DatabaseManager::getInstance();
    $connection = $dbManager->getConnection('test');
    
    echo "🎯 AGREGANDO CASOS DE PRUEBA REALISTAS\n";
    echo "=====================================\n\n";
    
    // Obtener usuarios e IDs de productos
    $users = $connection->query("SELECT id, username, full_name FROM users WHERE role LIKE '%Engineer%'")->fetchAll(PDO::FETCH_ASSOC);
    $products = $connection->query("SELECT id, name FROM products")->fetchAll(PDO::FETCH_ASSOC);
    
    // Casos realistas con solo los campos que existen en la tabla
    $newCases = [
        [
            'case_number' => 'HP-2024-025',
            'title' => 'Complete system failure - production printer offline',
            'description' => 'Main production LaserJet Pro 9015 completely unresponsive. Error code 49.4C02 displayed. Business operations severely impacted.',
            'severity' => 'Critical',
            'status' => 'Open',
            'customer_name' => 'Acme Manufacturing Corp',
            'days_ago' => 0
        ],
        [
            'case_number' => 'HP-2024-026',
            'title' => 'Security breach - printer accessible from external network',
            'description' => 'OfficeJet Pro X Series detected sending data to unknown external IP. Potential security vulnerability discovered.',
            'severity' => 'Critical',
            'status' => 'Escalated',
            'customer_name' => 'SecureBank Financial',
            'days_ago' => 1
        ],
        [
            'case_number' => 'HP-2024-027',
            'title' => 'Network printer queue backing up - 200+ jobs pending',
            'description' => 'LaserJet Enterprise MFP M528 processing jobs extremely slowly. Print queue has 200+ jobs backed up affecting entire accounting department.',
            'severity' => 'High',
            'status' => 'In Progress',
            'customer_name' => 'GlobalTech Solutions',
            'days_ago' => 2
        ],
        [
            'case_number' => 'HP-2024-028',
            'title' => 'Color calibration severely off - marketing materials unusable',
            'description' => 'HP DesignJet T1700 producing colors way off specification. Marketing team cannot print brochures for trade show.',
            'severity' => 'High',
            'status' => 'Open',
            'customer_name' => 'Creative Design Studio',
            'days_ago' => 0
        ],
        [
            'case_number' => 'HP-2024-029',
            'title' => 'Frequent paper jams in tray 2 - 15+ times daily',
            'description' => 'LaserJet Pro 4301dw constantly jamming in tray 2 with standard 20lb paper. Rollers appear worn.',
            'severity' => 'High',
            'status' => 'Open',
            'customer_name' => 'MidSize Business Corp',
            'days_ago' => 3
        ],
        [
            'case_number' => 'HP-2024-030',
            'title' => 'Toner replacement procedure clarification needed',
            'description' => 'Customer needs guidance on replacing toner cartridge in LaserJet Pro M404dn. First time replacement.',
            'severity' => 'Normal',
            'status' => 'Open',
            'customer_name' => 'Small Office Solutions',
            'days_ago' => 1
        ],
        [
            'case_number' => 'HP-2024-031',
            'title' => 'Setup wireless printing for new employee laptops',
            'description' => 'Need to configure 5 new Dell laptops to print to existing HP OfficeJet Pro 9010. Windows 11 systems.',
            'severity' => 'Normal',
            'status' => 'In Progress',
            'customer_name' => 'Growing Startup Inc',
            'days_ago' => 4
        ],
        [
            'case_number' => 'HP-2024-032',
            'title' => 'Monthly maintenance schedule setup required',
            'description' => 'Customer wants to establish preventive maintenance schedule for 3 LaserJet Enterprise printers.',
            'severity' => 'Normal',
            'status' => 'Open',
            'customer_name' => 'Reliable Services LLC',
            'days_ago' => 5
        ],
        [
            'case_number' => 'HP-2024-033',
            'title' => 'Driver update resolved connectivity issues',
            'description' => 'Updated printer drivers on Windows 10 systems resolved intermittent connection drops. Customer confirmed stable operation.',
            'severity' => 'Normal',
            'status' => 'Resolved',
            'customer_name' => 'Steady Business Co',
            'days_ago' => 7
        ],
        [
            'case_number' => 'HP-2024-034',
            'title' => 'Replaced faulty duplexer unit - testing complete',
            'description' => 'Duplexer unit replacement completed on LaserJet Enterprise M507. Double-sided printing now working correctly.',
            'severity' => 'High',
            'status' => 'Resolved',
            'customer_name' => 'Important Client Ltd',
            'days_ago' => 10
        ],
        [
            'case_number' => 'HP-2024-035',
            'title' => 'Firmware update caused boot loop - engineering review needed',
            'description' => 'LaserJet Enterprise M612 stuck in boot loop after firmware update. Standard recovery procedures failed.',
            'severity' => 'High',
            'status' => 'Escalated',
            'customer_name' => 'TechCorp Enterprises',
            'days_ago' => 6
        ],
        [
            'case_number' => 'HP-2024-036',
            'title' => 'Large format poster printing alignment issues',
            'description' => 'HP DesignJet T650 producing posters with misaligned graphics. Architecture firm needing precise blueprints.',
            'severity' => 'Normal',
            'status' => 'In Progress',
            'customer_name' => 'Precision Architecture',
            'days_ago' => 2
        ],
        [
            'case_number' => 'HP-2024-037',
            'title' => 'Envelope printing setup - multiple sizes needed',
            'description' => 'Law firm needs to print various envelope sizes. HP LaserJet Pro 4301dw configuration assistance required.',
            'severity' => 'Normal',
            'status' => 'In Progress',
            'customer_name' => 'Legal Eagles LLP',
            'days_ago' => 1
        ],
        [
            'case_number' => 'HP-2024-038',
            'title' => 'Print server communication failure - 50+ printers affected',
            'description' => 'Enterprise print server lost communication with entire floor of printers. 50+ HP LaserJet devices showing offline.',
            'severity' => 'Critical',
            'status' => 'Escalated',
            'customer_name' => 'MegaCorp International',
            'days_ago' => 0
        ],
        [
            'case_number' => 'HP-2024-039',
            'title' => 'Ink system contamination - multicolor bleeding',
            'description' => 'HP OfficeJet Pro X Series showing severe color bleeding. All colors printing muddy brown. Suspected ink system contamination.',
            'severity' => 'Critical',
            'status' => 'Open',
            'customer_name' => 'Print Shop Express',
            'days_ago' => 0
        ],
        [
            'case_number' => 'HP-2024-040',
            'title' => 'Scanner glass cracked - replacement part needed',
            'description' => 'HP OfficeJet Pro 9015 scanner glass has visible crack affecting scan quality. Customer needs replacement part ordering guidance.',
            'severity' => 'Normal',
            'status' => 'Open',
            'customer_name' => 'Document Services Inc',
            'days_ago' => 3
        ],
        [
            'case_number' => 'HP-2024-041',
            'title' => 'Multiple print jobs stuck in queue - cannot cancel',
            'description' => 'LaserJet Pro 4025 has 15 jobs stuck in queue that cannot be cancelled through normal means. Printer unresponsive to new jobs.',
            'severity' => 'High',
            'status' => 'Open',
            'customer_name' => 'Busy Office LLC',
            'days_ago' => 1
        ],
        [
            'case_number' => 'HP-2024-042',
            'title' => 'Automatic document feeder not working properly',
            'description' => 'HP LaserJet Enterprise MFP M528 ADF pulling multiple sheets or skipping pages during scanning. Needs adjustment.',
            'severity' => 'Normal',
            'status' => 'In Progress',
            'customer_name' => 'Professional Services Corp',
            'days_ago' => 2
        ],
        [
            'case_number' => 'HP-2024-043',
            'title' => 'Toner cartridge not recognized after installation',
            'description' => 'Genuine HP toner cartridge not being recognized in LaserJet Pro M404dn. Error message indicates incompatible cartridge.',
            'severity' => 'Normal',
            'status' => 'Open',
            'customer_name' => 'Quality Printing Solutions',
            'days_ago' => 1
        ],
        [
            'case_number' => 'HP-2024-044',
            'title' => 'Network configuration lost after power outage',
            'description' => 'HP OfficeJet Pro 9010 lost all network settings after power outage. Need to reconfigure wireless and IP settings.',
            'severity' => 'High',
            'status' => 'Open',
            'customer_name' => 'Remote Office Branch',
            'days_ago' => 0
        ]
    ];
    
    $insertedCount = 0;
    
    foreach ($newCases as $caseData) {
        // Asignar ingeniero al azar
        $assignedUser = !empty($users) ? $users[array_rand($users)] : null;
        $assignedUserId = $assignedUser ? $assignedUser['id'] : null;
        
        // Asignar producto al azar
        $assignedProduct = !empty($products) ? $products[array_rand($products)] : null;
        $productId = $assignedProduct ? $assignedProduct['id'] : 1;
        
        // Calcular fechas
        $createdAt = date('Y-m-d H:i:s', strtotime("-{$caseData['days_ago']} days -" . rand(1, 23) . " hours"));
        $updatedAt = date('Y-m-d H:i:s', strtotime("-" . rand(1, 12) . " hours"));
        
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
            $assignedName = $assignedUser ? $assignedUser['full_name'] : 'Unassigned';
            echo "✅ {$caseData['case_number']} - {$caseData['severity']} - {$caseData['status']} - Asignado a: $assignedName\n";
            
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'UNIQUE constraint failed') !== false) {
                echo "⚠️ Caso ya existe: {$caseData['case_number']}\n";
            } else {
                echo "❌ Error creando caso {$caseData['case_number']}: {$e->getMessage()}\n";
            }
        }
    }
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "🎉 CASOS ADICIONALES AGREGADOS EXITOSAMENTE!\n";
    echo "Nuevos casos insertados: $insertedCount\n";
    
    // Mostrar resumen actualizado
    $totalCases = $connection->query('SELECT COUNT(*) FROM cases')->fetchColumn();
    $criticalCases = $connection->query("SELECT COUNT(*) FROM cases WHERE severity = 'Critical'")->fetchColumn();
    $highCases = $connection->query("SELECT COUNT(*) FROM cases WHERE severity = 'High'")->fetchColumn();
    $normalCases = $connection->query("SELECT COUNT(*) FROM cases WHERE severity = 'Normal'")->fetchColumn();
    $openCases = $connection->query("SELECT COUNT(*) FROM cases WHERE status = 'Open'")->fetchColumn();
    $inProgressCases = $connection->query("SELECT COUNT(*) FROM cases WHERE status = 'In Progress'")->fetchColumn();
    $escalatedCases = $connection->query("SELECT COUNT(*) FROM cases WHERE status = 'Escalated'")->fetchColumn();
    $resolvedCases = $connection->query("SELECT COUNT(*) FROM cases WHERE status = 'Resolved'")->fetchColumn();
    
    echo "\n📊 RESUMEN COMPLETO DE CASOS EN LA BASE DE DATOS:\n";
    echo str_repeat("-", 60) . "\n";
    echo "🔢 Total de casos: $totalCases\n\n";
    
    echo "📈 Por severidad:\n";
    echo "  🔴 Críticos: $criticalCases casos\n";
    echo "  🟡 Alta prioridad: $highCases casos\n";
    echo "  🟢 Normales: $normalCases casos\n\n";
    
    echo "📋 Por estado:\n";
    echo "  🆕 Abiertos: $openCases casos\n";
    echo "  🔄 En progreso: $inProgressCases casos\n";
    echo "  ⬆️ Escalados: $escalatedCases casos\n";
    echo "  ✅ Resueltos: $resolvedCases casos\n";
    
    echo "\n🚀 ¡La aplicación ahora tiene $totalCases casos para mostrar!\n";
    echo "🌐 Refresca la página en http://localhost:8080 para ver todos los casos.\n";
    echo "👤 Usa las credenciales: admin/admin123 para acceder.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
