<div id="mainMenu">
    <ul>
        <li><a href="index.php">Dashboard</a></li>
        <?php
        // $roles is populated in /index.php                
        if (in_array(1, $roles)) {
            echo    "<li><a href=\"administration.php\">Admin</a></li>";
        }
        if (in_array(1, $roles) or in_array(2, $roles) or in_array(3, $roles)) {
            echo    "<li><a href=\"#\">Reports</a></li>";            
        }
        ?>        
        <li><a href="profile.php">My Profile</a></li>
        <li><a href="Schedule.php">Schedule</a></li>
        <li><a href="http://ent51.sharepoint.hp.com/teams/tico/Lists/ER%20and%20Issues%20Tracking/AllItems.aspx" target="_new">ER's</a></li>
        <li><a href="signout.php">Sign Out</a></li>  
    </ul>
</div><!-- mainMenu -->

