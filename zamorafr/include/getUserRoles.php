<?php

if(!isset($_SESSION)){ 
session_start(); 
} 
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");
    //header('Location:../login.php');
}
require_once("connection.php");
$conn = connectToDB();
$query = "EXEC uspRolesList";
$params = array(5);
$getUserStaturs = sqlsrv_query($conn, $query, $params);
if ($getUserStaturs === false) {
    die(FormatErrors(sqlsrv_errors()));
}
echo"<select id=\"displayStatus\" name=\"displayStatus\" title=\"Status\">";
while ($row = sqlsrv_fetch_array($getUserStaturs)) {
    echo "<option value=" . $row["roleId"] . ">" . $row["roleDesc"] . "</option>";
}
echo "</select>";
/* Close the connection. */
closeDBConnetion();
?>
