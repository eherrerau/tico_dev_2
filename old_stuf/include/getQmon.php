<?php

require_once("connection.php");
if(!isset($_SESSION)){ 
session_start(); 
} 
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");
    //header('Location:../login.php');
}
$errors = null;
$conn = connectToDB(); //Connection stablished
$query = "exec uspQmonitor";
$params = array(5);
$getUsers = sqlsrv_query($conn, $query, $params);
if ($getUsers === false) {
    die(FormatErrors(sqlsrv_errors()));
}
if ($row = sqlsrv_fetch_array($getUsers)) {
    echo $row['nameToDisplay'];
} else {
    echo "Not Assigned";
}
closeDBConnetion();
?>
