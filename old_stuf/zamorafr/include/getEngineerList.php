<?php

if(!isset($_SESSION)){ 
session_start(); 
} 
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");
    //header('Location:../login.php');
}
require_once("connection.php");
/*
  $todayDate = date("Y-m-d");
  $date=$todayDate;

  $date_arr=explode('-',$date);

  $dateOneMonthAgo= Date("Y-m-d",mktime(0,0,0,$date_arr[1]-1,$date_arr[2],$date_arr[0]));
  if ($dateOneMonthAgo < '2012-02-06'){
  $dateOneMonthAgo = '2012-02-06';
  } */

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
$conn = connectToDB();
$query = "exec uspNextEngineer '" . $dateOneMonthAgo . "', '" . $todayDate . "', " . $_GET['producto'];

$params = array(5);
$getUsers = sqlsrv_query($conn, $query, $params);
if ($getUsers === false) {
    die(FormatErrors(sqlsrv_errors()));
}

$scheduleIn = "";
$scheduleOut = "";
$scheduleLunch = "";
echo"<select id=\"engineerlst\" name=\"engineerlst\" tabindex=\"6\" >";
while ($row = sqlsrv_fetch_array($getUsers)) {
    $queryShbyUsr = "EXEC uspAvailables @userid=" . $row["usrId"];
    $getSchByUsr = sqlsrv_query($conn, $queryShbyUsr, $params);
    if ($getSchByUsr === false) {
        die(FormatErrors(sqlsrv_errors()));
    }
    $i = 0;


    while ($rowSchbyUsr = sqlsrv_fetch_array($getSchByUsr)) {
        //$schByUser = $rowSchbyUsr["scheduleId"];
        $scheduleIn = date_format($rowSchbyUsr["timeIn"], "H:i");
        $scheduleOut = date_format($rowSchbyUsr["timeOff"], "H:i");
        $scheduleLunch = date_format($rowSchbyUsr["timeLunch"], "H:i");
        $scheduleLunchOff = date_format($rowSchbyUsr["timeLunchEnd"], "H:i");
        //$scheduleLunchOff = date("H:i",strtotime($scheduleLunch."+1 hour"));
        //echo $scheduleLunchOff. "--". $scheduleLunch."  ";
        if (($scheduleIn < date("H:i")) and ($scheduleOut >= date("H:i")) and !((date("H:i") >= $scheduleLunch) and (date("H:i") < $scheduleLunchOff))) {

            echo "<option value=\"" . $row["usrId"] . "\">" . $row["nameToDisplay"] . "</option>";
        }
    }/* While */
}/* * While */
echo "</select>";
/* Close the connection. */
closeDBConnetion();
?>