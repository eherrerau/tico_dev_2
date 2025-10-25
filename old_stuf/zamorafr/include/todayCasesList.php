<?php

session_start();
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");
    //header('Location:../login.php');
}
require_once("connection.php"); //including the connection functions
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
echo "<div id=tittle>Cases assigned today</div>";
echo "<div id=\"main\">";
echo "<div id=\"rowHistory\">
			<div id=\"caseNumer2\"><b>Case#</b></div>
			<div id=\"dateUpdate2\"><b>Update</b></div>
			<div id=\"severityHistory2\"><b>Sev</b></div>
			<div id=\"fromEngineerHistory2\"><b>Assigned by</b></div>
			<div id=\"toEngineerHistory2\"><b>Assigned to</b></div>
			<div id=\"notaHistory2\"><b>Note</b></div> 			
		</div>";

$params = array(5);
$result = sqlsrv_query($conndb, $query, $params);
if ($result === false) {
    die(FormatErrors(sqlsrv_errors()));
}
While ($row = sqlsrv_fetch_array($result)) { //For each case update	
    echo "<div id=\"rowHistory\">
			<div id=\"caseNumer2\"><b>" . $row['caseNumber'] . "</b>";
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