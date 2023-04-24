<?php session_start(); ?>
<!DOCTYPE HTML>
<html>
    <head>
        <?php include("modules/metaHeader.php"); ?>
        <title>TICO-Tickets Control Center</title>
        <?php include("modules/cssload.php"); ?>
        <link href="assets/css/profile.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/addSchedExceptionBox.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/personalInfo.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/scheduleExcepListForUser.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/scheduleBox.css" rel="stylesheet" type="text/css" />
        <?php
        include("include/functions.php");
        if (!isset($_SESSION['DBtoUse'])) {
            header('Location:../login.php');
        }
        ?>
        <?php
        $profileDetails = getProfileValues(callSessionName());
        $product = getAllProductsOnMyProfile();
        ?>
    </head>
    <body onload="onLoadProfile()">
        <div id="principal">
            <?php
            include("modules/header.php");
            include("modules/rightMenu.php");
            ?>
            <div id="mainContent">
                <div id="centerColumnMyProfile">
                    <div id="leftColumnMyProfile">
                        <?php include_once './modules/personalInfo.php'; ?>
                        <?php include_once './modules/schedulesProfile.php'; ?>
                    </div>
                    <div id="rightColumnMyProfile">                    
                        <?php include_once './modules/exceptionsBoxes.php'; ?>
                    </div>
                </div>
            </div><!-- mainContent -->               
        </div><!-- principal -->
<?php include("modules/jsload.php") ?>        
        <script src="assets/js/script.js" type="text/javascript"></script>
        <script src="assets/js/jqueryProfile.js" type="text/javascript"></script>           
    </body><!-- ------------Body------------ -->
</html>