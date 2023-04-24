<?php
//Login MOdified by ehu@hp  - 26-April-2013
//I move this login.php from the zamorafr folder to the root, and update the links, alos copied the header.php in the include folder.

// Added by franklin@hp.com - 26-March-2013
// Move all includes, conf files, connections, sessions, etc to include/header.php
require_once($_SERVER['DOCUMENT_ROOT'] . '/include/header.php');

// Added by franklin@hp.com
// Action items.  This is to validate the user.
$error = "";

if (isset($_GET['action']) && $_GET['action'] == 'login') {
    $error = login($_POST['username'], $_POST['passwordTB']);
}

// Added by franklin@hp.com - 28/March/2013
// Redirect to index.php if session is active, as there is no need to login again if you're already logged in, unless action=logout.
if (isset($_SESSION['appUser']) && $_SESSION['appUser'] != "" && $_GET['action'] != 'logout') {
    header('Location:/zamorafr/index1.php');
}

// Added by franklin@hp.com - 28/March/2013
// Logout user.
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    unset($_SESSION['appUser']);
    $error = 'Logout Successful.';
}
?>

<!DOCTYPE HTML>
<html>
    <head>
        <?php include("modules/metaHeader.php"); ?>
        <title><?php echo SITE_TITLE; ?></title>
        <link href="assets/css/header.css" rel="stylesheet" />
        <link href="assets/css/login.css" rel="stylesheet" type="text/css" media="screen" />
        <link rel="shortcut icon" type="image/x-icon" href="assets/media/images/favicon.ico">        
    </head>
    <body>
        <div id="header">
            <div id="headerImage"><img src="assets/media/images/HPR_White_RGB_150_SM.png" tittle="TICO"></div>
            <div id="headerTitleH1"><h1><?php echo SITE_TITLE; ?></h1></div>
        </div><!-- header -->   
        <div id="principal">
            <div id="mainContentLogin">   
                <div id="MainLoginBox">
                    <div id="LoginForm">
                        <?php
                        // Added by franklin@hp.com
                        // There is no need for this next line.  We can send the login data to this same page. Replacing.
                        //<form action="include/loginCheck.php" method="post" name="loginForm" id="loginForm" autofocus="autofocus">            
                        ?> 
                        <form action="login.php?action=login" method="post" name="loginForm" id="loginForm" autofocus="autofocus">
                            <div id="LoginIconImage"></div>                            
                            <div id="LoginUserNameContainer">
                                <div id="LoginUserNameLabel">Username:</div>
                                <div id="LoginUserNameTextbox">
                                    <input name="username" type="text" id="username" tabindex="1" size="12" maxlength="15" autofocus />
                                </div>
                            </div><!--LoginUserNameContainer-->
                            <div id="LoginPasswordContainer">
                                <div id="LoginPasswordLabel">Password:</div>
                                <div id="LoginPasswordTextbox">
                                    <input name="passwordTB" type="password" id="passwordTB" tabindex="2"  size="12" maxlength="18" />
                                </div>
                            </div>
<?php
// Added by franklin@hp.com
// This is not needed anymore given that the team will be read from the team table on the database.
//<!--LoginPasswordConainer-->
//<div id="LoginTeamSelector">
//    <div id="LoginTeamSelectorLabel">Team:</div>
//    <div id="LoginTeamSelectorCombobox">
//        <?php include("include/getTeamList.php"); ? >
//    </div>
//</div><!--LoginTeamSelector-->
?>
                            
                            <div id="LoginSubmitButton" class="genericPrimaryButtonSlim">
                                <a href="javascript:loginValidation();" title="Login" tabindex="4">Login</a>
                            </div><!--LoginSubmitButton-->
                            <div id="LoginErrorMessageLabel">
                                <label id="errorlbl"><?php echo $error; ?></label>
                            </div>
                        </form>
                    </div><!-- LoginForm -->        
                </div><!-- MainLoginBox -->    	  	
            </div><!-- mainContentLogin-->
        </div><!-- principal -->
        <script src="assets/js/jquery-ui-1.9.2/js/jquery-1.9.0.js" type="text/javascript"></script>
        <script src="assets/js/jqueryLogin.js" type="text/javascript"></script>
    </body>        
</html>