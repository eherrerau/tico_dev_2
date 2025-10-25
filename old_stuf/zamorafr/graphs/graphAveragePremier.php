<?php

if(!isset($_SESSION)){ 
session_start(); 
} 
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");
    //header('Location:../login.php');
}
require_once("../include/connection.php"); //including the connection functions
//-------------Check start date-------------------
$conn = connectToGlobal();
$querydate = "select * from Team where teamId in(select teamId from DB_ByTeam where DBname='" . $_SESSION['DBtoUse'] . "')"; //Finding all engineers

$params = array(5);
$resultado = sqlsrv_query($conn, $querydate, $params);
if ($resultado === false) {
    //echo $querydate;
    die(FormatErrors(sqlsrv_errors()));
}

While ($rowDate = sqlsrv_fetch_array($resultado)) {

//echo $_SESSION['DBtoUse'];
//echo  "fecha es ".date_format($rowDate["startDate"],"M-d-Y") ."--- team ".$rowDate['teamDesc'];
    $todayDate = date("Y-m-d");
    $date = $todayDate;

    $date_arr = explode('-', $date);

    $dateOneMonthAgo = Date("Y-m-d", mktime(0, 0, 0, $date_arr[1] - 1, $date_arr[2], $date_arr[0]));
    if ($dateOneMonthAgo < date_format($rowDate["startDate"], "Y-m-d")) {
        $dateOneMonthAgo = date_format($rowDate["startDate"], "Y-m-d");
    }
}

closeDBConnetion();
//-----------------------------------------------------

$conndb = connectToDB(); //Connection stablished
$totalUsers = 0;
$totalAvg = 0;
//----------------------------------
//--------Searching engineers-------------
$query = "exec uspOrderPremier' " . $dateOneMonthAgo . "', '" . $todayDate . "'";
; //Finding all engineers
$params = array(5);
$result = sqlsrv_query($conndb, $query, $params);
if ($result === false) {
    die(FormatErrors(sqlsrv_errors()));
}
//echo "<a href=\"graphs\averagePdfReport.php\">pdf</a>";
// <a href=\"graphs\averagePdfReport.php\">pdf</a>";
echo "<h2><div id=tittle>Average cases per Engineer/From " . $dateOneMonthAgo . " to " . $todayDate . " </div></h2>";
echo "<div id=main>";
$x = 1;
$serie = "Serie" . $x;
While ($row = sqlsrv_fetch_array($result)) { //For each Engineer
    $sqlquery = "exec uspAveragePeriod " . $row['usrId'] . ", '" . $dateOneMonthAgo . "' , '" . $todayDate . "'"; //This returns all the assigned cases in the week

    $params = array(5);
    $getCases = sqlsrv_query($conndb, $sqlquery, $params);
    if ($getCases === false) {
        die(FormatErrors(sqlsrv_errors()));
    }
    if ($rowCases = sqlsrv_fetch_array($getCases)) {
        if ($rowCases['average'] > 0) {
            $totalUsers = $totalUsers + 1;
            $totalAvg = $totalAvg + $rowCases['average'];
            $tamano = $rowCases['average'] * 50;
            $tamanoCritical = $rowCases['criticalAvg'] * 50;
            //-------------chick if Available
            $queryShbyUsr = "EXEC uspAvailables @userid=" . $row["usrId"];

            $getSchByUsr = sqlsrv_query($conndb, $queryShbyUsr, $params);
            if(isset($getCasesaverage))
            if ($getCasesaverage === false) {

                die(FormatErrors(sqlsrv_errors()));
            }
            //else {echo "<div id=row><div id=\"engineer\">";}
            $rowSchbyUsr = sqlsrv_fetch_array($getSchByUsr);

            if ($rowSchbyUsr) {
                echo "<div id=row><div id=\"engineer\" >";
            } else {
                echo "<div id=row><div id=\"engineer\"style=\"color: #FF0000;\">";
            }            
            echo " " . $row['nameToDisplay'] . "</div>
                <div id=\"tamano\">" . round($rowCases['average'], 2) . "</div>
                <div id=\"graphline\" style=\"width:" . $tamano . "px; \">";
            if ($tamanoCritical > 0) {
                echo "<img src=\"assets/media/images/criticalBar.gif\" width=\"" . $tamanoCritical . "px\" height=\"10px\" alt=\"Average Critical cases\" />";
            }
            echo "</div></div>";            
        }
    }
    $x = $x + 1;
    $serie = "Serie" . $x;
}//While
closeDBConnetion();
if ($totalUsers > 0) {
    $totalAvg = $totalAvg / $totalUsers;
    echo 
    "<div id=row>
        <div id=\"engineer\" style=\"color:#FF0000;\">AVERAGE</div>
        <div id=\"tamano\"  style=\"position:relative; color:#FF0000;\">" . round($totalAvg, 2) . "</div>
        <div id=\"graphline\" style=\"width:" . round($totalAvg * 50, 0) . "px; \"></div>            
    </div>";
    $totalBar = round($totalAvg * 50, 0);
    $totalBar = $totalBar + 5;
    echo "<div id=\"avgBar\" style=\"width:" . $totalBar . "px\"></div>";
}
?>
