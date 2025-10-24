<?php

if(!isset($_SESSION)){ 
session_start(); 
} 
require_once("functions.php");
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");
    //header('Location:../login.php');
}
$profileDetails = getProfileValues(callSessionName());

require_once("connection.php");
$conn = connectToDB();
$queryShbyUsr = "exec uspAllengineers";
$params = array(5);
$getUsr = sqlsrv_query($conn, $queryShbyUsr, $params);
if ($getUsr === false) {
    echo "failed";
    die(FormatErrors(sqlsrv_errors()));
}
$i = 0;
echo"<select id=\"engineerlst\" name=\"engineerlst\">";
while ($rowUsr = sqlsrv_fetch_array($getUsr)) {
    //if there is permitision as QM, ADmin or Manager
    if ((isProfile(1))||(isProfile(2))|| (isProfile(3)))  {
        if ($rowUsr["usrId"] == $profileDetails[0]) {
        echo "<option selected=\"selected\" value=\"" . $rowUsr["usrId"] . "\">" . $rowUsr["NameToDisplay"] . " </option>";
    }else{
        echo "<option value=\"" . $rowUsr["usrId"] . "\">" . $rowUsr["NameToDisplay"] . "</option>";
    }
    }
    //if there is just an engineer do not shows the engineer list.
    else{
        if ($rowUsr["usrId"] == $profileDetails[0]) {
        echo "<option selected=\"selected\" hidden=\"true\" value=\"" . $rowUsr["usrId"] . "\">" . $rowUsr["NameToDisplay"] . " </option>";
    }else{
        echo "<option hidden=\"true\" value=\"" . $rowUsr["usrId"] . "\">" . $rowUsr["NameToDisplay"] . "</option>";
    }
    }
}/* While */
echo "</select>";
closeDBConnetion();
?>