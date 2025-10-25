<?php

if(!isset($_SESSION)){ 
session_start(); 
} 
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");
    //header('Location:../login.php');
}
include_once("functions.php");
$QMDetails = getProfileValues(callSessionName());
//----------Finding QM--------------
require_once("connection.php"); //including the connection functions
$errors = null;
$conndb = connectToDB(); //Connection stablished

$sqlquery = "exec uspQmonitor";
$params = array(5);
$getQM = sqlsrv_query($conndb, $sqlquery, $params);
if ($getQM === false) {
    die(FormatErrors(sqlsrv_errors()));
}
if ($rowqm = sqlsrv_fetch_array($getQM)) {
    $qmonitor = $rowqm['usrId'];
}
closeDBConnetion();
//--------------------------------------------------------------------------------------------------
//----------Does the case exists?--------------
require_once("connection.php"); //including the connection functions
$errors = null;
$connect = connectToDB(); //Connection stablished

$sql = "exec uspFindCase " . $_POST["casetxt"];
$params = array(5);
$getCase = sqlsrv_query($connect, $sql, $params);
if ($getCase === false) {
    die(FormatErrors(sqlsrv_errors()));
}
switch ($_POST["ascallback"]) {
    case "Normal":
        //------------------Normal----------------------
        if ($rowCase = sqlsrv_fetch_array($getCase)) { //Exists
            echo "<b>The case is already assigned</b>";
        } else {   //case do not exists
            if ($_POST["premier"] == "Yes") { //Check if the case is premier
                $premierValue = "True";

                addNewCase($premierValue, $QMDetails[0], 1);
            } else {
                $premierValue = "False";
                addNewCase($premierValue, $QMDetails[0], 1);
            }
            echo "The case " . $_POST["casetxt"] . " has been assigned succesfully";
        }
        break;
    //---------------------------------------- 
    case "Callback":
        //------------------Normal----------------------
        if ($rowCase = sqlsrv_fetch_array($getCase)) { //Exists
            updateCase($_POST["casetxt"], $QMDetails[0], $_POST["engineerlst"], $_POST["ascallback"]);
        } else {   //case do not exists
            if ($_POST["premier"] == "Yes") { //Check if the case is premier
                $premierValue = "True";
                addNewCase($premierValue, $QMDetails[0], 0);
                updateCase($_POST["casetxt"], $QMDetails[0], $_POST["engineerlst"], $_POST["ascallback"]);
            } else {
                $premierValue = "False";
                addNewCase($premierValue, $QMDetails[0], 0);
                updateCase($_POST["casetxt"], $QMDetails[0], $_POST["engineerlst"], $_POST["ascallback"]);
            }
        }
        break;
    //----------------------------------------
    case "Elevation":
        //------------------Normal----------------------
        if ($rowCase = sqlsrv_fetch_array($getCase)) { //Exists
            updateCase($_POST["casetxt"], $QMDetails[0], $_POST["engineerlst"], $_POST["ascallback"]);
        } else {   //case do not exists
            if ($_POST["premier"] == "Yes") { //Check if the case is premier
                $premierValue = "True";
                addNewCase($premierValue, $QMDetails[0], 0);
                updateCase($_POST["casetxt"], $QMDetails[0], $_POST["engineerlst"], $_POST["ascallback"]);
            } else {
                $premierValue = "False";

                addNewCase($premierValue, $QMDetails[0], 0);
                updateCase($_POST["casetxt"], $QMDetails[0], $_POST["engineerlst"], $_POST["ascallback"]);
            }
        }
        break;
    //----------------------------------------	
}//switch
closeDBConnetion();

//------------------------------------------------
function addNewCase($premierValue, $qmonitor, $isNew) {
    $product = productValue($_POST["productID"]);

    $conn = connectToDB(); //Connection stablished
    if (!$conn)
        die(print_r(sqlsrv_errors(), true));
    $query = "exec uspAddCase " . $_POST["severitylst"] . "," . $premierValue . "," . $_POST["productID"] . "," . $_POST["casetxt"] . "," . $qmonitor . "," . $_POST["engineerlst"] . ",1," . $isNew . ", " . $product;

    $params = array(5);
    $getUsers = sqlsrv_query($conn, $query, $params);
    if ($getUsers === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {

        $queryUsr = "select usrMail from UserDetails where usrId=" . $_POST["engineerlst"];
        $getUsr = sqlsrv_query($conn, $queryUsr, $params);
        if ($getUsr === false) {
            die(FormatErrors(sqlsrv_errors()));
        } else {
            while ($rowUsr = sqlsrv_fetch_array($getUsr)) {
                $mail = $rowUsr['usrMail'];

                $message = "The case " . $_POST["casetxt"] . " has been assigned to you with severity " . $_POST["severitylst"];
                mail($mail, 'A case has been assigned to you', $message, null);
            }
        }
    }closeDBConnetion();
}

//-----------------------------------------------------------------------------------------------------------
//------------------------------------------------
function updateCase($caseValue, $qmonitor, $engineer, $tipo) {
    $product = productValue($_POST["productID"]);
    $conn = connectToDB(); //Connection stablished
    if (!$conn)
        die(print_r(sqlsrv_errors(), true));
    if ($tipo == "Callback") {
        $query = "exec uspAsCallback " . $caseValue . "," . $qmonitor . "," . $engineer . ", 1, " . $_POST["productID"] . ", " . $product;
    }
    if ($tipo == "Elevation") {
        $query = "exec uspAsElevation " . $caseValue . "," . $qmonitor . "," . $engineer . ", 1, " . $_POST["productID"] . ", " . $product;
    }

    $params = array(5);
    $getUsers = sqlsrv_query($conn, $query, $params);
    if ($getUsers === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {

        $queryUsr = "select usrMail from UserDetails where usrId=" . $_POST["engineerlst"];
        $getUsr = sqlsrv_query($conn, $queryUsr, $params);
        if ($getUsr === false) {
            die(FormatErrors(sqlsrv_errors()));
        } else {
            while ($rowUsr = sqlsrv_fetch_array($getUsr)) {
                if ($tipo == "Callback") {
                    echo "The case " . $_POST["casetxt"] . " has been assigned succesfully as a callback";
                }
                if ($tipo == "Elevation") {
                    echo "The case " . $_POST["casetxt"] . " has been assigned succesfully as an elevation";
                }
            }
        }
    }closeDBConnetion();
}

?>