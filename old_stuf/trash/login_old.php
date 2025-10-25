<!DOCTYPE HTML>
<html>
    <head>
        <?php include("modules/metaHeader.php"); ?>
        <title>Login Page-TICO-Tickets Control Center</title>
        <link href="assets/css/header.css" rel="stylesheet" />
        <link href="assets/css/login.css" rel="stylesheet" type="text/css" media="screen" />
        <link rel="shortcut icon" type="image/x-icon" href="assets/media/images/favicon.ico">        
    </head>
    <body>
        <div id="header">
            <div id="headerImage"><img src="assets/media/images/HPR_White_RGB_150_SM.png" tittle="TICO"></div>
            <div id="headerTitleH1"><h1>TICO-Tickets Control Center</h1></div>
        </div><!-- header -->   
        <div id="principal">
            <div id="mainContentLogin">   
                <div id="MainLoginBox">
                    <div id="LoginForm">
                        <form action="include/loginCheck.php" method="post" name="loginForm" id="loginForm" autofocus="autofocus">             
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
                            </div><!--LoginPasswordConainer-->
                            <div id="LoginTeamSelector">
                                <div id="LoginTeamSelectorLabel">Team:</div>
                                <div id="LoginTeamSelectorCombobox">
                                    <?php include("include/getTeamList.php"); ?>
                                </div>
                            </div><!--LoginTeamSelector-->
                            <div id="LoginSubmitButton" class="genericPrimaryButtonSlim">
                                <a href="javascript:loginValidation();" title="Login" tabindex="4">Login</a>
                            </div><!--LoginSubmitButton-->
                            <div id="LoginErrorMessageLabel">
                                <label id="errorlbl"></label>
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