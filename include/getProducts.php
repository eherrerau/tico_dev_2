<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

use Tico\Database\DatabaseManager;

try {
    $dbManager = DatabaseManager::getInstance();
    $connection = $dbManager->getConnection('test');
    
    $query = "SELECT id, name, description FROM products WHERE active = 1 ORDER BY name";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    
    echo '<select id="productID" name="productID" tabindex="3" onChange="getenglst()">';
    echo '<option value="">-- Select Product --</option>';
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<option value="' . htmlspecialchars($row['id']) . '">' . 
             htmlspecialchars($row['name']) . '</option>';
    }
    
    echo '</select>';
    
} catch (Exception $e) {
    // Fallback if database fails
    echo '<select id="productID" name="productID" tabindex="3">';
    echo '<option value="">-- Products Unavailable --</option>';
    echo '</select>';
}
closeDBConnetion();
?>