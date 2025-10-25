<?php
if(!isset($_SESSION)){ 
session_start(); 
} 
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");    
}
include_once("functions.php");
?>
<body> 
    <?php
    $profileDetails = getProfileValues(callSessionName());
    echo "<h1>Schedule</h1>";
    echo "<form name=\"updateScheForm\"  id=\"updateScheForm\" action=\"include/updateSchedule.php\" method=\"post\">
            	<div id=\"titulos\">
                    <div id=\"roleDescription\"><h2>" . getRoleDescription($_GET['schtype']) . "</h2></div>
                    <div id=\"in\"><h3>In</h3></div>
                    <div id=\"lunchIn\"><h3>Lunch out</h3></div>
                    <div id=\"lunchOut\"><h3>Lunch in</h3></div>
                    <div id=\"out\"><h3>Out</h3></div>
                </div>
                <input name=\"roleIdValue\" type=\"hidden\" value=\"" . $_GET['schtype'] . "\" />				 ";
    echo $sched = implode(getScheduleForUser($profileDetails[0], $_GET['schtype'])); /// Cambiar a usar varios roles // 
    echo" <div id=\"schedSaveButon\" class=\"genericPrimaryButtonSlim\" onClick=\"javascript:document.updateScheForm.submit()\" >
            <a href=\"include/updateSchedule.php\" title=\"Save Schedule\" onclick=\"return false\">Save</a>
          </div><!-- schedSaveButon -->
        </form>"
    ?>