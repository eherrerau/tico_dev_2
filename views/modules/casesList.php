<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/bootstrap.php';

use Tico\Database\DatabaseManager;

try {
    $dbManager = DatabaseManager::getInstance();
    $connection = $dbManager->getConnection('test');
    
    // Get cases for today and recent
    $query = "
        SELECT 
            c.case_number,
            c.title,
            c.severity,
            c.status,
            c.customer_name,
            u.full_name as assigned_to,
            p.name as product_name,
            c.created_at,
            c.updated_at
        FROM cases c
        LEFT JOIN users u ON c.assigned_to = u.id
        LEFT JOIN products p ON c.product_id = p.id
        ORDER BY 
            CASE c.severity 
                WHEN 'Critical' THEN 1 
                WHEN 'High' THEN 2 
                WHEN 'Normal' THEN 3 
                ELSE 4 
            END,
            c.created_at DESC
        LIMIT 10
    ";
    
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $cases = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    $cases = [];
}
?>

<div class="cases-list-container">
    <h3><i class="icon-list-alt"></i> Recent Cases</h3>
    
    <?php if (empty($cases)): ?>
        <div class="no-data">
            <p><i class="icon-info-sign"></i> No cases found. <a href="#" onclick="refreshCases()">Refresh</a></p>
        </div>
    <?php else: ?>
        <div class="cases-table-wrapper">
            <table class="cases-table">
                <thead>
                    <tr>
                        <th>Case #</th>
                        <th>Title</th>
                        <th>Severity</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Customer</th>
                        <th>Age</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cases as $case): ?>
                        <?php
                        $severityClass = strtolower($case['severity']);
                        $statusClass = strtolower(str_replace(' ', '-', $case['status']));
                        $caseAge = '';
                        
                        if ($case['created_at']) {
                            $created = new DateTime($case['created_at']);
                            $now = new DateTime();
                            $diff = $now->diff($created);
                            
                            if ($diff->days > 0) {
                                $caseAge = $diff->days . 'd';
                            } elseif ($diff->h > 0) {
                                $caseAge = $diff->h . 'h';
                            } else {
                                $caseAge = $diff->i . 'm';
                            }
                        }
                        ?>
                        <tr class="case-row" onclick="viewCase('<?= htmlspecialchars($case['case_number']) ?>')">
                            <td class="case-number">
                                <strong><?= htmlspecialchars($case['case_number']) ?></strong>
                            </td>
                            <td class="case-title" title="<?= htmlspecialchars($case['title']) ?>">
                                <?= htmlspecialchars(strlen($case['title']) > 30 ? substr($case['title'], 0, 30) . '...' : $case['title']) ?>
                            </td>
                            <td class="case-severity">
                                <span class="severity-badge <?= $severityClass ?>">
                                    <?php if ($case['severity'] === 'Critical'): ?>
                                        <i class="icon-asterisk"></i>
                                    <?php elseif ($case['severity'] === 'High'): ?>
                                        <i class="icon-circle"></i>
                                    <?php else: ?>
                                        <i class="icon-minus"></i>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($case['severity']) ?>
                                </span>
                            </td>
                            <td class="case-status">
                                <span class="status-badge <?= $statusClass ?>">
                                    <?= htmlspecialchars($case['status']) ?>
                                </span>
                            </td>
                            <td class="case-assigned">
                                <?= htmlspecialchars($case['assigned_to'] ?: 'Unassigned') ?>
                            </td>
                            <td class="case-customer" title="<?= htmlspecialchars($case['customer_name']) ?>">
                                <?= htmlspecialchars(strlen($case['customer_name']) > 20 ? substr($case['customer_name'], 0, 20) . '...' : $case['customer_name']) ?>
                            </td>
                            <td class="case-age">
                                <span class="age-badge"><?= $caseAge ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="cases-footer">
            <small>
                <i class="icon-info"></i> 
                Showing <?= count($cases) ?> recent cases. 
                <a href="#" onclick="viewAllCases()">View All Cases</a>
            </small>
        </div>
    <?php endif; ?>
</div>

<style>
.cases-list-container {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 15px;
    margin: 10px 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.cases-list-container h3 {
    margin: 0 0 15px 0;
    color: #333;
    border-bottom: 1px solid #eee;
    padding-bottom: 8px;
}

.cases-table-wrapper {
    overflow-x: auto;
    max-height: 300px;
    overflow-y: auto;
}

.cases-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}

.cases-table th {
    background: #f5f5f5;
    padding: 8px 6px;
    text-align: left;
    border-bottom: 2px solid #ddd;
    font-weight: bold;
    position: sticky;
    top: 0;
}

.cases-table td {
    padding: 6px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

.case-row:hover {
    background: #f9f9f9;
    cursor: pointer;
}

.severity-badge {
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 10px;
    font-weight: bold;
    text-transform: uppercase;
}

.severity-badge.critical {
    background: #d9534f;
    color: white;
}

.severity-badge.high {
    background: #f0ad4e;
    color: white;
}

.severity-badge.normal {
    background: #5cb85c;
    color: white;
}

.status-badge {
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 10px;
    font-weight: bold;
}

.status-badge.open {
    background: #5bc0de;
    color: white;
}

.status-badge.in-progress {
    background: #f0ad4e;
    color: white;
}

.status-badge.resolved {
    background: #5cb85c;
    color: white;
}

.status-badge.escalated {
    background: #d9534f;
    color: white;
}

.age-badge {
    background: #f5f5f5;
    padding: 2px 4px;
    border-radius: 2px;
    font-size: 10px;
}

.cases-footer {
    margin-top: 10px;
    text-align: center;
    color: #666;
}

.no-data {
    text-align: center;
    padding: 20px;
    color: #666;
}
</style>

<script>
function viewCase(caseNumber) {
    alert('View case: ' + caseNumber + '\n(Feature would open case details)');
}

function viewAllCases() {
    alert('View all cases\n(Feature would navigate to cases page)');
}

function refreshCases() {
    location.reload();
}
</script>
