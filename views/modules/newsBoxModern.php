<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/bootstrap.php';

use Tico\Database\DatabaseManager;

try {
    $dbManager = DatabaseManager::getInstance();
    $connection = $dbManager->getConnection('test');
    
    // Get recent news and announcements
    $query = "
        SELECT 
            id,
            title,
            content,
            type,
            priority,
            author,
            created_at,
            expires_at
        FROM news 
        WHERE (expires_at IS NULL OR expires_at > datetime('now'))
        ORDER BY 
            CASE priority 
                WHEN 'Critical' THEN 1 
                WHEN 'High' THEN 2 
                WHEN 'Normal' THEN 3 
                ELSE 4 
            END,
            created_at DESC
        LIMIT 10
    ";
    
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $news = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    $news = [];
}
?>

<div class="news-container">
    <h3><i class="icon-bullhorn"></i> News &amp; Announcements</h3>
    
    <?php if (empty($news)): ?>
        <div class="no-news">
            <p><i class="icon-info-sign"></i> No current announcements.</p>
        </div>
    <?php else: ?>
        <div class="news-list">
            <?php foreach ($news as $item): ?>
                <?php
                $priorityClass = strtolower($item['priority']);
                $typeClass = strtolower($item['type']);
                $isNew = (new DateTime($item['created_at']))->diff(new DateTime())->days < 1;
                ?>
                <div class="news-item <?= $priorityClass ?> <?= $typeClass ?>" data-id="<?= $item['id'] ?>">
                    <div class="news-header">
                        <div class="news-meta">
                            <?php if ($item['type'] === 'Maintenance'): ?>
                                <i class="icon-wrench news-icon"></i>
                            <?php elseif ($item['type'] === 'Alert'): ?>
                                <i class="icon-warning-sign news-icon"></i>
                            <?php elseif ($item['type'] === 'Info'): ?>
                                <i class="icon-info-sign news-icon"></i>
                            <?php else: ?>
                                <i class="icon-bullhorn news-icon"></i>
                            <?php endif; ?>
                            
                            <span class="news-type"><?= htmlspecialchars($item['type']) ?></span>
                            
                            <?php if ($isNew): ?>
                                <span class="new-badge">NEW</span>
                            <?php endif; ?>
                            
                            <?php if ($item['priority'] === 'Critical'): ?>
                                <span class="priority-badge critical">
                                    <i class="icon-exclamation-sign"></i> CRITICAL
                                </span>
                            <?php elseif ($item['priority'] === 'High'): ?>
                                <span class="priority-badge high">
                                    <i class="icon-arrow-up"></i> HIGH
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="news-time">
                            <?php
                            $created = new DateTime($item['created_at']);
                            $now = new DateTime();
                            $diff = $now->diff($created);
                            
                            if ($diff->days > 0) {
                                echo $diff->days . 'd ago';
                            } elseif ($diff->h > 0) {
                                echo $diff->h . 'h ago';
                            } else {
                                echo $diff->i . 'm ago';
                            }
                            ?>
                        </div>
                    </div>
                    
                    <div class="news-content">
                        <h4 class="news-title"><?= htmlspecialchars($item['title']) ?></h4>
                        <p class="news-text"><?= htmlspecialchars($item['content']) ?></p>
                        
                        <?php if (!empty($item['author'])): ?>
                            <div class="news-author">
                                <i class="icon-user"></i> <?= htmlspecialchars($item['author']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($item['expires_at'])): ?>
                            <div class="news-expires">
                                <i class="icon-time"></i> 
                                Expires: <?= (new DateTime($item['expires_at']))->format('M j, Y g:i A') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="news-actions">
                        <button type="button" class="news-action-btn" onclick="markAsRead(<?= $item['id'] ?>)" title="Mark as read">
                            <i class="icon-ok"></i>
                        </button>
                        <button type="button" class="news-action-btn" onclick="shareNews(<?= $item['id'] ?>)" title="Share">
                            <i class="icon-share"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="news-footer">
            <button type="button" class="btn-link" onclick="viewAllNews()">
                <i class="icon-list"></i> View All News
            </button>
            <button type="button" class="btn-link" onclick="refreshNews()">
                <i class="icon-refresh"></i> Refresh
            </button>
        </div>
    <?php endif; ?>
</div>

<style>
.news-container {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 15px;
    margin: 10px 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.news-container h3 {
    margin: 0 0 15px 0;
    color: #333;
    border-bottom: 1px solid #eee;
    padding-bottom: 8px;
}

.news-list {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 5px;
}

.news-item {
    border: 1px solid #e9ecef;
    border-radius: 4px;
    margin-bottom: 10px;
    padding: 12px;
    background: #fefefe;
    transition: all 0.3s ease;
    position: relative;
}

.news-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateY(-1px);
}

