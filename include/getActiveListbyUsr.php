<?php
if(!isset($_SESSION)){ 
session_start(); 
} 
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");
}
require_once("functions.php");
$usrname = $_GET["user"];
$userDetails = getProfileValues($usrname);
echo implode("<br>", getAllRolesByUsr($userDetails[0]));
?>