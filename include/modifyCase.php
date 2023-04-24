<?php

//----------Received values from Form--------
/*
  .$_POST["casetxt"];
  .$_POST["premier2"];
  .$_POST["engineerlst"];
  .$_POST["severitylst"];
  .$_POST["datepicker2"];
  .$_POST["callback"];
 */
if(!isset($_SESSION)){ 
session_start(); 
} 
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");
    //header('Location:../login.php');
}
if ($_POST["datepicker2"] == "") {
    $fecha = date("Y-m-d H:i:s");
} else {
    $fecha = $_POST["datepicker2"];
}

if ($_POST["premier2"] == "Yes") {
    $topremier = "True";
} else {
    $topremier = "False";
}
require_once("connection.php"); //including the connection functions
//-----Check if the case exists-----
$conn = connectToDB(); //Connection stablished
//-------FIND USER who modifies

include_once("functions.php");
$QMDetails = getProfileValues(callSessionName());


$query = "exec uspFindCase " . $_POST["casetxt"];
$params = array(5);
$getCase = sqlsrv_query($conn, $query, $params);
if ($getCase === false) {
    die(FormatErrors(sqlsrv_errors()));
}
if ($row = sqlsrv_fetch_array($getCase)) {

    //Here we have the results from the table cases if the case exists
    $queryUser = "exec uspOwnerOfCase " . $_POST["casetxt"]; //Now let's search the assignee for that case
    $params = array(5);
    $getUsers = sqlsrv_query($conn, $queryUser, $params);
    if ($getUsers === false) {
        die(FormatErrors(sqlsrv_errors()));
    }
    if ($row = sqlsrv_fetch_array($getUsers)) {
        //Assignee found

        if ($_POST["engineerlst"] != $row['userId']) { //If the assignee has changed
            $queryNew = "exec uspModifyCaseNewEng " . $_POST["casetxt"] . "," . $QMDetails[0] . ",1," . $_POST["engineerlst"] . "," . $_POST["severitylst"] . "," . $topremier;

            //values for this query: case, qmonitor, teamid, engineer, severity, premier, fechaoffset
            $params = array(5);
            $update = sqlsrv_query($conn, $queryNew, $params);
            if ($update === false) {
                die(print_r(sqlsrv_errors(), true));
            } else {
                echo "The case " . $_POST["casetxt"] . " has been updated succesfully";
            }
        }//if assignee changed
        else {
            $queryNew = "exec uspModifyCaseSameEng " . $_POST["casetxt"] . "," . $QMDetails[0] . "," . $_POST["engineerlst"] . "," . $_POST["severitylst"] . "," . $topremier . ",1";

            //values for this query: case, qmonitor, engineer, severity, premier, teamid
            $params = array(5);
            $update = sqlsrv_query($conn, $queryNew, $params);
            if ($update === false) {
                die(print_r(sqlsrv_errors(), true));
            } else {
                echo "The case " . $_POST["casetxt"] . " has been updated succesfully";
            }
        }
    } else {
        echo "The case is not assigned";
    }

    //echo "The Case: ".$_POST["casetxt"]." was assigned to: ".$row['nombre']." on ".date_format($row['assignTime'],"M-d-Y");
} else {
    echo "<b>The case does not exits</b>";
}
//-------------------------------------------------------
//----------Finding QM--------------
/* $conndb = connectToDB();//Connection stablished

  $sqlquery =	"exec uspQmonitor";
  $params = array(5);
  $getQM = sqlsrv_query( $conndb, $sqlquery, $params);
  if ( $getQM === false)
  { die( FormatErrors( sqlsrv_errors() ) );

  }
  if ($rowqm = sqlsrv_fetch_array($getQM)){
  $qmonitor = $rowqm['usrId'];
  } */
//------------------------------------------------------------------------
closeDBConnetion();
?>