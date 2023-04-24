<?php
// Added by franklin@hp.com - 26/March/2013
// Move all includes, conf files, connections, sessions, etc to include/header.php
require_once($_SERVER['DOCUMENT_ROOT'].'/zamorafr/include/header.php');

// Added by franklin@hp.com - 28/March/2013
// Create the user object and load it with current user's data.
$user = new user();
$user->__construct();
$user->load($_SESSION['appUser']);

// Load all roles for this user
$roles = $user->getRoles();

// Load user's schedule
$i = 0;
foreach ($roles as $role) {
	if ($role != 1 && $role != 2) {
	# Validates roles 1 and 2.  These roles are admin and manager and they dont have schedules.
		$userSchedule[$i] = new scheduler();
		$userSchedule[$i]->__construct();
		$userSchedule[$i]->loadUserSchedule($user->getUsrId(), $role);
		$i++;
	}
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <?php include(APP_PATH."/modules/metaHeader.php"); ?>
        <title><?php echo SITE_TITLE;?></title>
        <?php include("modules/cssload.php"); ?>
        <link href="assets/css/index.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/casesAssignForm.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/graphBox.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/engineersBoxes.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/newsBox.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <?php
        // Added by franklin@hp.com - 28/March/2013
        // No need for this next line, this is read from the Team table in the DB.
        //$DBteam = $_SESSION['DBtoUse'];
        
        // Added by franklin@hp.com - 28/March/2013
        // No need for this next line, already added in header.php
        //include_once("include/functions.php");
        
        // Added by franklin@hp.com - 28/March/2013
        // No need for this next line, data is loaded via user object.
        //$userDetails = getProfileValues(callSessionName());
        ?>
        <div id="principal">
            <?php
            include(APP_PATH."/modules/header.php");
            include(APP_PATH."/modules/rightMenu.php");
            ?>
            <div id="mainContent">
                <div id="leftColumn">
                    <div id="boxesMainSection">
                        <div id="leyendDescriptionLink"><i class="icon-question-sign" style="color: #D7410B"></i>Legend</div>
                        <div id="leyendDescription">
                            <ul>
                                <li><i class="icon-circle" style="color:#B7CA34"></i>Foundation Case</li>
                                <li><i class="icon-asterisk" style="color:#B7CA34"></i>Premier Case</li>
                                <li><i class="icon-circle" style="color:#FF0000"></i>Critical Foundation Case</li>
                                <li><i class="icon-asterisk" style="color:#FF0000"></i>Critical Premier Case</li>
                                <li><i class="icon-phone-sign" style="color:#B7CA34"></i>Callback Foundation</li>
                                <li><i class="icon-phone" style="color:#B7CA34"></i>Callback Premier</li>
                                <li><i class="icon-phone-sign" style="color:#FF0000"></i>Critical Callback Foundation</li>
                                <li><i class="icon-phone" style="color:#FF0000"></i>Critical Callback Premier</li>
                                <li><i class="icon-circle-arrow-up" style="color:#B7CA34"></i>Elevation Foundation</li>
                                <li><i class="icon-upload" style="color:#B7CA34"></i>Elevation Premier</li>
                                <li><i class="icon-circle-arrow-up" style="color:#FF0000"></i>Critical Elevation Foundation</li>
                                <li><i class="icon-upload" style="color:#FF0000"></i>Critical Elevation Premier</li>
                                <li class="divider"></li>
                                <li><i class="icon-star"></i>Premier Engineer</li>
                                <li><i class="icon-home"></i>Status: Working from home</li>
                                <li><i class="icon-thumbs-up"></i>Status: On Permission</li>
                                <li><i class="icon-plane"></i>Status: Vacations</li>
                                <li><i class="icon-book"></i>Status: Training</li>
                                <li><i class="icon-medkit"></i>Status: Sick</li>
                                <li><i class="icon-minus-sign"></i>Status: OOQ</li>
                                <li><i class="icon-desktop"></i>Status: Queue Monitor</li>
                                <li><i class="icon-suitcase"></i>Status: Holiday</li>
                                <li><i class="icon-gift"></i>Status: Birthday</li>
                            </ul>
                        </div>                        
                    <div id="engineerListBox">
                    <?php  include_once APP_PATH.'/modules/getAvailableTEMP.php'; ?>
                    </div>
                </div>
                </div>
                <div id="centerColumn">
                    <?php
                    // Changed by franklin@hp.com - 28/March/2013
                    if (in_array(1, $roles) or in_array(2, $roles) or in_array(3, $roles)) {//if is a QM, admin or manager
                    //    include("modules/casesFormMenu.php");
                    }?>
                    <div id="schedulesBox">
                        <?php 
                        // Changed by franklin@hp.com - 28/March/2013
                        //    include 'graphs/myStatisticsGraph.php'; 
                        if (in_array(3, $roles) or in_array(4, $roles) or in_array(5, $roles)) {// if is an engineer, expert, QM or admin
                            //include 'graphs/myStatisticsGraph.php';
                        }
                        ?>
                        <div id="grafico"></div>
                    </div>
                </div>
                <div id="rightColumn">
                    <?php 
                    // Changed by franklin@hp.com - 28/March/2013
                    include_once APP_PATH.'/modules/newsBox.php'; ?>
                </div>                
            </div><!-- mainContent -->                    
        </div><!-- principal -->                    
        <div id="detail"></div>
    </body>

    <?php 
    // Changed by franklin@hp.com - 28/March/2013
    include(APP_PATH."/modules/jsload.php") ?>
    <script src="assets/js/getcases.js" type="text/javascript"></script>
    <script src="assets/js/graphs.js" type="text/javascript"></script>
    <script src="assets/js/jqueryIndex.js" type="text/jscript"></script>
    <script src="assets/js/script.js" type="text/jscript"></script>
    <script src="assets/js/boxesJS.js" type="text/jscript"></script>
    <script languaje="javascript"> getexpert();</script>
</html>