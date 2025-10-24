<?php
// Modern header module for TICO application

use Tico\Services\AuthService;
use Tico\Config\Config;

$authService = new AuthService();
$config = Config::getInstance();
$currentUser = $authService->getCurrentUser();
?>

<div id="header">
    <div id="headerImage">
        <img src="../assets/media/images/HPR_White_RGB_150_SM.png" 
             alt="<?= htmlspecialchars($config->get('app.name')) ?>" 
             title="<?= htmlspecialchars($config->get('app.name')) ?>">
    </div>
    <div id="headerTitleH1">
        <h1><?= htmlspecialchars($config->get('app.name')) ?></h1>
    </div>
    <div id="rightInfo">
        <?php if ($currentUser): ?>
            <div id="userLogin">
                <i class="icon-user"></i>
                <?= htmlspecialchars($currentUser['nameToDisplay'] ?? $currentUser['usrName']) ?>
            </div>
            <div id="userTeam">
                Team: <?= htmlspecialchars($currentUser['teamID'] ?? 'N/A') ?>
            </div>
            <?php if (!empty($currentUser['timeZone'])): ?>
                <div id="userTimezone">
                    <i class="icon-time"></i>
                    <?= date('H:i T') ?> (<?= htmlspecialchars($currentUser['timeZone']) ?>)
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
            <div id="QMLabel">QM on Duty:</div>                        
        </div>
    </div>
</div><!-- header -->     
