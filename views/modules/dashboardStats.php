<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/bootstrap.php';

use Tico\Database\DatabaseManager;

try {
    $dbManager = DatabaseManager::getInstance();
    $connection = $dbManager->getConnection();
    
    // Get various statistics
    $stats = [];
    
    // Total cases
    $stmt = $connection->query("SELECT COUNT(*) as total FROM cases");
    $stats['total_cases'] = $stmt->fetchColumn();
    
    // Open cases
    $stmt = $connection->query("SELECT COUNT(*) as open FROM cases WHERE status = 'Open'");
    $stats['open_cases'] = $stmt->fetchColumn();
    
    // Critical cases
    $stmt = $connection->query("SELECT COUNT(*) as critical FROM cases WHERE severity = 'Critical'");
    $stats['critical_cases'] = $stmt->fetchColumn();
    
    // Available engineers
    $stmt = $connection->query("SELECT COUNT(*) as available FROM users WHERE is_available = 1 AND role LIKE '%Engineer%'");
    $stats['available_engineers'] = $stmt->fetchColumn();
    
    // Total engineers
    $stmt = $connection->query("SELECT COUNT(*) as total FROM users WHERE role LIKE '%Engineer%'");
    $stats['total_engineers'] = $stmt->fetchColumn();
    
    // Cases by severity
    $stmt = $connection->query("
        SELECT severity, COUNT(*) as count 
        FROM cases 
        GROUP BY severity 
        ORDER BY 
            CASE severity 
                WHEN 'Critical' THEN 1 
                WHEN 'High' THEN 2 
                WHEN 'Normal' THEN 3 
                ELSE 4 
            END
    ");
    $severity_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Cases by status
    $stmt = $connection->query("SELECT status, COUNT(*) as count FROM cases GROUP BY status");
    $status_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Recent activity (last 24 hours)
    $stmt = $connection->query("
        SELECT COUNT(*) as recent 
        FROM cases 
        WHERE created_at >= datetime('now', '-1 day')
    ");
    $stats['recent_cases'] = $stmt->fetchColumn();
    
    // Average resolution time (mock data for now)
    $stats['avg_resolution'] = '2.3 hours';
    
    // SLA compliance (mock data)
    $stats['sla_compliance'] = 87;
    
} catch (Exception $e) {
    $stats = [
        'total_cases' => 0,
        'open_cases' => 0,
        'critical_cases' => 0,
        'available_engineers' => 0,
        'total_engineers' => 0,
        'recent_cases' => 0,
        'avg_resolution' => 'N/A',
        'sla_compliance' => 0
    ];
    $severity_stats = [];
    $status_stats = [];
}
?>

<div class="dashboard-stats-container">
    <!-- Key Metrics Cards -->
    <div class="stats-cards">
        <div class="stat-card urgent">
            <div class="stat-icon">
                <i class="icon-fire"></i>
            </div>
            <div class="stat-content">
                <h3><?= $stats['critical_cases'] ?></h3>
                <p>Critical Cases</p>
                <small class="stat-trend">
                    <?php if ($stats['critical_cases'] > 0): ?>
                        <i class="icon-warning-sign"></i> Needs attention
                    <?php else: ?>
                        <i class="icon-ok-sign"></i> All clear
                    <?php endif; ?>
                </small>
            </div>
        </div>
        
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="icon-tasks"></i>
            </div>
            <div class="stat-content">
                <h3><?= $stats['open_cases'] ?></h3>
                <p>Open Cases</p>
                <small class="stat-trend">
                    of <?= $stats['total_cases'] ?> total
                </small>
            </div>
        </div>
        
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="icon-user"></i>
            </div>
            <div class="stat-content">
                <h3><?= $stats['available_engineers'] ?></h3>
                <p>Available Engineers</p>
                <small class="stat-trend">
                    of <?= $stats['total_engineers'] ?> total
                </small>
            </div>
        </div>
        
        <div class="stat-card info">
            <div class="stat-icon">
                <i class="icon-time"></i>
            </div>
            <div class="stat-content">
                <h3><?= $stats['avg_resolution'] ?></h3>
                <p>Avg Resolution</p>
                <small class="stat-trend">
                    <i class="icon-arrow-down"></i> Improving
                </small>
            </div>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="stats-charts">
        <!-- Severity Distribution -->
        <div class="chart-card">
            <h4><i class="icon-bar-chart"></i> Cases by Severity</h4>
            <div class="severity-chart">
                <?php if (!empty($severity_stats)): ?>
                    <?php foreach ($severity_stats as $item): ?>
                        <?php
                        $percentage = $stats['total_cases'] > 0 ? round(($item['count'] / $stats['total_cases']) * 100) : 0;
                        $severityClass = strtolower($item['severity']);
                        ?>
                        <div class="severity-item">
                            <div class="severity-label">
                                <span class="severity-dot <?= $severityClass ?>"></span>
                                <?= htmlspecialchars($item['severity']) ?>
                                <span class="severity-count">(<?= $item['count'] ?>)</span>
                            </div>
                            <div class="severity-bar">
                                <div class="severity-fill <?= $severityClass ?>" style="width: <?= $percentage ?>%"></div>
                            </div>
                            <div class="severity-percentage"><?= $percentage ?>%</div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-data">No data available</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Status Distribution -->
        <div class="chart-card">
            <h4><i class="icon-list-alt"></i> Cases by Status</h4>
            <div class="status-chart">
                <?php if (!empty($status_stats)): ?>
                    <?php foreach ($status_stats as $item): ?>
                        <?php
                        $percentage = $stats['total_cases'] > 0 ? round(($item['count'] / $stats['total_cases']) * 100) : 0;
                        $statusClass = strtolower(str_replace(' ', '-', $item['status']));
                        ?>
                        <div class="status-item">
                            <div class="status-label">
                                <span class="status-dot <?= $statusClass ?>"></span>
                                <?= htmlspecialchars($item['status']) ?>
                                <span class="status-count">(<?= $item['count'] ?>)</span>
                            </div>
                            <div class="status-bar">
                                <div class="status-fill <?= $statusClass ?>" style="width: <?= $percentage ?>%"></div>
                            </div>
                            <div class="status-percentage"><?= $percentage ?>%</div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-data">No data available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Performance Metrics -->
    <div class="performance-metrics">
        <div class="metric-card">
            <h4><i class="icon-trophy"></i> SLA Compliance</h4>
            <div class="sla-gauge">
                <div class="gauge-container">
                    <div class="gauge-fill" style="width: <?= $stats['sla_compliance'] ?>%"></div>
                </div>
                <div class="gauge-value"><?= $stats['sla_compliance'] ?>%</div>
            </div>
        </div>
        
        <div class="metric-card">
            <h4><i class="icon-calendar"></i> Activity Today</h4>
            <div class="activity-summary">
                <div class="activity-item">
                    <i class="icon-plus"></i>
                    <span><?= $stats['recent_cases'] ?> new cases</span>
                </div>
                <div class="activity-item">
                    <i class="icon-ok"></i>
                    <span><?= $stats['total_cases'] - $stats['open_cases'] ?> resolved</span>
                </div>
                <div class="activity-item">
                    <i class="icon-user"></i>
                    <span><?= $stats['available_engineers'] ?> engineers online</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-stats-container {
    margin: 10px 0;
}

.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.stat-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 15px;
    display: flex;
    align-items: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.stat-card.urgent {
    border-left: 4px solid #d9534f;
}

.stat-card.primary {
    border-left: 4px solid #337ab7;
}

.stat-card.success {
    border-left: 4px solid #5cb85c;
}

.stat-card.info {
    border-left: 4px solid #5bc0de;
}

.stat-icon {
    font-size: 24px;
    margin-right: 15px;
    width: 40px;
    text-align: center;
}

.stat-card.urgent .stat-icon {
    color: #d9534f;
}

.stat-card.primary .stat-icon {
    color: #337ab7;
}

.stat-card.success .stat-icon {
    color: #5cb85c;
}

.stat-card.info .stat-icon {
    color: #5bc0de;
}

.stat-content h3 {
    margin: 0;
    font-size: 24px;
    font-weight: bold;
    color: #333;
}

.stat-content p {
    margin: 0;
    color: #666;
    font-size: 12px;
}

.stat-trend {
    font-size: 10px;
    color: #999;
}

.stats-charts {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.chart-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 15px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.chart-card h4 {
    margin: 0 0 15px 0;
    color: #333;
    border-bottom: 1px solid #eee;
    padding-bottom: 8px;
}

.severity-item, .status-item {
    display: grid;
    grid-template-columns: 1fr 100px 40px;
    gap: 10px;
    align-items: center;
    margin-bottom: 8px;
    font-size: 12px;
}

.severity-label, .status-label {
    display: flex;
    align-items: center;
    gap: 5px;
}

.severity-dot, .status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.severity-dot.critical, .status-dot.escalated {
    background: #d9534f;
}

.severity-dot.high {
    background: #f0ad4e;
}

.severity-dot.normal {
    background: #5cb85c;
}

.status-dot.open {
    background: #5bc0de;
}

.status-dot.in-progress {
    background: #f0ad4e;
}

.status-dot.resolved {
    background: #5cb85c;
}

.severity-bar, .status-bar {
    background: #f5f5f5;
    border-radius: 2px;
    height: 6px;
    position: relative;
}

.severity-fill, .status-fill {
    height: 100%;
    border-radius: 2px;
    transition: width 0.3s ease;
}

.severity-fill.critical, .status-fill.escalated {
    background: #d9534f;
}

.severity-fill.high {
    background: #f0ad4e;
}

.severity-fill.normal {
    background: #5cb85c;
}

.status-fill.open {
    background: #5bc0de;
}

.status-fill.in-progress {
    background: #f0ad4e;
}

.status-fill.resolved {
    background: #5cb85c;
}

.severity-percentage, .status-percentage {
    text-align: right;
    font-weight: bold;
}

.performance-metrics {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.metric-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 15px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.metric-card h4 {
    margin: 0 0 15px 0;
    color: #333;
    border-bottom: 1px solid #eee;
    padding-bottom: 8px;
}

.sla-gauge {
    text-align: center;
}

.gauge-container {
    background: #f5f5f5;
    border-radius: 10px;
    height: 20px;
    margin-bottom: 10px;
    position: relative;
    overflow: hidden;
}

.gauge-fill {
    height: 100%;
    background: linear-gradient(90deg, #d9534f 0%, #f0ad4e 50%, #5cb85c 100%);
    border-radius: 10px;
    transition: width 0.3s ease;
}

.gauge-value {
    font-size: 24px;
    font-weight: bold;
    color: #333;
}

.activity-summary {
    space-y: 8px;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-size: 12px;
    color: #666;
}

.activity-item i {
    color: #5cb85c;
}

.no-data {
    text-align: center;
    color: #999;
    font-style: italic;
}

@media (max-width: 768px) {
    .stats-cards {
        grid-template-columns: 1fr;
    }
    
    .stats-charts {
        grid-template-columns: 1fr;
    }
    
    .performance-metrics {
        grid-template-columns: 1fr;
    }
}
</style>
