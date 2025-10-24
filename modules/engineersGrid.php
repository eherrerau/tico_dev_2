<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

use Tico\Database\DatabaseManager;

try {
    $dbManager = DatabaseManager::getInstance();
    $connection = $dbManager->getConnection('test');
    
    // Get users with their current status and roles
    $query = "
        SELECT 
            u.id,
            u.username,
            u.full_name,
            u.email,
            u.role,
            u.is_available,
            u.location,
            CASE 
                WHEN u.is_available = 1 THEN 'Available'
                ELSE 'Unavailable'
            END as status,
            COUNT(c.id) as active_cases
        FROM users u
        LEFT JOIN cases c ON c.assigned_to = u.id AND c.status IN ('Open', 'In Progress')
        WHERE u.role IN ('Engineer', 'Senior Engineer', 'Team Lead', 'Manager')
        GROUP BY u.id, u.username, u.full_name, u.email, u.role, u.is_available, u.location
        ORDER BY 
            CASE u.role 
                WHEN 'Manager' THEN 1 
                WHEN 'Team Lead' THEN 2 
                WHEN 'Senior Engineer' THEN 3 
                WHEN 'Engineer' THEN 4 
                ELSE 5 
            END,
            u.full_name
    ";
    
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $engineers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    $engineers = [];
}
?>

<div class="engineers-list-container">
    <h3><i class="icon-user"></i> Engineering Team</h3>
    
    <?php if (empty($engineers)): ?>
        <div class="no-data">
            <p><i class="icon-info-sign"></i> No engineers found. <a href="#" onclick="refreshEngineers()">Refresh</a></p>
        </div>
    <?php else: ?>
        <div class="engineers-grid">
            <?php foreach ($engineers as $engineer): ?>
                <?php
                $statusClass = $engineer['is_available'] ? 'available' : 'unavailable';
                $roleClass = strtolower(str_replace(' ', '-', $engineer['role']));
                ?>
                <div class="engineer-card <?= $statusClass ?>" onclick="viewEngineer(<?= $engineer['id'] ?>)">
                    <div class="engineer-header">
                        <div class="engineer-avatar">
                            <?= strtoupper(substr($engineer['full_name'], 0, 1)) ?>
                        </div>
                        <div class="engineer-info">
                            <h4><?= htmlspecialchars($engineer['full_name']) ?></h4>
                            <span class="engineer-username">@<?= htmlspecialchars($engineer['username']) ?></span>
                        </div>
                        <div class="status-indicator <?= $statusClass ?>">
                            <?php if ($engineer['is_available']): ?>
                                <i class="icon-ok-circle"></i>
                            <?php else: ?>
                                <i class="icon-ban-circle"></i>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="engineer-details">
                        <div class="engineer-role">
                            <span class="role-badge <?= $roleClass ?>">
                                <?php if ($engineer['role'] === 'Manager'): ?>
                                    <i class="icon-star"></i>
                                <?php elseif ($engineer['role'] === 'Team Lead'): ?>
                                    <i class="icon-flag"></i>
                                <?php elseif ($engineer['role'] === 'Senior Engineer'): ?>
                                    <i class="icon-certificate"></i>
                                <?php else: ?>
                                    <i class="icon-wrench"></i>
                                <?php endif; ?>
                                <?= htmlspecialchars($engineer['role']) ?>
                            </span>
                        </div>
                        
                        <?php if (!empty($engineer['location'])): ?>
                            <div class="engineer-location">
                                <i class="icon-map-marker"></i>
                                <?= htmlspecialchars($engineer['location']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="engineer-workload">
                            <i class="icon-tasks"></i>
                            <?= $engineer['active_cases'] ?> active case<?= $engineer['active_cases'] != 1 ? 's' : '' ?>
                        </div>
                    </div>
                    
                    <div class="engineer-actions">
                        <button type="button" class="btn-small" onclick="event.stopPropagation(); assignCase(<?= $engineer['id'] ?>)">
                            <i class="icon-plus"></i> Assign Case
                        </button>
                        <button type="button" class="btn-small" onclick="event.stopPropagation(); sendMessage(<?= $engineer['id'] ?>)">
                            <i class="icon-envelope"></i> Message
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="engineers-footer">
            <small>
                <i class="icon-info"></i> 
                Showing <?= count($engineers) ?> team members. 
                Available: <?= count(array_filter($engineers, fn($e) => $e['is_available'])) ?> | 
                Busy: <?= count(array_filter($engineers, fn($e) => !$e['is_available'])) ?>
            </small>
        </div>
    <?php endif; ?>
</div>

<style>
.engineers-list-container {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 15px;
    margin: 10px 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.engineers-list-container h3 {
    margin: 0 0 15px 0;
    color: #333;
    border-bottom: 1px solid #eee;
    padding-bottom: 8px;
}

.engineers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 15px;
}

.engineer-card {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 12px;
    background: #fafafa;
    transition: all 0.3s ease;
    cursor: pointer;
}

.engineer-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateY(-1px);
}

.engineer-card.available {
    border-left: 3px solid #5cb85c;
}

.engineer-card.unavailable {
    border-left: 3px solid #d9534f;
}

.engineer-header {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.engineer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #337ab7;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 10px;
}

.engineer-info {
    flex: 1;
}

.engineer-info h4 {
    margin: 0;
    font-size: 14px;
    color: #333;
}

.engineer-username {
    font-size: 11px;
    color: #666;
}

.status-indicator {
    font-size: 16px;
}

.status-indicator.available {
    color: #5cb85c;
}

.status-indicator.unavailable {
    color: #d9534f;
}

.engineer-details {
    margin-bottom: 10px;
    font-size: 11px;
}

.engineer-details > div {
    margin-bottom: 4px;
}

.role-badge {
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 10px;
    font-weight: bold;
}

.role-badge.manager {
    background: #8e44ad;
    color: white;
}

.role-badge.team-lead {
    background: #e67e22;
    color: white;
}

.role-badge.senior-engineer {
    background: #2980b9;
    color: white;
}

.role-badge.engineer {
    background: #27ae60;
    color: white;
}

.engineer-location, .engineer-workload {
    color: #666;
}

.engineer-actions {
    display: flex;
    gap: 5px;
}

.btn-small {
    font-size: 10px;
    padding: 4px 8px;
    border: 1px solid #ccc;
    background: #fff;
    border-radius: 2px;
    cursor: pointer;
    flex: 1;
}

.btn-small:hover {
    background: #f5f5f5;
}

.engineers-footer {
    margin-top: 15px;
    text-align: center;
    color: #666;
}

.no-data {
    text-align: center;
    padding: 20px;
    color: #666;
}

@media (max-width: 768px) {
    .engineers-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function viewEngineer(engineerId) {
    alert('View engineer profile: ID ' + engineerId + '\n(Feature would open engineer details)');
}

function assignCase(engineerId) {
    alert('Assign case to engineer: ID ' + engineerId + '\n(Feature would open case assignment dialog)');
}

function sendMessage(engineerId) {
    alert('Send message to engineer: ID ' + engineerId + '\n(Feature would open messaging interface)');
}

function refreshEngineers() {
    location.reload();
}
</script>