.news-item.critical {
    border-left: 4px solid #dc3545;
    background: #fff5f5;
}

.news-item.high {
    border-left: 4px solid #ffc107;
    background: #fffdf5;
}

.news-item.maintenance {
    background: #f8f9fa;
}

.news-item.alert {
    background: #fff3cd;
}

.news-header {
    display: flex;
    justify-content: between;
    align-items: flex-start;
    margin-bottom: 8px;
}

.news-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 1;
}

.news-icon {
    font-size: 14px;
    color: #6c757d;
}

.news-type {
    font-size: 11px;
    font-weight: bold;
    color: #6c757d;
    text-transform: uppercase;
}

.new-badge {
    background: #28a745;
    color: white;
    font-size: 9px;
    padding: 2px 6px;
    border-radius: 10px;
    font-weight: bold;
}

.priority-badge {
    font-size: 9px;
    padding: 2px 6px;
    border-radius: 10px;
    font-weight: bold;
}

.priority-badge.critical {
    background: #dc3545;
    color: white;
}

.priority-badge.high {
    background: #ffc107;
    color: #212529;
}

.news-time {
    font-size: 11px;
    color: #6c757d;
    white-space: nowrap;
}

.news-title {
    margin: 0 0 6px 0;
    font-size: 14px;
    font-weight: 600;
    color: #333;
    line-height: 1.2;
}

.news-text {
    margin: 0 0 8px 0;
    font-size: 12px;
    color: #666;
    line-height: 1.4;
}

.news-author, .news-expires {
    font-size: 10px;
    color: #6c757d;
    margin-bottom: 4px;
}

.news-actions {
    position: absolute;
    top: 8px;
    right: 8px;
    display: flex;
    gap: 4px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.news-item:hover .news-actions {
    opacity: 1;
}

.news-action-btn {
    background: transparent;
    border: 1px solid #dee2e6;
    border-radius: 2px;
    padding: 2px 6px;
    font-size: 10px;
    cursor: pointer;
    color: #6c757d;
}

.news-action-btn:hover {
    background: #f8f9fa;
    color: #333;
}

.news-footer {
    margin-top: 15px;
    text-align: center;
    display: flex;
    justify-content: center;
    gap: 15px;
}

.btn-link {
    background: none;
    border: none;
    color: #007bff;
    font-size: 12px;
    cursor: pointer;
    text-decoration: none;
    padding: 4px 8px;
}

.btn-link:hover {
    text-decoration: underline;
}

.no-news {
    text-align: center;
    padding: 30px 20px;
    color: #6c757d;
}

/* Scrollbar styling */
.news-list::-webkit-scrollbar {
    width: 4px;
}

.news-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 2px;
}

.news-list::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}

.news-list::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Animation for new items */
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.news-item.new-item {
    animation: slideInRight 0.5s ease;
}
</style>

<script>
function markAsRead(newsId) {
    // Find the news item and fade it out
    const newsItem = document.querySelector(`[data-id="${newsId}"]`);
    if (newsItem) {
        newsItem.style.opacity = '0.5';
        newsItem.style.pointerEvents = 'none';
        
        // Here you would typically make an AJAX call to mark as read
        console.log('Marking news item as read:', newsId);
    }
}

function shareNews(newsId) {
    // Simple share functionality (could be enhanced with proper sharing API)
    const newsItem = document.querySelector(`[data-id="${newsId}"]`);
    if (newsItem) {
        const title = newsItem.querySelector('.news-title').textContent;
        const text = newsItem.querySelector('.news-text').textContent;
        
        if (navigator.share) {
            navigator.share({
                title: title,
                text: text,
                url: window.location.href
            });
        } else {
            // Fallback for browsers without Web Share API
            navigator.clipboard.writeText(`${title}: ${text}`).then(() => {
                alert('News copied to clipboard!');
            });
        }
    }
}

function viewAllNews() {
    // Navigate to full news page
    console.log('Navigate to full news page');
    alert('Feature: Navigate to full news page\n(Would open dedicated news management interface)');
}

function refreshNews() {
    // Refresh the news content
    console.log('Refreshing news...');
    location.reload();
}

// Auto-refresh news every 5 minutes
setInterval(function() {
    // In a real application, this would fetch new news via AJAX
    console.log('Auto-refreshing news...');
}, 5 * 60 * 1000);
</script>
