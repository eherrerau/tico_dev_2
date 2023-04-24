<?php

if(!isset($_SESSION)){ 
session_start(); 
} 
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");    
}

$todayDate = date("Y-m-d");
$date = $todayDate;

$date_arr = explode('-', $date);
$dateOneMonthAgo = Date("Y-m-d", mktime(0, 0, 0, $date_arr[1] - 1, $date_arr[2], $date_arr[0]));
if ($dateOneMonthAgo < '2012-02-06') {
    $dateOneMonthAgo = '2012-02-06';
}

$conndb = connectToDB(); //Connection stablished
//--------Searching engineers-------------
$usrNameQuery = callSessionName();
$query = "SELECT usrId, nameToDisplay FROM UserDetails where usrName='" . $usrNameQuery . "'"; //Finding all engineers
$params = array(5);
$result = sqlsrv_query($conndb, $query, $params);
if ($result === false) {
    die(FormatErrors(sqlsrv_errors()));
}
echo "<div id=MyStatisticsBox>";
echo "<div id=tittle><h2>My Statistics</h2></div>";
$x = 1;
$serie = "Serie" . $x;
if ($row = sqlsrv_fetch_array($result)) { //For each Engineer
    //$row['usrId']
    $sqlquery = "exec uspAveragePeriod " . $row['usrId'] . ", '" . $dateOneMonthAgo . "' , '" . $todayDate . "'"; //This returns all the assigned cases for Today
    $params = array(5);
    $getCases = sqlsrv_query($conndb, $sqlquery, $params);
    if ($getCases === false) {
        die(FormatErrors(sqlsrv_errors()));
    }
    if ($rowCases = sqlsrv_fetch_array($getCases)) {
        
        $tamano = $rowCases['totalcases'] * 5;
        echo "<div id=row>
                <div id=\"engineer\"> Total Cases last 60 days: </div>
                <div id=\"tamano\">" . $rowCases['totalcases'] . "</div>                              
              </div>";
        $tamano2 = $rowCases['average'] * 5;
        echo "<div id=row>
                    <div id=\"engineer\"> W/days last 60 days:</div>              
                    <div id=\"tamano\">" . round($rowCases['totalWorkingDays'], 2) . "</div>
                </div>";

        $sqlqueryW = "exec uspWeekCasesByUser " . $row['usrId']; //This returns all the assigned cases in the week
        $params = array(5);
        $getCasesW = sqlsrv_query($conndb, $sqlqueryW, $params);
        if ($getCasesW === false) {
            die(FormatErrors(sqlsrv_errors()));
        }
        if ($rowCasesW = sqlsrv_fetch_array($getCasesW)) {
        
            $tamano = $rowCasesW['total'] * 25;
            echo "<div id=row>
                    <div id=\"engineer\">My total Cases this Week</div>
                    <div id=\"tamano\">" . $rowCasesW['total'] . "</div>                    
                </div>";
        }
        echo    "<div id=row>
                    <div id=\"engineer\"> My average Last 30 days: </div>
                    <div id=\"tamano\">" . round($rowCases['average'], 2) . "</div>                    
                </div>";
    }
    $x = $x + 1;
    $serie = "Serie" . $x;
}//if
//---------------Calculating Team Average-------------------
//--------Searching engineers-------------
$profileDetails = getProfileValues(callSessionName());
if ($profileDetails[6] == true) {
    $query = "SELECT usrId, nameToDisplay FROM UserDetails WHERE usrId in (SELECT usrId FROM RoleByUsr WHERE roleId IN(SELECT roleId FROM Roles Where receivesCases=1)) and active='True' and Premier ='True' Order by nameToDisplay"; //Finding all engineers
} else {
    $query = "SELECT usrId, nameToDisplay FROM UserDetails WHERE usrId in (SELECT usrId FROM RoleByUsr WHERE roleId IN(SELECT roleId FROM Roles Where receivesCases=1)) and active='True' and Premier ='False' Order by nameToDisplay"; //Finding all engineers
}
$params = array(5);
$result = sqlsrv_query($conndb, $query, $params);
if ($result === false) {
    die(FormatErrors(sqlsrv_errors()));
}

$x = 1;
$serie = "Serie" . $x;
$totalUsers = 0;
$totalAvg = 0;
While ($row = sqlsrv_fetch_array($result)) { //For each Engineer
    $totalUsers = $totalUsers + 1;

    $sqlquery = "exec uspAveragePeriod " . $row['usrId'] . ", '" . $dateOneMonthAgo . "' , '" . $todayDate . "'";

    $params = array(5);
    $getCases = sqlsrv_query($conndb, $sqlquery, $params);
    if ($getCases === false) {
        die(FormatErrors(sqlsrv_errors()));
    }
    if ($rowCases = sqlsrv_fetch_array($getCases)) {
        $totalAvg = $totalAvg + $rowCases['average'];
        $tamano = $rowCases['average'] * 50;
    }
    $x = $x + 1;
    $serie = "Serie" . $x;
}//While

if ($totalUsers > 0) {
    $totalAvg = $totalAvg / $totalUsers;

    echo    "<div id=row>
                <div id=\"engineer\">Team Avg last 30 days</div>
                <div id=\"tamano\">" . round($totalAvg, 2) . "</div>                
            </div>";
}
echo "</div>";
?>