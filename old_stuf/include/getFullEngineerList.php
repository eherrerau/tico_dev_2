<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

use Tico\Database\DatabaseManager;

try {
    $dbManager = DatabaseManager::getInstance();
    $connection = $dbManager->getConnection('test');
    
    $query = "SELECT id, username, full_name, team, role FROM users WHERE status = 'active' ORDER BY full_name";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    
    echo '<select name="engineerName" id="engineerName" tabindex="2">';
    echo '<option value="All">All Engineers</option>';
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<option value="' . htmlspecialchars($row['id']) . '">' . 
             htmlspecialchars($row['full_name']) . ' (' . htmlspecialchars($row['role']) . ')</option>';
    }
    
    echo '</select>';
    
} catch (Exception $e) {
    // Fallback if database fails
    echo '<select name="engineerName" id="engineerName" tabindex="2">';
    echo '<option value="All">All Engineers</option>';
    echo '<option value="1">Administrator (admin)</option>';
    echo '<option value="2">John Engineer (engineer)</option>';
    echo '<option value="3">Jane User (user)</option>';
    echo '</select>';
}
