<?php session_start(); ?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>TICO - Change your password</title>
        <link href="../assets/css/popups.css" rel="stylesheet" type="text/css" media="screen">        
        <script src="../assets/js/SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
        <script src="../assets/js/SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
        <link href="../assets/js/SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css">
        <link href="../assets/js/SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css">
        <?php include_once("functions.php"); ?>
        <?php $profileDetails = getProfileValues(callSessionName()); ?>
    </head>
    <body>
        <form name="form1" method="post" action="passUpCheck.php">
            <span id="sprypassword1">
                <label for="Password_txt">  Password</label>
                <input type="password" name="Password_txt" id="Password_txt" tabindex="1">
                <span class="passwordRequiredMsg">A value is required.</span><span class="passwordMinCharsMsg">Minimum number of characters not met.</span><span class="passwordMaxCharsMsg">Exceeded maximum number of characters.</span><span class="passwordInvalidStrengthMsg">The password doesn't meet the specified strength.</span></span>
            <p><span id="spryconfirm1">
                    <label for="passConfirm_txt">Confirm</label>
                    <input type="password" name="passConfirm_txt" id="passConfirm_txt" tabindex="2">
                    <span class="confirmRequiredMsg">A value is required.</span><span class="confirmInvalidMsg">The values don't match.</span></span></p>
            <p>
                <input type="submit" name="passChang_btn" id="passChang_btn" value="Change my password" tabindex="3">
            </p>
        </form>
        <script type="text/javascript">
            var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1", {minChars:4, maxChars:16, validateOn:["blur"], minAlphaChars:1, minNumbers:1, minUpperAlphaChars:1});
            var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "Password_txt", {validateOn:["blur"]});
        </script>
    </body>
</html>