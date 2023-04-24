    <?php
    if(!isset($_SESSION)){ 
    session_start(); 
    } 
    if (!isset($_SESSION['DBtoUse'])) {
        header("Cache-Control: no-cache");
        //header('Location:../login.php');
    }
    include_once("functions.php");
    ?>

    <?php
    $profileDetails = getProfileValues(callSessionName());
    ?>
    <?php
    updateMyProfile($profileDetails[0]);
    header('Location:../profile.php');
    ?> 