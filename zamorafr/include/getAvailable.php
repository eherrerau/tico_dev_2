<?php

if(!isset($_SESSION)){ 
session_start(); 
} 
if (!isset($_SESSION['DBtoUse'])) {
    echo"<meta http-equiv=\"Refresh\" content=\"0;URL=../login.php\">";
}
$i = 0;
$x = 0;
$cases[$i] = 0;
$counter = 0; 
require_once("connection.php");
include_once("functions.php");

// *******************************
// Added by dotb@hp.com
// Date: 14-Feb-2013
//
// Includes library for TZ support
//

// Changed by franklin@hp.com - 28/March/2013
// No need for this next line, already added in header.php
//require_once("includes.php");
    
// *******************************


// Added by franklin@hp.com - 28/March/2013
// No need for these next lines, data is loaded via object.
//$userDetails = getProfileValues(callSessionName());
$conn = connectToDB();
$query = "exec uspUserByTimeIn";
$params = array(5);
$getUsers = sqlsrv_query($conn, $query, $params);
if ($getUsers === false) {
    die(FormatErrors(sqlsrv_errors()));
}

echo "    
    <div id=\"total_eng\">" . $counter . "</div>
    <div id=\"engineerBoxTitle\">Available</div>
    <div id=\"BoxColumnsLabel\">
        <div id=\"engineerNameTitle\"><b>Engineer</b></div>        
        <div id=\"in\">In</div>
        <div id=\"lIn\">Lunch</div>
        <div id=\"out\">Out</div>
        <div id=\"especialIcons\"></div>
        <div id=\"casesLabel\">Cases</div>
      </div>";
