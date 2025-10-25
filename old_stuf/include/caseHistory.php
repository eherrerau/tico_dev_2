<?php

if(!isset($_SESSION)){ 
session_start(); 
} 
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");
    //header('Location:../login.php');
}
require_once("connection.php"); //including the connection functions
if ($_GET["caseNumber"] <= 999999999) {
    echo "Please enter a Case Number";
} else {
    $conndb = connectToDB(); //Connection stablished
    //----------------------------------
    //--------Searching case history-------------
    $query = "exec uspCaseHistory " . $_GET["caseNumber"]; //Finding all entries
    $params = array(5);
    $result = sqlsrv_query($conndb, $query, $params);
    if ($result === false) {
        die(FormatErrors(sqlsrv_errors()));
    }
    echo "<div id=tittle><h2>Case History | Case: " . $_GET["caseNumber"];
    $row = sqlsrv_fetch_array($result);
    if ($row['lastUpdate'] == 1) {
        echo "<img src=\"../assets/media/images/premier.png\" width=\"12\" height=\"12\" />";
    }
    echo "</div>";
    echo "<div id=\"main\">";
    echo "<div id=\"rowHistory\">
			<div id=\"dateUpdate\"><b>Update</b></div>
			<div id=\"severityHistory\"><b>Sev</b></div>
			<div id=\"fromEngineerHistory\"><b>Assigned by</b></div>
			<div id=\"toEngineerHistory\"><b>Assigned to</b></div>
			<div id=\"motiveHistory\"><b>Motive</b></div>
			<div id=\"notaHistory\"><b>Note</b></div> 
			<div id=\"vacio\">-</div>
		</div>";
    $params = array(5);
    $result = sqlsrv_query($conndb, $query, $params);
    if ($result === false) {
        die(FormatErrors(sqlsrv_errors()));
    }
    While ($row = sqlsrv_fetch_array($result)) { //For each case update	
        echo "<div id=\"rowHistory\">
			<div id=\"dateUpdate\">" . date_format($row['lastUpdate'], "M-d-Y H:i") . "</div>
			<div id=\"severityHistory\">" . $row['severy'] . "</div>
			<div id=\"fromEngineerHistory\">" . $row['userFrom'] . "</div>
			<div id=\"toEngineerHistory\">" . $row['userTo'] . "</div>
			<div id=\"motiveHistory\">" . $row['motive'] . "</div>
			<div id=\"notaHistory\">" . $row['note'] . "</div> 
			<div id=\"vacio\">.</div>
		</div>";
    }//While
    echo "</div>";
    closeDBConnetion();
}
?>