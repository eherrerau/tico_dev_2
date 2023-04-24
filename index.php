<?php
session_start();
// *******************************
// Added by dotb@hp.com
// Date: 14-Feb-2013
//
// Includes library for TZ support
//

require_once 'include/includes.php';

// *******************************
?>
<!DOCTYPE HTML>
<html>
    <head>
        <?php include("modules/metaHeader.php"); ?>
        <title>TICO-Tickets Control Center</title>
        <?php include("modules/cssload.php"); ?>
        <link href="assets/css/index.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/casesAssignForm.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/graphBox.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/engineersBoxes.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/newsBox.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <?php
        // Revome it by ehu@hp.com
//        if (!isset($_SESSION['DBtoUse'])) {
//            header('Location:../login.php');
//        }
        /*
         * Edit by ehu@hp.com
         *  set to hardcode to the the name of the DB, since we are attemping to use only one, and not multiple.
         */
        //$DBteam = $_SESSION['DBtoUse'];
        $DBteam = "TICO_DB";
        include_once("include/functions.php");
        $userDetails = getProfileValues(callSessionName());
        ?>
        <div id="principal">
            <?php
            include("modules/header.php");
            include("modules/rightMenu.php");
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
                        <?php include_once './include/getAvailable.php'; ?>
                    </div>
                </div>
                </div>
                <div id="centerColumn">
                    <?php
                    if (isProfile(3) or isProfile(1) or isProfile(2)) {//if is a QM, admin or manager
                        include("modules/casesAssignForm.php");
                    }?>
                    <div id="schedulesBox">
                        <?php 
                            include 'graphs/myStatisticsGraph.php'; 
                        if (isProfile(5) or isProfile(4) or isProfile(3)) {// if is an engineer, expert, QM or admin
                            //include 'graphs/myStatisticsGraph.php';
                        }
                        ?>
                        <div id="grafico"></div>
                    </div>
                </div>
                <div id="rightColumn">
                    <?php include_once './modules/newsBox.php'; ?>
                </div>                
            </div><!-- mainContent -->                    
        </div><!-- principal -->                    
        <div id="detail"></div>
    </body>

    <?php include("modules/jsload.php") ?>
    <script src="assets/js/getcases.js" type="text/javascript"></script>
    <script src="assets/js/graphs.js" type="text/javascript"></script>
    <script src="assets/js/jqueryIndex.js" type="text/jscript"></script>
    <script src="assets/js/script.js" type="text/jscript"></script>
    <script src="assets/js/boxesJS.js" type="text/jscript"></script>
    <script languaje="javascript"> getexpert();</script>
</html>