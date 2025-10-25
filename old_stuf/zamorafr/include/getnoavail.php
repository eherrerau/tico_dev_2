<?php

if(!isset($_SESSION)){ 
session_start(); 
} 
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");
    //header('Location:../login.php');
}
$counter = 0;
require_once("connection.php");
include_once("functions.php");

// *******************************
// Added by dotb@hp.com
// Date: 14-Feb-2013
//
// Includes library for TZ support
//

require_once("includes.php");
    
// *******************************


$userDetails = getProfileValues(callSessionName());
$conn = connectToDB();
$query = "exec uspUserByTimeIn";
//$query = "SELECT * FROM UserDetails where UserDetails.active=1";
$params = array(5);
$getUsers = sqlsrv_query($conn, $query, $params);
if ($getUsers === false) {
    die(FormatErrors(sqlsrv_errors()));
}

echo "<div id=\"BoxColumnsLabel\"><div id=\"engineerNameTitle\"><b>Engineer</b></div><div id=\"premier\">P</div><div id=\"especialIcons\"></div><div id=\"Td\"></div><div id=\"TW\"></div><div id=\"Av\"></div><div id=\"in\">in</div><div id=\"lIn\">Lunch</div><div id=\"out\">out</div><div id=\"cases\">Cases</div></div>";
$scheduleIn = "";
$scheduleOut = "";
$scheduleLunch = "";
while ($row = sqlsrv_fetch_array($getUsers)) {
    $queryShbyUsr = "EXEC uspNoAvail @userid=" . $row["usrId"];
    $getSchByUsr = sqlsrv_query($conn, $queryShbyUsr, $params);
    if ($getSchByUsr === false) {
        die(FormatErrors(sqlsrv_errors()));
    }
    $i = 0;
    $onlyOneUsr = $row["usrId"];
    while ($rowSchbyUsr = sqlsrv_fetch_array($getSchByUsr)) {
        //$schByUser = $rowSchbyUsr["scheduleId"];
        
        // *******************************
        // Changed by dotb@hp.com
        // Date: 14-Feb-2013
        //
        // Includes TZ support on list for available engineers
        //
        
        /*$scheduleIn = date_format($rowSchbyUsr["timeIn"], "H:i");
        $scheduleOut = date_format($rowSchbyUsr["timeOff"], "H:i");
        $scheduleLunch = date_format($rowSchbyUsr["timeLunch"], "H:i");
        $scheduleLunchOff = date_format($rowSchbyUsr["timeLunchEnd"], "H:i");
        
                
        if (date_format($rowSchbyUsr["timeIn"], "H:i") < $scheduleIn) {
            $scheduleIn = date_format($rowSchbyUsr["timeIn"], "H:i");
            $onlyOneUsr = $row["usrId"];
        }
        if (date_format($rowSchbyUsr["timeOff"], "H:i") < $scheduleOut) {
            $scheduleIn = date_format($scheduleOut["timeOff"], "H:i");
            $onlyOneUsr = $row["usrId"];
        }*/
        
        $scheduleIn = new TZSelector(date_format($rowSchbyUsr["timeIn"], "Y-m-d H:i"), $_SESSION['timezone']);
        $scheduleOut = new TZSelector(date_format($rowSchbyUsr["timeOff"], "Y-m-d H:i"), $_SESSION['timezone']);
        $scheduleLunch = new TZSelector(date_format($rowSchbyUsr["timeLunch"], "Y-m-d H:i"), $_SESSION['timezone']);
        $scheduleLunchOff = new TZSelector(date_format($rowSchbyUsr["timeLunchEnd"], "Y-m-d H:i"), $_SESSION['timezone']);
        
        if (date_format($rowSchbyUsr["timeIn"], "H:i") < $scheduleIn->getSystemTime()) {
            $scheduleIn = date_format($rowSchbyUsr["timeIn"], "H:i");
            $onlyOneUsr = $row["usrId"];
        }
        if (date_format($rowSchbyUsr["timeOff"], "H:i") < $scheduleOut->getSystemTime()) {
            $scheduleIn = date_format($scheduleOut["timeOff"], "H:i");
            $onlyOneUsr = $row["usrId"];
        }
        
        // *******************************
        // 
        //$scheduleLunchOff = date("H:i",strtotime($scheduleLunch."+1 hour"));
        //echo $scheduleLunchOff. "--". $scheduleLunch."  ";
        //if (($scheduleIn < date("H:i")) and ($scheduleOut >= date("H:i")) and !((date("H:i") >= $scheduleLunch)and(date("H:i") < $scheduleLunchOff))){	
        //echo $rowSchbyUsr["caseId"];
        $queryCase = "Exec uspUsrCasebyDay @userid =" . $row["usrId"];
        $getCase = sqlsrv_query($conn, $queryCase, $params);
        if ($getCase === false) {
            die(FormatErrors(sqlsrv_errors()));
        }
        $cases[$i] = 0;
        while ($rowCase = sqlsrv_fetch_array($getCase)) {
            $premierCase[$i] = $rowCase["premier"];
            $caseSev[$i] = $rowCase["severy"]; //this is the severity for an especific case
            $cases[$i] = $rowCase["caseId"]; //$cases[i] contains today's cases for one engineer
            $caseNum[$i] = $rowCase["caseNumber"]; //$cases[i] contains today's cases numbers

            $i = $i + 1;
        }
        $queryPremier = "SELECT premier from UserDetails where usrId=" . $row["usrId"];
        $getpremier = sqlsrv_query($conn, $queryPremier, $params);
        if ($getpremier === false) {
            die(FormatErrors(sqlsrv_errors()));
        }
        If ($rowpremier = sqlsrv_fetch_array($getpremier)) {
            if ($rowpremier["premier"])
                $usrPremier = "<img src=\"../assets/media/images/premier.png\" width=\"12\" height=\"12\" alt=\"Premier\" title=\"Premier\"/>";
            else
                $usrPremier = "";
            $counter = $counter + 1;
            // Creates and prints the div id="engineer"  for the non available engineers only
            echo "<div id=\"boxRow\">";

            if ($row['usrName'] == $userDetails[1]) {
                echo "<div id=\"person\"><b>" . $row['nameToDisplay'] . "</b></div> <!--person-->";
            } else {
                echo "<div id=\"person\">" . $row['nameToDisplay'] . "</div> <!--person-->";
            }
            echo "<div id=\"premier\">" . $usrPremier . "</div><!--premier-->
							<div id=\"LW\"></div><!--LW-->
							<div id=\"Td\">";
            //-----------WFH------------
            $queryWFH = "exec uspChkWFH " . $row["usrId"];
            $getWFH = sqlsrv_query($conn, $queryWFH, $params);
            if ($getWFH === false) {
                die(FormatErrors(sqlsrv_errors()));
            }
            If ($rowWFH = sqlsrv_fetch_array($getWFH)) {
                if ($rowWFH['schExcepTypeId'] == 8) {
                    echo "<img src=\"../assets/media/images/house.gif\" width=\"15\" height=\"15\" alt=\"WFH\" title=\"WFH\">";
                }
                if ($rowWFH['schExcepTypeId'] == 1) {
                    echo "<img src=\"../assets/media/images/vacations.gif\" width=\"15\" height=\"15\" alt=\"Vacations\" title=\"Vacations\">";
                }
                if ($rowWFH['schExcepTypeId'] == 2) {
                    echo "<img src=\"../assets/media/images/training.gif\" width=\"15\" height=\"15\" alt=\"Training\" title =\"Training\">";
                }
                if ($rowWFH['schExcepTypeId'] == 3) {
                    echo "<img src=\"../assets/media/images/sick.gif\" width=\"15\" height=\"15\" alt=\"Sick\" title=\"Sick\">";
                }
                if ($rowWFH['schExcepTypeId'] == 4) {
                    echo "<img src=\"../assets/media/images/permission.gif\" width=\"15\" height=\"15\" alt=\"On Permission\" title=\"On Permission\">";
                }
                if ($rowWFH['schExcepTypeId'] == 5) {
                    echo "<img src=\"../assets/media/images/ooq.gif\" width=\"15\" height=\"15\" alt=\"OOQ\" title=\"OOQ\">";
                }
                if ($rowWFH['schExcepTypeId'] == 6) {
                    echo "<img src=\"../assets/media/images/qm.gif\" width=\"15\" height=\"15\" alt=\"Queue Monitor\" title=\"Queue Monitor\">";
                }
            }

            //---------------------------
            echo "</div><!--Td-->
							<div id=\"TW\"></div><!--TW-->
							<div id=\"Av\"></div><!--Av-->";
            // *******************************
            // Changed by dotb@hp.com
            // Date: 14-Feb-2013
            //
            // Includes TZ support on list for available engineers
            //
               
            /*if ($row['usrName'] == $userDetails[1]) {
                echo "<div id=\"in\"><b>" . $scheduleIn . "</b></div><!--in-->
			<div id=\"lIn\"><b>" . $scheduleLunch . " - " . $scheduleLunchOff . "</b></div><!--lIn-->
			<div id=\"out\"><b>" . $scheduleOut . "</b></div><!--out-->";
            } else {
                echo "<div id=\"in\">" . $scheduleIn . "</div><!--in-->
			<div id=\"lIn\">" . $scheduleLunch . " - " . $scheduleLunchOff . "</div><!--lIn-->
                	<div id=\"out\">" . $scheduleOut . "</div><!--out-->";
            }*/
                
            if ($row['usrName'] == $userDetails[1]) {
                echo "<div id=\"in\"><b>" . $scheduleIn->getUserTime() . "</b></div><!--in-->
			<div id=\"lIn\"><b>" . $scheduleLunch->getUserTime() . " - " . $scheduleLunchOff->getUserTime() . "</b></div><!--lIn-->
                	<div id=\"out\"><b>" . $scheduleOut->getUserTime() . "</b></div><!--out-->";
            } else {
                echo "<div id=\"in\">" . $scheduleIn->getUserTime() . "</div><!--in-->
			<div id=\"lIn\">" . $scheduleLunch->getUserTime() . " - " . $scheduleLunchOff->getUserTime() . "</div><!--lIn-->
			<div id=\"out\">" . $scheduleOut->getUserTime() . "</div><!--out-->";
            }
                
            // *******************************
                
            echo "<div id=\"cases\">";
            $x = 0;
            $countPrem = 0;
            $countNoPrem = 0;
            if ($i > 7) {
                while ($i >= 0) {
                    if(isset($cases[$x]))
                    if ($cases[$x] != 0) {

                        if ($caseSev[$x] == 1) {
                            $countPrem = $countPrem + 1;
                        } else {
                            $countNoPrem = $countNoPrem + 1;
                        }
                    }
                    $caseSev[$x] = 0;
                    $cases[$x] = 0;
                    $i = $i - 1;
                    $x = $x + 1;
                }//while
                echo "<b style=\"color:#FF0000\">" . $countPrem . "</b> <img src=\"../assets/media/images/2case.png\" width=\"10\" height=\"10\" onmouseover=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" > ";
                echo "<b style=\"color:#060\">" . $countNoPrem . "</b> <img src=\"../assets/media/images/ncase.png\" width=\"10\" height=\"10\" onmouseover=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" /> ";
            } else {
                while ($i >= 0) {
                    if (isset($cases[$x]))
                    if ($cases[$x] != 0) {

                        if ($caseSev[$x] == 1) {
                            if ($premierCase[$x] == 1) {

                                echo "<img src=\"../assets/media/images/2casep.png\" width=\"10\" height=\"10\" onmouseover=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" /> ";
                            } else {
                                echo "<img src=\"../assets/media/images/2case.png\" width=\"10\" height=\"10\" onmouseover=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" /> ";
                                $countPrem = $countPrem + 1;
                            }
                        } else {
                            if ($premierCase[$x] == 1) {
                                echo "<img src=\"../assets/media/images/ncasep.png\" width=\"10\" height=\"10\" onmouseover=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" /> ";
                            } else {
                                echo "<img src=\"../assets/media/images/ncase.png\" width=\"10\" height=\"10\" onmouseover=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" /> ";
                                $countNoPrem = $countPrem + 1;
                            }
                        }
                    }
                    $caseSev[$x] = 0;
                    $cases[$x] = 0;
                    $i = $i - 1;
                    $x = $x + 1;
                }//while
            }
            echo "</div><!--cases--></div><!--boxRow-->"; //finishes the div creation for the engineer details
        }/* if */
        //}
    }/* While */
}/* * While */
/* Close the connection. */
echo "<div id=\"total_eng\"><b>" . $counter . "</b></div>";
sqlsrv_close($conn);
?>