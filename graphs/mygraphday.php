<?php

session_start();
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");
    //header('Location:../login.php');
}
require_once("../include/connection.php"); //including the connection functions
require_once("../include/functions.php");
$conndb = connectToDB(); //Connection stablished
//----------------------------------
//--------Searching engineers-------------
$usrNameQuery = callSessionName();
$query = "SELECT usrId, nameToDisplay FROM UserDetails where usrName='" . $usrNameQuery . "'"; //Finding current engineer
$params = array(5);
$result = sqlsrv_query($conndb, $query, $params);
if ($result === false) {
    die(FormatErrors(sqlsrv_errors()));
}
echo "<h2><div id=tittle>My Total Cases /Today</div></h2>";
echo "<div id=main>";
$x = 1;
$serie = "Serie" . $x;
if ($row = sqlsrv_fetch_array($result)) { //For each Engineer
    //$row['usrId']
    $sqlquery = "exec uspDayCasesByUser " . $row['usrId']; //This returns all the assigned cases for Today
    $params = array(5);
    $getCases = sqlsrv_query($conndb, $sqlquery, $params);
    if ($getCases === false) {
        die(FormatErrors(sqlsrv_errors()));
    }
    if ($rowCases = sqlsrv_fetch_array($getCases)) {
        //$CaseId = $rowCases['caseId'];
        $tamano = $rowCases['total'] * 5;
        echo "<div id=row><div id=\"engineer\"> My total casesToday: </div><div id=\"graphline\" style=\"width:" . $tamano . "px; \"></div><div id=\"tamano\"  style=\"position:relative; \" >" . $rowCases['total'] . "</div></div>";

        $tamano2 = $rowCases['totalcases'] * 5;
        echo "<div id=row><div id=\"engineer\"> Team average cases: </div><div id=\"graphline\" style=\"width:" . $tamano2 . "px; \"></div><div id=\"tamano\"  style=\"position:relative; \" >" . round($rowCases['totalcases'], 2) . "</div></div>";
        //echo "the engineer ". $row['nameToDisplay']. " has "; 
        //echo "total cases ". $rowCases['total']. "  ";
    }
    $x = $x + 1;
    $serie = "Serie" . $x;
}//if
closeDBConnetion();
?>