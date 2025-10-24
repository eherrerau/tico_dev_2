<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

use Tico\Services\AuthService;
use Tico\Config\Config;

$authService = new AuthService();
$config = Config::getInstance();

// Check authentication
$authService->requireAuthentication();

// Check session timeout
if (!$authService->checkSessionTimeout()) {
    header('Location: /login.php?timeout=1');
    exit;
}

$currentUser = $authService->getCurrentUser();
$csrfToken = $authService->getCsrfToken();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars((string) $config->get('app.name')) ?></title>
    
    <!-- Security meta tags -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    
    <!-- Stylesheets -->
    <link href="assets/css/globalStyle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/index.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/casesAssignForm.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/graphBox.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/engineersBoxes.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/newsBox.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/header.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/dashboard-modern.css" rel="stylesheet" type="text/css" />
    
    <link rel="shortcut icon" type="image/x-icon" href="assets/media/images/favicon.ico">
</head>
<body>
    <div id="principal">
        <?php include __DIR__ . '/../modules/header.php'; ?>
        
        <div id="mainContent">
            <!-- Top Dashboard Stats -->
            <div id="topStats">
                <?php include __DIR__ . '/../modules/dashboardStats.php'; ?>
            </div>
            
            <div id="leftColumn">
                <!-- Engineers Grid -->
                <div id="engineersSection">
                    <?php include __DIR__ . '/../modules/engineersGrid.php'; ?>
                </div>
                
                <div id="boxesMainSection">
                    <div id="leyendDescriptionLink">
                        <i class="icon-question-sign" style="color: #D7410B"></i>Legend
                    </div>
                    <div id="leyendDescription">
                        <ul>
                            <li><i class="icon-circle" style="color:#B7CA34"></i>Foundation Case</li>
                            <li><i class="icon-asterisk" style="color:#B7CA34"></i>Premier Case</li>
                            <li><i class="icon-circle" style="color:#D7410B"></i>Critical Case</li>
                            <li><i class="icon-asterisk" style="color:#D7410B"></i>Premier Critical Case</li>
                        </ul>
                    </div>
                    
                    <!-- Available Engineers Box (Legacy) -->
                    <div id="availableBox" style="display: none;">
                        <?php include __DIR__ . '/../modules/engineersBox.php'; ?>
                    </div>
                    
                    <!-- Not Available Engineers Box (Legacy) -->
                    <div id="notAvailableBox" style="display: none;">
                        <?php include __DIR__ . '/../modules/notAvailableBox.php'; ?>
                    </div>
                </div>
            </div>
            
            <div id="centerColumn">
                <!-- Recent Cases List -->
                <div id="casesListBox">
                    <?php include __DIR__ . '/../modules/casesList.php'; ?>
                </div>
                
                <!-- Cases Assignment Form -->
                <div id="casesAssignFormBox">
                    <?php include __DIR__ . '/../modules/casesAssignForm.php'; ?>
                </div>
                
                <!-- News and Updates Box -->
                <div id="newsBox">
                    <?php include __DIR__ . '/../modules/newsBoxModern.php'; ?>
                </div>
                
                <!-- Schedule Exceptions Box -->
                <div id="exceptionsBox">
                    <?php include __DIR__ . '/../modules/exceptionsBoxes.php'; ?>
                </div>
            </div>
            
            <div id="rightColumn">
                <!-- Main Menu -->
                <?php include __DIR__ . '/../modules/rightMenu.php'; ?>
                
                <!-- Personal Statistics -->
                <div id="statisticsBox">
                    <?php include __DIR__ . '/../modules/personalStatistics.php'; ?>
                </div>
                
                <!-- Today's Cases -->
                <div id="todayCasesBox">
                    <?php include __DIR__ . '/../include/todayCasesList.php'; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="assets/js/jquery-1.9.1.min.js"></script>
    <script src="assets/js/script.js"></script>
    
    <!-- CSRF Token for AJAX requests -->
    <script>
        window.csrfToken = '<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>';
        
        // Add CSRF token to all AJAX requests
        $(document).ajaxSend(function(event, xhr, settings) {
            if (settings.type === 'POST') {
                if (settings.data) {
                    settings.data += '&csrf_token=' + encodeURIComponent(window.csrfToken);
                } else {
                    settings.data = 'csrf_token=' + encodeURIComponent(window.csrfToken);
                }
            }
        });
        
        // Session timeout warning
        let sessionTimeout = <?= $config->get('session.lifetime') * 60 * 1000 ?>;
        let warningTime = sessionTimeout - (5 * 60 * 1000); // 5 minutes before timeout
        
        setTimeout(function() {
            if (confirm('Your session will expire in 5 minutes. Do you want to extend it?')) {
                // Make a request to extend session
                $.post('extend_session.php', {csrf_token: window.csrfToken});
            }
        }, warningTime);
    </script>
</body>
</html>