$scheduleIn = "";
$scheduleOut = "";
$scheduleLunch = "";
$scheduleLunchOff = "";
while ($row = sqlsrv_fetch_array($getUsers)) {
    $queryShbyUsr = "EXEC uspAvailables @userid=" . $row["usrId"];
    $getSchByUsr = sqlsrv_query($conn, $queryShbyUsr, $params);
    if ($getSchByUsr === false) {
        die(FormatErrors(sqlsrv_errors()));
    }
    $i = 0;
    
    while ($rowSchbyUsr = sqlsrv_fetch_array($getSchByUsr)) {
    
        // *******************************
        // Changed by dotb@hp.com
        // Date: 14-Feb-2013
        //
        // Includes TZ support on list for available engineers
        //
        
        //$scheduleIn = date_format($rowSchbyUsr["timeIn"], "H:i");
        //$scheduleOut = date_format($rowSchbyUsr["timeOff"], "H:i");
        //$scheduleLunch = date_format($rowSchbyUsr["timeLunch"], "H:i");
        //$scheduleLunchOff = date_format($rowSchbyUsr["timeLunchEnd"], "H:i");
     
        $scheduleIn = new TZSelector(date_format($rowSchbyUsr["timeIn"], "Y-m-d H:i"), $_SESSION['timezone']);
        $scheduleOut = new TZSelector(date_format($rowSchbyUsr["timeOff"], "Y-m-d H:i"), $_SESSION['timezone']);
        $scheduleLunch = new TZSelector(date_format($rowSchbyUsr["timeLunch"], "Y-m-d H:i"), $_SESSION['timezone']);
        $scheduleLunchOff = new TZSelector(date_format($rowSchbyUsr["timeLunchEnd"], "Y-m-d H:i"), $_SESSION['timezone']);
        
        // *******************************
        
        //$scheduleLunchOff = date("H:i",strtotime($scheduleLunch."+1 hour"));
        //echo $scheduleLunchOff. "--". $scheduleLunch."  ";
        
        // *******************************
        // Changed by dotb@hp.com
        // Date: 14-Feb-2013
        //
        // Includes TZ support on list for available engineers
        //
        
        //if (($scheduleIn < date("H:i")) and ($scheduleOut >= date("H:i")) and !((date("H:i") >= $scheduleLunch) and (date("H:i") < $scheduleLunchOff))) {
        
        if (($scheduleIn->getSystemTime() < date("H:i")) and ($scheduleOut->getSystemTime() >= date("H:i")) and !((date("H:i") >= $scheduleLunch->getSystemTime()) and (date("H:i") < $scheduleLunchOff->getSystemTime()))) {
        
        // *******************************
        
            //echo $rowSchbyUsr["caseId"];
            $queryCase = "Exec uspUsrCasebyDay @userid =" . $row["usrId"];
            $getCase = sqlsrv_query($conn, $queryCase, $params);
            if ($getCase === false) {
                die(FormatErrors(sqlsrv_errors()));
            }
            while ($rowCase = sqlsrv_fetch_array($getCase)) {
                $cases[$i] = 0;
                $premierCase[$i] = $rowCase["premier"];
                $caseSev[$i] = $rowCase["severy"]; //this is the severity for an especific case
                $cases[$i] = $rowCase["caseId"]; //$cases[i] contains today's cases for one engineer
                $caseNum[$i] = $rowCase["caseNumber"]; //$cases[i] contains today's cases numbers
                $callback[$i] = $rowCase["note"];
                $i = $i + 1;
            }
            $queryPremier = "SELECT premier from UserDetails where usrId=" . $row["usrId"];
            $getpremier = sqlsrv_query($conn, $queryPremier, $params);
            if ($getpremier === false) {
                die(FormatErrors(sqlsrv_errors()));
            }
            If ($rowpremier = sqlsrv_fetch_array($getpremier)) {
                if ($rowpremier["premier"])
                    //$usrPremier = "<img src=\"../assets/media/images/premier.png\" width=\"12\" height=\"12\" title=\"Premier\" />";
                    $usrPremier = "<i class=\"icon-star\" title=\"Premier\"></i>";                    
                else
                    $usrPremier = "";
                // Creates and prints the div id="boxRow"  for the available engineers only
                $counter = $counter + 1;
                echo "<div id=\"boxRow\">";
                if ($row['usrName'] == $userDetails[1]) {
                    echo "<div id=\"person\"><b>" . $row['nameToDisplay'] . "</b></div> <!--person-->";
                } else {
                    echo "<div id=\"person\">" . $row['nameToDisplay'] . "</div> <!--person-->";
                }
                //echo 
//                "<div id=\"premier\">" . $usrPremier . "</div><!--premier-->                
               
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
                        if (isset($cases[$x])) 
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
                    //echo "<b style=\"color:#FF0000\">" . $countPrem . "</b> <img src=\"../assets/media/images/2case.png\" width=\"10\" height=\"10\" onmouseover=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" > ";
                    echo $countPrem . "<i class=\"icon-circle\" style=\"color: #FF0000;\" onclick=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" ></i> ";
                    //echo "<b style=\"color:#060\">" . $countNoPrem . "</b> <img src=\"../assets/media/images/ncase.png\" width=\"10\" height=\"10\" onmouseover=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" /> ";
                    echo $countNoPrem . "<i class=\"icon-circle\" style=\"color: #B7CA34;\" onclick=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" ></i> ";
                } else {
                    while ($i >= 0) {
                        if (isset($cases[$x]))                 
                        if ($cases[$x] != 0) {
                            if ($caseSev[$x] == 1) {
                                if ($premierCase[$x] == 1) {
                                    if ($callback[$x] == "Assign as Callback") { //Critical Callback Permier
                                        //echo "<img src=\"../assets/media/images/sev1cbpr.png\" width=\"10\" height=\"10\" onmouseover=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" /> ";
                                        echo "<i class=\"icon-phone\" style=\"color: #FF0000;\" onclick=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" title=\"Critical Callback Permier\" ></i> ";
                                    } else {
                                        if ($callback[$x] == "Assign as elevation") { //Critical Elevation Premier
                                            //echo "<img src=\"../assets/media/images/Sev1PreEl.png.png\" width=\"10\" height=\"10\" onmouseover=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" /> ";
                                            echo "<i class=\"icon-upload\" style=\"color: #FF0000;\" onclick=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" title=\"Critical Elevation Premier\" ></i> ";
                                        } else {//Critical Premier Case
                                            //echo "<img src=\"../assets/media/images/2casep.png\" width=\"10\" height=\"10\" onmouseover=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" /> ";
                                            echo "<i class=\"icon-asterisk\" style=\"color: #FF0000;\" onclick=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" title=\"Critical Premier Case\" ></i> ";
                                        }
                                    }
                                } else {
                                    if ($callback[$x] == "Assign as Callback") {// Critical Callback Foundation
                                        //echo "<img src=\"../assets/media/images/sev1cb.png\" width=\"10\" height=\"10\" onmouseover=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" /> ";
                                        echo "<i class=\"icon-phone-sign\" style=\"color: #FF0000;\" onclick=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" title=\"Critical Callback Foundation\" ></i> ";
                                    } else {
                                        if ($callback[$x] == "Assign as elevation") { //Critical Elevation Foundation
                                            //echo "<img src=\"../assets/media/images/Sev1PreEl.png\" width=\"10\" height=\"10\" onmouseover=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" /> ";
                                            echo "<i class=\"icon-circle-arrow-up\" style=\"color: #FF0000;\" onclick=\"javascript:caseDescription(event, " . $row["usrId"] . ")\"title=\"Critical Elevation Foundation\" ></i> ";
                                        } else { // Critical Case Foundation
                                            //echo "<img src=\"../assets/media/images/2case.png\" width=\"10\" height=\"10\" onmouseover=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" /> ";
                                            echo "<i class=\"icon-circle\" style=\"color: #FF0000;\" onclick=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" title=\"Critical Case Foundation\" ></i> ";
                                        }
                                    }
                                    $countPrem = $countPrem + 1;
                                }
                            } else {
                                if ($premierCase[$x] == 1) {
                                    if ($callback[$x] == "Assign as Callback") { // Callback Premier
                                        //echo "<img src=\"../assets/media/images/sev2cbpr.png\" width=\"10\" height=\"10\" onmouseover=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" /> ";
                                        echo "<i class=\"icon-phone\" style=\"color: #B7CA34;\" onclick=\"javascript:caseDescription(event, " . $row["usrId"] . ")\"title=\"Callback Premier\" ></i> ";
                                    } else {
                                        if ($callback[$x] == "Assign as elevation") { // Premier Elevation
                                            //echo "<img src=\"../assets/media/images/Sev2PreEl.png\" width=\"10\" height=\"10\" onmouseover=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" /> ";
                                            echo "<i class=\"icon-upload\" style=\"color: #B7CA34;\" onclick=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" title=\"Premier Elevation\" ></i> ";
                                        } else { // Premier Case
                                            //echo "<img src=\"../assets/media/images/ncasep.png\" width=\"10\" height=\"10\" onmouseover=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" /> ";
                                            echo "<i class=\"icon-asterisk\" style=\"color: #B7CA34;\" onclick=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" title=\"Premier Case\" ></i> ";
                                        }
                                    }
                                } else {
                                    if ($callback[$x] == "Assign as Callback") { // Callback Foundation
                                        //echo "<img src=\"../assets/media/images/sev2cb.png\" width=\"10\" height=\"10\" onmouseover=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" /> ";
                                        echo "<i class=\"icon-phone-sign\" style=\"color: #B7CA34;\" onclick=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" title=\"Callback Foundation\" ></i> ";
                                    } else {
                                        if ($callback[$x] == "Assign as elevation") { // Elevation Foundation
                                            //echo "<img src=\"../assets/media/images/Sev2FuEl.png\" width=\"10\" height=\"10\" onmouseover=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" /> ";
                                            echo "<i class=\"icon-circle-arrow-up\" style=\"color: #B7CA34;\" onclick=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" title=\"Elevation Foundation\" ></i> ";
                                            ;
                                        } else {                         //Case Foundation                                                             
                                            //echo "<img src=\"../assets/media/images/ncase.png\" width=\"10\" height=\"10\" onmouseover=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" /> ";
                                            echo "<i class=\"icon-circle\" style=\"color: #B7CA34;\" onclick=\"javascript:caseDescription(event, " . $row["usrId"] . ")\" title=\"Case Foundation\" ></i> ";
                                        }
                                    }
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
                echo    "</div><!--cases-->";
                echo    "<div id=\"especialIcons\">".$usrPremier ;
                //------Birthday here-----							
                if (date("m-d") == date_format($row['birthday'], 'm-d')) {
                    //echo "<img src=\"../assets/media/images/cake.gif\" width=\"15\" height=\"15\" alt=\"Birthday\">";
                    echo "<i class=\"icon-gift\" title=\"Birthday\"></i>";
                }
                //---------------------------
                //-----------WFH------------
                $queryWFH = "exec uspChkWFH " . $row["usrId"];
                $getWFH = sqlsrv_query($conn, $queryWFH, $params);
                if ($getWFH === false) {
                    die(FormatErrors(sqlsrv_errors()));
                }
                While ($rowWFH = sqlsrv_fetch_array($getWFH)) {
                    if ($rowWFH['schExcepTypeId'] == 8) {
                        //echo "<img src=\"../assets/media/images/house.gif\" width=\"15\" height=\"15\" alt=\"WFH\" title=\"WFH\">";                        
                        echo "<i class=\"icon-home\" title=\"Working from home\"></i>";
                    }                    
                    if ($rowWFH['schExcepTypeId'] == 1) {
                        //echo "<img src=\"../assets/media/images/vacations.gif\" width=\"15\" height=\"15\" alt=\"vacations\" title=\"vacations\">";
                        echo "<i class=\"icon-plane\" title=\"Vacations\"></i>";
                    }
                    if ($rowWFH['schExcepTypeId'] == 2) {
                        //echo "<img src=\"../assets/media/images/training.gif\" width=\"15\" height=\"15\" alt=\"Training\" title=\"Training\">";
                        echo "<i class=\"icon-book\" title=\"Training\"></i>";
                    }
                    if ($rowWFH['schExcepTypeId'] == 6) {
                        //echo "<img src=\"../assets/media/images/qm.gif\" width=\"15\" height=\"15\" alt=\"Queue Monitor\" title=\"Queue Monitor\">";
                        echo "<i class=\"icon-desktop\" title=\"Queue Monitor\"></i>";
                    }
                }
                //---------------------------
                echo "</div><!--especialIcons-->
                     </div><!--boxRow-->"; //finishes the div creation for the engineer details
            }/* if */
        }
    }/* While */
}/* * While */
closeDBConnetion();
?>