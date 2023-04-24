<?php

if(!isset($_SESSION)){ 
session_start(); 
} 
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");    
}
require_once("connection.php"); //including the connection functions
$errors = null;
$engineer = $_GET["engineer"];
$conndb = connectToDB(); //Connection stablished
echo "Cases details:";
$sqlquery = "exec uspUsrCasebyDay " . $engineer;
$params = array(5);
$getcase = sqlsrv_query($conndb, $sqlquery, $params);
if ($getcase === false) {
    die(FormatErrors(sqlsrv_errors()));
}
while ($rowcase = sqlsrv_fetch_array($getcase)) {
    echo "<div id=\"divcontainer\">";
    echo "<div id=\"assigndTime\">" . date_format($rowcase["assignTime"], "h:i") . "</div>";
    echo "<div id=\"kaseNum\">" . $rowcase["caseNumber"] . "</div>";
    echo "";
    if ($rowcase["severy"] == 1) { //If case is critical            
        if ($rowcase["premier"] == 1) { //If case is premier            
            //if case is premier, is should print according to motiveId.
            switch($rowcase["motiveId"]){
                case 1://New case assigned
                     echo "<div id=\"caseIcon\"> <i class=\"icon-asterisk\" style=\"color: #FF0000; title=\"Critical Premier Case\"></i></div>";
                    break;
                case 9://As a callback
                     echo "<div id=\"caseIcon\"> <i class=\"icon-phone\" style=\"color: #FF0000; title=\"Critical Premier Callback\"></i> </div>";
                    break;
                case 10:// As elevation
                     echo "<div id=\"caseIcon\"> <i class=\"icon-upload\" style=\"color: #FF0000; title=\"Critical Premier Elevation\"></i> </div>";
                    break;
            }
        } else { //Case is critical and Foundation            
            switch($rowcase["motiveId"]){
                case 1: //New case assigned
                     echo "<div id=\"caseIcon\"> <i class=\"icon-circle\" style=\"color: #FF0000; title=\"Critical Foundation Case\"></i> </div>";
                    break;
                case 9://As a callback
                     echo "<div id=\"caseIcon\"> <i class=\"icon-phone-sign\" style=\"color: #FF0000; title=\"Critical Foundation Callback\"></i> </div>";
                    break;
                case 10:// As elevation
                     echo "<div id=\"caseIcon\"> <i class=\"icon-circle-arrow-up\" style=\"color: #FF0000; title=\"Critical Foundation Elevation\" ></i> </div>";
                    break;
            }
        }        
    } else { //If case is severity 2 to 4         
        if ($rowcase["premier"] == 1) {//If case is severity 2 to 4 and premier            
            switch($rowcase["motiveId"]){
                case 1://New case assigned
                     echo "<div id=\"caseIcon\"> <i class=\"icon-asterisk\" style=\"color: #B7CA34; title=\"Premier Case\"></i> </div>";
                    break;
                case 9://As a callback
                     echo "<div id=\"caseIcon\"> <i class=\"icon-phone\" style=\"color: #B7CA34; title=\"Premier Callback\"></i> </div>";
                    break;
                case 10:// As elevation
                     echo "<div id=\"caseIcon\"> <i class=\"icon-upload\" style=\"color: #B7CA34; title=\"Premier elevation\"></i> </div>";
                    break;
            }
        } else {//If case is severity 2 to 4 and foundation            
            switch($rowcase["motiveId"]){
                case 1://New case assigned
                     echo "<div id=\"caseIcon\"> <i class=\"icon-circle\" style=\"color: #B7CA34; title=\"Foundation Case\"></i> </div>";
                    break;
                case 9://As a callback
                     echo "<div id=\"caseIcon\"> <i class=\"icon-phone-sign\" style=\"color: #B7CA34; title=\"Foundation Callback\"></i> </div>";
                    break;
                case 10:// As elevation
                     echo "<div id=\"caseIcon\"> <i class=\"icon-circle-arrow-up\" style=\"color: #B7CA34; title=\"Foundation Elevation\"></i> </div>";
                    break;
            }
        }        
    }
    echo "</div>";
}
?>