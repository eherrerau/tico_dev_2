<?php

if(!isset($_SESSION)){ 
session_start(); 
} 
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");
}
include_once("functions.php");
$profileDetails = getProfileValues(callSessionName());

insertScheduleException($profileDetails[0], $profileDetails[0]);
header('Location:../profile.php');
?>