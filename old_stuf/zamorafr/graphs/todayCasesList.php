<?php

if(!isset($_SESSION)){ 
session_start(); 
} 
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");
    //header('Location:../login.php');
}
require_once("../include/connection.php"); //including the connection functions
$Contador = 0;
$conndb = connectToDB(); //Connection stablished
//----------------------------------
//--------Searching case history-------------
$query = "exec uspTodayCases "; //Finding all entries
$params = array(5);
$result = sqlsrv_query($conndb, $query, $params);
if ($result === false) {
    die(FormatErrors(sqlsrv_errors()));
}
$row = sqlsrv_fetch_array($result);
echo "<div id=tittle><h2>Cases assigned today</h2></div>";
echo "<div id=\"main\">";
echo "<div id=\"rowHistory\">
			<div id=\"caseNumer2\">Case#</div>
			<div id=\"dateUpdate2\">Update</div>
			<div id=\"severityHistory2\">Sev</div>
			<div id=\"fromEngineerHistory2\">Assigned by</div>
			<div id=\"toEngineerHistory2\">Assigned to</div>
			<div id=\"notaHistory2\">Note</div> 			
		</div>";
$params = array(5);
$result = sqlsrv_query($conndb, $query, $params);
if ($result === false) {
    die(FormatErrors(sqlsrv_errors()));
}
While ($row = sqlsrv_fetch_array($result)) { //For each case update	
    echo "<div id=\"rowHistory\">
			<div id=\"caseNumer2\">".$row['caseNumber'];
    if ($row['premier'] == 1) {
        echo "<img src=\"../assets/media/images/premier.png\" width=\"12\" height=\"12\" />";
    }
    echo"</div>
			<div id=\"dateUpdate2\">" . date_format($row['lastUpdate'], "H:i") . "</div>	
			<div id=\"severityHistory2\">" . $row['severy'] . "</div>
			<div id=\"fromEngineerHistory2\">" . $row['userFrom'] . "</div>
			<div id=\"toEngineerHistory2\">" . $row['userTo'] . "</div>			
			<div id=\"notaHistory2\">" . $row['note'] . "</div> 
		</div>";
    $Contador = $Contador + 1;
}//While
echo "</div><div id=\"total_casos\">" . $Contador . "</div>";
closeDBConnetion();
?>