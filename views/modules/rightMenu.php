<?php
// Modern right menu for TICO application

use Tico\Services\AuthService;

$authService = new AuthService();
?>

<div id="mainMenu">
    <ul>
        <li><a href="index.php"><i class="icon-dashboard"></i> Dashboard</a></li>
        
        <?php if ($authService->hasRole(1)): // Admin role ?>
            <li><a href="administration.php"><i class="icon-cogs"></i> Admin</a></li>
        <?php endif; ?>
        
        <?php if ($authService->hasRole(3) || $authService->hasRole(1) || $authService->hasRole(2)): // Reports access ?>
            <li><a href="#"><i class="icon-bar-chart"></i> Reports</a></li>
        <?php endif; ?>
        
        <li><a href="profile.php"><i class="icon-user"></i> My Profile</a></li>
        <li><a href="schedule.php"><i class="icon-calendar"></i> Schedule</a></li>
        <li><a href="#" onclick="showERsModal()"><i class="icon-external-link"></i> ER's</a></li>
        <li><a href="login.php?action=logout"><i class="icon-sign-out"></i> Sign Out</a></li>
    </ul>
</div>

<script>
function showERsModal() {
    if (confirm('This will open the Enhancement Requests portal in a new window. Continue?')) {
        window.open('http://ent51.sharepoint.hp.com/teams/tico/Lists/ER%20and%20Issues%20Tracking/AllItems.aspx', '_blank');
    }
}
</script>

<style>
#mainMenu {
    background: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 10px;
    margin: 10px 0;
}

#mainMenu ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

#mainMenu li {
    margin: 5px 0;
}

#mainMenu a {
    display: block;
    padding: 8px 12px;
    text-decoration: none;
    color: #333;
    border-radius: 3px;
    transition: background-color 0.2s;
}

#mainMenu a:hover {
    background-color: #0096D6;
    color: white;
}

#mainMenu i {
    margin-right: 8px;
    width: 16px;
    text-align: center;
}
</style>

