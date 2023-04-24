<?php

if(!isset($_SESSION)){ 
session_start(); 
} 

if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");
}
$excepId = $_POST['excId'];
require_once("connection.php");
$conn = connectToDB();
$query = "delete from ScheduleExceptions where schedExecId=" . $excepId;

$params = array(5);
$getNextSchedExcep = sqlsrv_query($conn, $query, $params);
if ($getNextSchedExcep === false) {
    //die( FormatErrors( sqlsrv_errors() ) ); 
    $result = "couldn't delete the Schedule Exception";
} else {
    echo "Exception Deleted";
}
header('Location:../profile.php');
?>		