<div id="mainMenu">
    <ul>
        <li><a href="index.php">Dashboard</a></li>
        <?php
        if (isProfile(1)) {
            echo    "<li><a href=\"administration.php\">Admin</a></li>";
        }
        if (isProfile(3) or isProfile(1) or isProfile(2)) {
            echo    "<li><a href=\"#\">Reports</a></li>";            
        }
        ?>        
        <li><a href="profile.php">My Profile</a></li>
        <li><a href="Schedule.php">Schedule</a></li>
        <li><a href="http://ent51.sharepoint.hp.com/teams/tico/Lists/ER%20and%20Issues%20Tracking/AllItems.aspx" target="_new">ER's</a></li>
        <li><a href="login.php?action=logout">Sign Out</a></li>  
    </ul>
</div><!-- mainMenu -->

