<?php

if(!isset($_SESSION)){ 
session_start(); 
} 
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");
    //header('Location:../login.php');
}
require_once("connection.php"); //including the connection functions
$errors = null;
include_once("functions.php");
$QMDetails = getProfileValues(callSessionName());
//----------Finding QM--------------
$conndb = connectToDB(); //Connection stablished
$sqlquery = "exec uspQmonitor";
$params = array(5);
$getQM = sqlsrv_query($conndb, $sqlquery, $params);
if ($getQM === false) {
    die(FormatErrors(sqlsrv_errors()));
}
if ($rowqm = sqlsrv_fetch_array($getQM)) {
    $qmonitor = $rowqm['usrId'];
}
//------------------------------------------
$conn = connectToDB(); //Connection stablished
$query = "uspCheckIfDeleted " . $_POST["casetxt"];
$params = array(5);
$getUsers = sqlsrv_query($conn, $query, $params);
if ($getUsers === false) {
    die(FormatErrors(sqlsrv_errors()));
}
if ($row = sqlsrv_fetch_array($getUsers)) {
//-----------------------------------------------------------
    //$qry =	"EXEC uspDeleteCase " . $_POST["casetxt"] . ", " . $_POST["qmontor"] . ", " . $_POST["teamid"];
    $qry = "EXEC uspDeleteCase " . $_POST["casetxt"] . ", " . $QMDetails[0] . ", 1"; //values case number, qmonitor, teamId
    $params = array(5);
    $getUsers = sqlsrv_query($conn, $qry, $params);
    if ($getUsers === false) {
        die(FormatErrors(sqlsrv_errors()));
    } else {
        echo "The case has been deleted";



        $queryUsr = "exec uspMailFromCase " . $_POST["casetxt"];
        $getUsr = sqlsrv_query($conn, $queryUsr, $params);
        if ($getUsr === false) {
            die(FormatErrors(sqlsrv_errors()));
        } else {
            while ($rowUsr = sqlsrv_fetch_array($getUsr)) {
                $mail = $rowUsr['usrMail'];

                $message = "The case " . $_POST["casetxt"] . " has been deleted";
                mail($mail, 'Your case has been deleted', $message, null);
            }
        }
    }
//-----------------------------------------------------------
} else {
    echo "The case does not exists or has been deleted";
}
closeDBConnetion();
?>