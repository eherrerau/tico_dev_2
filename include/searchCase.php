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
$conn = connectToDB(); //Connection stablished
$query = "exec uspFindCase " . $_POST["casetxt"];
$params = array(5);
$getUsers = sqlsrv_query($conn, $query, $params);
if ($getUsers === false) {
    die(FormatErrors(sqlsrv_errors()));
}
if ($row = sqlsrv_fetch_array($getUsers)) {
    //echo "<b>".$row['nameToDisplay']."</b>";
    echo "The Case: " . $_POST["casetxt"] . " was assigned to: " . $row['nombre'] . " on " . date_format($row['assignTime'], "M-d-Y");
} else {
    echo "<b>Not Assigned </b>";
}
closeDBConnetion();
?>
