<?php

if(!isset($_SESSION)){ 
session_start(); 
} 
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");
    //header('Location:../login.php');
}
require_once("../include/connection.php"); //including the connection functions

$conndb = connectToDB(); //Connection stablished
//----------------------------------
//--------Searching engineers-------------
$query = "SELECT usrId, nameToDisplay FROM UserDetails WHERE usrId in (SELECT usrId FROM RoleByUsr WHERE roleId IN(SELECT roleId FROM Roles Where receivesCases=1)) and active='True' and premier='True' Order by nameToDisplay"; //Finding all engineers
$params = array(5);
$result = sqlsrv_query($conndb, $query, $params);
if ($result === false) {
    die(FormatErrors(sqlsrv_errors()));
}
echo "<h2><div id=tittle>Total Premier Cases per Engineer/This Month</div></h2>";
echo "<div id=main>";
$x = 0;
$serie = "Serie" . $x;
While ($row = sqlsrv_fetch_array($result)) { //For each Engineer
    $x = $x + 1;
    //$row['usrId']

    $sqlquery = "exec uspPremierCases " . $row['usrId']; //This returns all the assigned cases in the month
    $params = array(5);
    $getCases = sqlsrv_query($conndb, $sqlquery, $params);
    if ($getCases === false) {
        die(FormatErrors(sqlsrv_errors()));
    }
    if ($rowCases = sqlsrv_fetch_array($getCases)) {
        //$CaseId = $rowCases['caseId'];
        $tamano = $rowCases['totalcases'] * 5;
        echo "<div id=row><div id=\"engineer\"> " . $row['nameToDisplay'] . "</div><div id=\"graphline\" style=\"width:" . $tamano . "px; \"></div><div id=\"tamano\"  style=\"position:relative; \" >" . $rowCases['totalcases'] . "</div></div>";
        //echo "the engineer ". $row['nameToDisplay']. " has "; 
        //echo "total cases ". $rowCases['total']. "  ";
    }

    $serie = "Serie" . $x;
}//While
closeDBConnetion();
?>
