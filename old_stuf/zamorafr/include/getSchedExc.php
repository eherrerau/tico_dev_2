<?php
if(!isset($_SESSION)){ 
session_start(); 
} 
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");
}
require_once("connection.php");
require_once("functions.php");
$userDetails = getProfileValues(callSessionName());
$schedExc = array();
$schedExc = getNextSchedExcep($userDetails[0]);
echo implode(", ", $schedExc);
?>