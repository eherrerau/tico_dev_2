<?php session_start(); ?>
<!DOCTYPE HTML>
<html>
    <head>
        <?php include("modules/metaHeader.php"); ?>
        <title>TICO-Maintenance</title>
        <?php include("modules/cssload.php"); ?>
        <link href="assets/css/admin.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <?php      
        if (!isset($_SESSION['DBtoUse'])) {
            header('Location:../login.php');
        }
        include_once("include/functions.php");
        require_once("include/connection.php"); //including the connection functions 
        //$userDetails = getProfileValues(callSessionName());
        ?>
        <p>
        <div id="principal">
            <?php include("modules/header.php");
            include("modules/rightMenu.php");
            ?>
            <!-- the Body of the page starts here -->
            <div id="fullPage">
                <div id="tittle"><h2>Maintenance</h2></div>
                <div id="responseMessage"></div>
                <?php include("modules/admin/usr/usersAdmin.php") ?>
                <div id="permission">
                    <div id="subMenu">
                        <div id="opt1">Create</div>
                        <div id="opt2">Modify</div>
                        <div id="opt3">Enable</div>
                    </div>
                    <div id=logoPermission></div>
                    <div id=tittlePermission><h4>Schedule Exceptions Maintenance</h4></div>
                </div><!-- permission -->
                <div id="other">
                    <div id="subMenu">
                        <div id="opt1">Create</div>
                        <div id="opt2">Modify</div>
                        <div id="opt3">Enable</div>
                    </div>
                    <div id=logoOther></div>
                    <div id=tittleOther><h4>Other Maintenance</h4></div>
                </div><!-- other -->    
            </div><!-- fullPage -->
        </div><!-- principal -->
    <?php include_once './modules/jsload.php'; ?>    
    <script src="modules/admin/usr/jsUsr/usrFunctions.js" type="text/javascript"></script>
    <script src="lib/js/script.js" type="text/javascript"></script>
    <script src="assets/js/jqueryAdmin.js" type="text/javascript"></script>
    </body>
</html>