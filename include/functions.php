<?php
// used by the loginCheck.php
function Login() {
    $username = trim($_POST['username']);
    $password = trim($_POST['passwordTB']);
    $teamID = trim($_POST['teamDropmenu']);
    $pwdSha1 = sha1($password);

    require_once("connection.php");
    $conn = connectToGlobal();
//    echo "Imprimiendo conn: " .var_dump($conn); "</br>";

    $sqlquery = "Select DBname from DB_ByTeam where teamId=" . $teamID;
//    var_dump($sqlquery). "</br>";
    $params = array(5);
    $getGlobal = sqlsrv_query($conn, $sqlquery, $params);    
//    echo "bien hasta aca" . "</br>";
//    var_dump($getGlobal). "</br>";
    while ($rowglobal = sqlsrv_fetch_array($getGlobal)) {
        session_start();
        $_SESSION['TICO_DB_PC'] = $rowglobal['DBname'];        
    }
//    closeDBConnetion();
    if (CheckLoginInDB($username, $pwdSha1, $teamID)) {
        $_SESSION['username'] = $_POST['username'];
        // *******************************
        // Added by dotb@hp.com
        // Date: 14-Feb-2013
              
        $_SESSION['timezone'] = getUserTimeZone();
                      
        // ********************************
        
        header('Location:../index.php');       
        return true;
    } else {
//        header('Location:../login.php?error=true');
//        echo $errors;
        return false;
    }
}
// used by the loginCheck.php
function CheckLoginInDB($username, $pwdSha1, $teamId) {
     $conn = connectToDB();
     $qry = "EXEC usp_login_autentication '" . $username . "', '" . $pwdSha1 . "', " . $teamId;
    $params = array(5);
    $result = sqlsrv_query($conn, $qry, $params);

    if (sqlsrv_fetch_array($result) == 0) {
        $errors = ("The username or password incorrect");
        closeDBConnetion();
        //echo $errors;
        return false;
    }

    closeDBConnetion();
    return true;
}

function getErrors() {
    if ($errors != null) {
        return $errors;
    } else {
        return false;
    }
}

function getHours() {
    $hours = array("00", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23");
    foreach ($hours as $value) {
        echo "<option value=" . "\"" . $value . "\">" . $value . "</option>";
    }
}

function getHours1($h) {
    $hours = array("00", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23");
//    $hour;
    $x = 0;
    foreach ($hours as $value) {
        if ($value == $h) {
            $hour[$x] = "<option value=" . "\"" . $value . "\"selected >" . $value . "</option>";
            $x++;
        } else {
            $hour[$x] = "<option value=" . "\"" . $value . "\">" . $value . "</option>";
            $x++;
        }
    }
    return $hour;
}

function getMinutes() {
    $hours = array("00", "15", "30", "45");
    foreach ($hours as $value) {
        echo "<option value=" . "\"" . $value . "\">" . $value . "</option>";
    }
}

function getMinutes1($m) {
    $hours = array("00", "15", "30", "45");
//    $min;
    $x = 0;
    foreach ($hours as $value) {
        if ($value == $m) {
            $min[$x] = "<option value=" . "\"" . $value . "\"selected >" . $value . "</option>";
            $x++;
        } else {
            $min[$x] = "<option value=" . "\"" . $value . "\">" . $value . "</option>";
            $x++;
        }
    }return $min;
}

//used by all the pages
function callSessionName() {
//	session_start(); 
    return $_SESSION['username'];
}

//used by all the pages
///*
// REmove by ehu@hp.com
// No longer used in version 2.0
// // 
//function signOut() {
//    session_start();
//    $_SESSION = array();
//    session_destroy();
//    header('Location: login.php');
//}

//used on all pages
function isProfile($roleToFind) {
    include_once("include/connection.php");
    $conndb = connectToDB(); //Connection stablished	
    $sqlquery = "exec uspCheckRole " . callSessionName() . ", " . $roleToFind;
    $params = array(5);
    $getRoles = sqlsrv_query($conndb, $sqlquery, $params);
    if ($getRoles == false) {
        echo "No Roles were found for this user";
    }
    $rolesValue = array();
    $x = 0;
    if ($row = sqlsrv_fetch_array($getRoles)) {
        if ($row["TotalRoles"] > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

//My profile page
function getProfileValues($user) {
    include_once("connection.php");
    $conndb = connectToDB(); //Connection stablished	
    $sqlquery = "exec uspshowProfileInfo " . $user;
    $params = array(5);
    $getMyProfile = sqlsrv_query($conndb, $sqlquery, $params);
    if ($getMyProfile === false) {
//        header('Location: login.php');
        //die( FormatErrors( sqlsrv_errors() ) ); 	
    }
    $row = sqlsrv_fetch_array($getMyProfile);
    $profileValues = array($row["usrId"], $row["usrName"], $row["nameToDisplay"], $row["usrMail"], $row["phoneExt"], $row["birthday"], $row["premier"], $row["teamDesc"]);
    return $profileValues;
}

//My profile page -- Fills the roles that a user has.
function getRolesOnMyProfile($usrId) {
    include_once("connection.php");
    $conndb = connectToDB(); //Connection stablished
    $sqlquery = "exec uspGetUserRoles " . $usrId;
    $params = array(5);
    $getRolesOnMyProfile = sqlsrv_query($conndb, $sqlquery, $params);
    if ($getRolesOnMyProfile == false) {
        echo "No Roles were found for this user";
    }
    $rolesValue = array();
    $x = 0;
    while ($row = sqlsrv_fetch_array($getRolesOnMyProfile)) {
        $rolesValue[$x] = $row["roleDesc"];
        $x++;
    }
    return $rolesValue;
}

function getAllRoles() {
    include_once("connection.php");
    $conndb = connectToDB(); //Connection stablished
    $sqlquery = "Select roleId, roleDesc from Roles";
    $params = array(5);
    $getRolesOnMyProfile = sqlsrv_query($conndb, $sqlquery, $params);
    if ($getRolesOnMyProfile == false) {
        echo "No Roles were found for this user";
    }
    $rolesValue = array();
    $x = 0;
    while ($row = sqlsrv_fetch_array($getRolesOnMyProfile)) {


        $rolesValue[$x] = "<input name=\"roleChk[" . $row["roleId"] . "]\" id=\"roleChk[" . $row["roleId"] . "]\" title=\"" . $row["roleDesc"] . "\" type=\"checkbox\" value=\"" . $row["roleId"] . "\"> " . $row["roleDesc"];


        $x++;
    }
    return $rolesValue;
}

//----------------------------------------------------------------------------------------------
function getAllRolesByUsr($usrId) {
    include_once("connection.php");
    $conndb = connectToDB(); //Connection stablished
    $sqlquery = "Select roleId, roleDesc from Roles";
    $params = array(5);
    $getRolesOnMyProfile = sqlsrv_query($conndb, $sqlquery, $params);

    if ($getRolesOnMyProfile == false) {
        echo "No Roles were found for this user";
    }
    $rolesValue = array();
    $x = 0;
    while ($row = sqlsrv_fetch_array($getRolesOnMyProfile)) {

        $sqlqueryCheked = "Select roleId from RoleByUsr where usrId=" . $usrId . " and roleId=" . $row["roleId"];
        $getRolesByUsr = sqlsrv_query($conndb, $sqlqueryCheked, $params);
        if ($row2 = sqlsrv_fetch_array($getRolesByUsr)) {
            $rolesValue[$x] = "<input name=\"roleChk\" title=\"" . $row["roleDesc"] . "\" type=\"checkbox\" value=\"" . $row["roleId"] . "\" Checked> " . $row["roleDesc"];
        } else {
            $rolesValue[$x] = "<input name=\"roleChk\" title=\"" . $row["roleDesc"] . "\" type=\"checkbox\" value=\"" . $row["roleId"] . "\"> " . $row["roleDesc"];
        }
        $x++;
    }
    return $rolesValue;
}

//----------------------------------------------------------------------------------------------
function getAllProductsByUsr($usrId) {
    include_once("connection.php");
    $conndb = connectToDB(); //Connection stablished
    //$sqlquery =execQuery("exec uspProductList");
    $sqlquery = "exec uspProductList";
    $params = array(5);
    $getAllProductOnMyProfile = sqlsrv_query($conndb, $sqlquery, $params);
    //$getAllProductOnMyProfile= execQuery("exec uspProductList");
    if ($getAllProductOnMyProfile == false) {
        echo "No Products were found";
    }
//	echo implode(",", $getAllProductOnMyProfile);
    $productsValue = array();
    $x = 0;
    while ($row = sqlsrv_fetch_array($getAllProductOnMyProfile)) {
        $sqlqueryCheked = "Select productId from ProductsByUser where usrId=" . $usrId . " and productId=" . $row["productId"];

        $getproductByUsr = sqlsrv_query($conndb, $sqlqueryCheked, $params);
        if ($row2 = sqlsrv_fetch_array($getproductByUsr)) {

            $productsValue[$x] = "<input name=\"Products[" . $row["productId"] . "]\" id=\"Products[" . $row["productId"] . "]\" title=\"" . $row["productDesc"] . "\" type=\"checkbox\" value=\"" . $row["productId"] . "\" Checked> " . $row["productDesc"];
        } else {

            $productsValue[$x] = "<input name=\"Products[" . $row["productId"] . "]\" id=\"Products[" . $row["productId"] . "]\" title=\"" . $row["productDesc"] . "\" type=\"checkbox\" value=\"" . $row["productId"] . "\"> " . $row["productDesc"];
        }
//		<input name="" title="$row["productDesc"]" type="checkbox" value="$row["productDesc"]">
        $x++;
    }
    closeDBConnetion();
    return $productsValue;
}

//----------------------------------------------------------------------------------------------
//My profile page -- Fills the list of products.	
function getAllProductsOnMyProfile() {
    include_once("connection.php");
    $conndb = connectToDB(); //Connection stablished
    //$sqlquery =execQuery("exec uspProductList");
    $sqlquery = "exec uspProductList";
    $params = array(5);
    $getAllProductOnMyProfile = sqlsrv_query($conndb, $sqlquery, $params);
    //$getAllProductOnMyProfile= execQuery("exec uspProductList");
    if ($getAllProductOnMyProfile == false) {
        echo "No Products were found";
    }
//	echo implode(",", $getAllProductOnMyProfile);
    $productsValue = array();
    $x = 0;
    while ($row = sqlsrv_fetch_array($getAllProductOnMyProfile)) {


        $productsValue[$x] = "<input name=\"Products[" . $row["productId"] . "]\" id=\"Products[" . $row["productId"] . "]\" title=\"" . $row["productDesc"] . "\" type=\"checkbox\" value=\"" . $row["productId"] . "\"> " . $row["productDesc"];
//		<input name="" title="$row["productDesc"]" type="checkbox" value="$row["productDesc"]">
        $x++;
    }
    closeDBConnetion();
    return $productsValue;
}

//My profile page -- Return all the supported products by the engineer.
function getAllProductsSupportedOnMyProfile($usrId) {
    $supporteProducts = getAllProductsOnMyProfile();
    include_once("connection.php");
    $conndb = connectToDB(); //Connection stablished
    //$sqlquery =execQuery("exec uspProductList");
    $sqlquery = "exec uspGetProductsForProfile " . $usrId;
    $params = array(5);
    $getAllSupportedProductOnMyProfile = sqlsrv_query($conndb, $sqlquery, $params);
    if ($getAllSupportedProductOnMyProfile == false) {
        echo "No Products were found";
    }
    $productsSupported = array();
    $x = 0;
    while ($row = sqlsrv_fetch_array($getAllSupportedProductOnMyProfile)) {
        $productsSupported[$x] = "<input name=\"Products\" title=\"" . $row["productDesc"] . "\" type=\"checkbox\" value=\"" . $row["productId"] . "\"> " . $row["productDesc"];
//		<input name="" title="$row["productDesc"]" type="checkbox" value="$row["productDesc"]">
        $x++;
    }
    closeDBConnetion();
    return $productsSupported;
}

//My profile page -- Compares the list of products vrs the supported products by the engineer 
//and add the check value at the end of every HTML check on each product to presented on the profile page
function compareArrayProducts($userId) {
    $listOfProducts = getAllProductsOnMyProfile();
    $ListOfSupportedProducts = getAllProductsSupportedOnMyProfile($userId);
    $difference = array_diff($listOfProducts, $ListOfSupportedProducts);
    foreach ($ListOfSupportedProducts as $key => $value) {
        $supported[$key] = $value ;
    }
    $results = array_merge($supported, $difference);
    return $results;
}

function getScheduleForUser($userId, $roleId) {
    include_once("connection.php");
    $conndb = connectToDB(); //Connection stablished		
    $sqlquery = "exec uspGetScheduleByRoleAndUser " . $userId . ", " . $roleId;
    $params = array(5);
    $getScheduleOnMyProfile = sqlsrv_query($conndb, $sqlquery, $params);
    $getScheduleOnMyProfile2 = sqlsrv_query($conndb, $sqlquery, $params);
    //$getScheduleOnMyProfile= execQuery("exec uspGetScheduleByRoleAndUser ".$userId .", " .$roleId);
    //echo $getScheduleOnMyProfile;
    $schedule = array();
    if ($getScheduleOnMyProfile === false) {
        
    } else {
        $x = 0;
        $row2 ="";
        if ($row2 == sqlsrv_fetch_array($getScheduleOnMyProfile2)) {
            return $schedule = callEmptySched();
        }
        while ($row = sqlsrv_fetch_array($getScheduleOnMyProfile)) {
            //Gets the hours and minustes from time "In"
            $TInH = implode(getHours1(getHoursFromDate($row['timeIn'], "h")));
            $TInM = implode(getMinutes1(getHoursFromDate($row['timeIn'], "m")));
            //Gets the hours and minustes from "Lunch in"
            $LInH = implode(getHours1(getHoursFromDate($row['timeLunch'], "h")));
            $LInM = implode(getMinutes1(getHoursFromDate($row['timeLunch'], "m")));
            //Gets the hours and minustes from "Lunch Out"
            $LEndH = implode(getHours1(getHoursFromDate($row['timeLunchEnd'], "h")));
            $LEndM = implode(getMinutes1(getHoursFromDate($row['timeLunchEnd'], "m")));
            //Gets the hours and minustes from "Time Off"
            $TOffH = implode(getHours1(getHoursFromDate($row['timeOff'], "h")));
            $TOffM = implode(getMinutes1(getHoursFromDate($row['timeOff'], "m")));
            $schedule[$x] =
                    "<div id=\"" . getDayOfTheWeekSpanish($row['dayOfTheWeek']) . "\">
                		<div id=\"weekDay\">" . getDayOfTheWeek($row['dayOfTheWeek']) . "</div>
						<div id=\"in\"><select name=\"InH" . $row['dayOfTheWeek'] . "\">"
                    . $TInH . "
							</select>:<select name=\"InMin" . $row['dayOfTheWeek'] . "\">"
                    . $TInM . "
							</select>
                    	</div>
						<div id=\"out\"><select name=\"OutH" . $row['dayOfTheWeek'] . "\">"
                    . $TOffH . "
							</select>:<select name=\"OutMin" . $row['dayOfTheWeek'] . "\">"
                    . $TOffM . "
							</select>
                    	</div>
						<div id=\"lunchIn\"><select name=\"LInH" . $row['dayOfTheWeek'] . "\">"
                    . $LInH . "
							</select>:<select name=\"LInMin" . $row['dayOfTheWeek'] . "\">"
                    . $LInM . "
							</select>
						</div>
						<div id=\"lunchOut\"><select name=\"LOutH" . $row['dayOfTheWeek'] . "\">"
                    . $LEndH . "
						</select>:<select name=\"LOutMin" . $row['dayOfTheWeek'] . "\">"
                    . $LEndM . "
						</select></div>
                </div>
				<!-- " . getDayOfTheWeekSpanish($row['dayOfTheWeek']) . " -->";
            $x++;
        }
        return $schedule;
    }
}

function callEmptySched() {
    $schedule = array();
    $days = array("", "Sunday", "Monday", "Tuesday", "Wednesday", "Thusrday", "Friday", "Saturday");
    $daysSpanish = array("", "domingo", "lunes", "martes", "miercoles", "jueves", "viernes", "sabado");
    $h = implode(getHours1(0));
    $m = implode(getMinutes1(0));
    //$marcos = "bla";
    for ($x = 1; $x <= 7;) {
        $schedule[$x] =
                "<div id=\"" . $daysSpanish[$x] . "\">
                		<div id=\"weekDay\">" . $days[$x] . "</div>
						<div id=\"in\"><select name=\"InH" . $x . "\">"
                . $h . "
							</select>:<select name=\"InMin" . $x . "\">"
                . $m . "
							</select>
                    	</div>
						<div id=\"out\"><select name=\"OutH" . $x . "\">"
                . $h . "
							</select>:<select name=\"OutMin" . $x . "\">"
                . $m . "
							</select>
                    	</div>
						<div id=\"lunchIn\"><select name=\"LInH" . $x . "\">"
                . $h . "
							</select>:<select name=\"LInMin" . $x . "\">"
                . $m . "
							</select>
						</div>
						<div id=\"lunchOut\"><select name=\"LOutH" . $x . "\">"
                . $h . "
						</select>:<select name=\"LOutMin" . $x . "\">"
                . $m . "
						</select></div>
                </div>
				<!-- " . $daysSpanish[$x] . " -->";
        $x++;
    }
    return $schedule;
}

function getDayOfTheWeek($number) {
    switch ($number) {
        case 1:
            $day = "Sunday";
            break;
        case 2:
            $day = "Monday";
            break;
        case 3:
            $day = "Tuesday";
            break;
        case 4:
            $day = "Wednesday";
            break;
        case 5:
            $day = "Thursday";
            break;
        case 6:
            $day = "Friday";
            break;
        case 7:
            $day = "Saturday";
            break;
    }
    return $day;
}

function getDayOfTheWeekSpanish($number) {
    switch ($number) {
        case 1:
            $day = "domingo";
            break;
        case 2:
            $day = "lunes";
            break;
        case 3:
            $day = "martes";
            break;
        case 4:
            $day = "miercoles";
            break;
        case 5:
            $day = "jueves";
            break;
        case 6:
            $day = "viernes";
            break;
        case 7:
            $day = "sabado";
            break;
    }
    return $day;
}

function getHoursFromDate($date, $type) {
    $data;
    switch ($type) {
        case "h":
            $data = date_format($date, "H");
            break;
        case "m":
            $data = date_format($date, "i");
            break;
        case "d":
            $data = date_format($date, "H:i");
            break;
        case "g":
            $data = gmdate($date, "H:i"); // hours and minutes
            break;
        case "u":
            $data = date_format($date, "M d Y H:i");
            break;
    }
    return $data;
}

function getSchedExcepList() {
    require_once("connection.php");
    $conn = connectToDB();
    $query = "EXEC uspScheduleExcepTypesList";
    $params = array(5);
    $getUserStatus = sqlsrv_query($conn, $query, $params);
    if ($getUserStatus === false) {
        die(FormatErrors(sqlsrv_errors()));
    }
    $schedExecList = array();
    $x = 0;
    while ($row = sqlsrv_fetch_array($getUserStatus)) {
        $schedExecList[$x] = "<option value=" . $row["schExcepTypeId"] . ">" . $row["schExcepTypeDesc"] . "</option>";
        $x++;
    }
    return $schedExecList;
}

function updateMyProfile($usrId) {
    echo $nameToDisplay = trim($_POST['Name']);
    echo $usrmail = trim($_POST["Mail"]);
    echo $phoneExt = trim($_POST["Phone"]);
    echo $bday = trim($_POST["Bday"]);
    echo $timeZone = $_POST['timezone'];
    echo " Premier:" . $premier = $_POST['premier_ck'];
    $premierT = convertChecks_TrueOrFalse($premier);
    $Biday = date_format(new DateTime($bday), 'Ymd');
    echo $premierT;
    echo "Biday:" . $Biday;
    require_once("connection.php");
    // *******************************
    // Added by dotb@hp.com
    // Date: 21-Feb-2013
    //
    // Add option to save timezone for an user
    //    
    
    $conn = connectToDB();
    //$query = "EXEC uspUpdateMyProfile " . $usrId . ", '" . $nameToDisplay . "', '" . $usrmail . "', '" . $phoneExt . "' ,'" . $Biday . "' ,'" . $premierT . "'";
    
    $query = "EXEC uspUpdateMyProfile " . $usrId . ", '" . $nameToDisplay . "', '" . $usrmail . "', '" . $phoneExt . "' ,'" . $Biday . "' ,'" . $premierT . "', '$timeZone'";
    
    //************************
    echo $query;
    $params = array(5);
    $updateMyProfile = sqlsrv_query($conn, $query, $params);
    if ($updateMyProfile == false) {
        echo "No hay nada";
        echo die(FormatErrors(sqlsrv_errors()));
    } else {
        echo "My profile was succesfully updated";
    }
}

function convertChecks_TrueOrFalse($value) {
    $result = "";
    switch ($value) {
        case "on":
            $result = 1;
            break;
        case "":
            $result = 0;
            break;
        case "0":
            $result = 0;
            break;
        case "1":
            $result = 1;
            break;
    }
    return $result;
}

/*
 * Shows the Schedule Expections list for the last 30 days in the profile page. 
 */

function getNextSchedExcep($ursId) {
    $conn = connectToDB();
    $query = "EXEC uspGetNextScheduleExcep " . $ursId;    
    $params = array(5);
    $getNextSchedExcep = sqlsrv_query($conn, $query, $params);
    if ($getNextSchedExcep === false) {        
        $result = "There is no any Schedule Exceptions for today";
    } else {
        $x = 0;
        $result = array();
        while ($row = sqlsrv_fetch_array($getNextSchedExcep)) {
            switch ($row["Reason"]) {
                                            case "Vacations":
                                                $schedExcpIcon = "<div id=\"exceptionIcon\">
                                                         <i class=\"icon-plane\"></i>
                                                      </div>";
                                                break;
                                            case "WFH":
                                                $schedExcpIcon = "<div id=\"exceptionIcon\">
                                                         <i class=\"icon-home\"></i>
                                                      </div>";
                                                break;
                                            case "On Permission":
                                                $schedExcpIcon = "<div id=\"exceptionIcon\">
                                                         <i class=\"icon-thumbs-up\"></i>
                                                      </div>";
                                                break;
                                            case "Training":
                                                $schedExcpIcon = "<div id=\"exceptionIcon\">
                                                         <i class=\"icon-book\"></i>
                                                      </div>";
                                                break;
                                            case "Sick":
                                                $schedExcpIcon = "<div id=\"exceptionIcon\">
                                                         <i class=\"icon-medkit\"></i>
                                                      </div>";
                                                break;
                                            case "OOQ":
                                                $schedExcpIcon = "<div id=\"exceptionIcon\">
                                                         <i class=\"icon-minus-sign\"></i>
                                                      </div>";
                                                break;
                                            case "QM":
                                                $schedExcpIcon = "<div id=\"exceptionIcon\">
                                                         <i class=\"icon-desktop\"></i>
                                                      </div>";
                                                break;
                                            case "Holiday":
                                                $schedExcpIcon = "<div id=\"exceptionIcon\">
                                                         <i class=\"icon-suitcase\"></i>
                                                      </div>";
                                                break;
                                                default:
                                                break;
                                            }
            $result[$x] =   "<div id=\"exceptionLineRow\">
                                <form action=\"include/delException.php\"  method=\"post\" id=\"delExc\" name=\"delExc\">
                                    <div id=\"exceptionDeleteButton\">
                                        <input name=\"X\" type=\"submit\" value=\"Delete\" title=\"Delete Exception\" class=\"genericPrimaryButtonSlim\">
                                    </div>
                                    <input size=\"1\" TYPE=\"text\" name=\"excId\" value=\"" . $row["schedExecId"] . "\" Style=\"visibility:hidden\">                                     
                                    $schedExcpIcon
                                    <div id=\"exceptionReason\">" . $row["Reason"] . "</div>
                                    <div id=\"exceptionFrom\">From: " . getHoursFromDate($row["timeFrom"], "u") . "</div>
                                    <div id=\"exceptionTo\">To " . getHoursFromDate($row["timeTo"], "u") . "</div>
                                </form>
                             </div><!--end-->";
            $x++;
        }
    }
    return $result;
}

function insertScheduleException($usrId, $qmUsr) {
    require_once("connection.php");
    $conn = connectToDB();
    $params = array(5);
    $scheduleType = trim($_POST['schedExecList']);
    //echo "</br> Time From H: ".			
    $timeFromH = trim($_POST["finh"]);
    //echo "</br> Time From M: ".			
    $timeFromM = trim($_POST["finmin"]);
    //echo "</br> Time to H: ".			
    $timeToH = trim($_POST["tinh"]);
    //echo "</br> Time To M: ".			
    $timeToM = trim($_POST["tinmin"]);
    //echo "</br> Excepetion Date From: ". 	
    $excepDateFrom = trim($_POST["datepickerFrom"]);
    //echo "</br> Excepetion Date To: ". 		
    $excepDateTo = trim($_POST["datepickerTo"]);
    //converts the true or false into 1 or 0
    //echo "</br> All Day Converted: ".
    $allDay = convertChecks_TrueOrFalse($_POST["allDay"]);
    /* //////////remove this section - start
      echo "</br></br>Day : " .date("d",strtotime($excepDateFrom));
      echo "</br>Month : " .date("m",strtotime($excepDateFrom));
      echo "</br>Year : " .date("Y",strtotime($excepDateFrom));
      //////////remove this section - end */
    //creates the query, depending of the AllDay checkbox is true or not.
    if (isset($row["usrId"]))
    $queryShbyUsr = "EXEC uspAvailables @userid=" . $row["usrId"];
    if (isset($getSchByUsr))
    $getSchByUsr = sqlsrv_query($conn, $queryShbyUsr, $params);



    $queryReceiveCases = "select receiveCases from ScheduleExcepTypesList where schExcepTypeId =" . $scheduleType;
    //echo $query;

    $getreceiveCases = sqlsrv_query($conn, $queryReceiveCases, $params);
    if ($getreceiveCases === false) {
        die(FormatErrors(sqlsrv_errors()));
    }
    if (isset($rowType))
    if ($rowType === sqlsrv_fetch_array($getreceiveCases)) {
        $receiveCases = $rowType['receiveCases'];
    }

    //echo $receiveCases;
    if ($allDay === 0) {
        // creates the string for the date of the field in the DB "timeFrom"
        $timeFrom = date("Y-m-d\ H:i:s.u\ P", mktime($timeFromH, $timeFromM, 0, date("m", strtotime($excepDateFrom)), date("d", strtotime($excepDateFrom)), date("Y", strtotime($excepDateFrom))));
        // creates the string for the date of the field in the DB "timeTo"
        $timeTo = date("Y-m-d\ H:i:s.u\ P", mktime($timeToH, $timeToM, 0, date("m", strtotime($excepDateFrom)), date("d", strtotime($excepDateFrom)), date("Y", strtotime($excepDateFrom))));
        //echo "</br> Fecha Creada timeTo: " . $timeTo;
        //echo "</br> Fecha Creada timefrom: " . $timeFrom;		

        $query = "EXEC uspInsertScheduleException " . $usrId . ", '" . $scheduleType . "', '" . $timeFrom . "' ,'" . $timeTo . "' ,'" . $allDay . "', '" . $receiveCases . "'," . $qmUsr;
    } else if ($allDay === 1) {
        // creates the string for the date of the field in the DB "timeFrom"
        $timeFrom = date("Y-m-d\ H:i:s.u\ P", mktime(00, 00, 01, date("m", strtotime($excepDateFrom)), date("d", strtotime($excepDateFrom)), date("Y", strtotime($excepDateFrom))));
        // creates the string for the date of the field in the DB "timeTo"
        $timeTo = date("Y-m-d\ H:i:s.u\ P", mktime(23, 59, 59, date("m", strtotime($excepDateTo)), date("d", strtotime($excepDateTo)), date("Y", strtotime($excepDateTo))));
        if (isset($query))
        $query = "EXEC uspInsertScheduleException " . $usrId . ", '" . $scheduleType . "', '" . $timeFrom . "' ,'" . $timeTo . "' ,'" . $allDay . "', '" . $receiveCases . "'," . $qmUsr;
    }
    //echo  "</br> Query: ". $query;

    if (isset($query))
    $insertScheduleExcep = sqlsrv_query($conn, $query, $params);
    if (isset($insertScheduleExcep))
    if ($insertScheduleExcep == false) {
        echo "No schedule Exceptions inserted";
        echo die(FormatErrors(sqlsrv_errors()));
    } else {
        echo "Schedule Exception Created Succesfully";
    }
}

function updateSchedule($usrId, $roleId) {

    for ($x = 1; $x < 8; $x++) {
        echo "</br> Day " . $x;
        //Time in
        //echo "</br> Time in H: " . 
        $InH = trim($_POST['InH' . $x]);
        //echo "</br> Time in M: ". 
        $InMin = trim($_POST['InMin' . $x]);
        //Time Out
        //echo "</br> Time Out H ". 
        $OutH = trim($_POST['OutH' . $x]);
        //echo "</br> Time Out M: ". 
        $OutMin = trim($_POST['OutMin' . $x]);
        //Time Lunch in
        //echo "</br> Lunch in H :" . 
        $LInH = trim($_POST['LInH' . $x]);
        //echo "</br> Lunch in M :" . 
        $LInMin = trim($_POST['LInMin' . $x]);
        //Time Lunch Out
        //echo "</br> Lunch Out H :" . 
        $LOutH = trim($_POST['LOutH' . $x]);
        //echo "</br> Lunch Out M :" . 
        $LOutMin = trim($_POST['LOutMin' . $x]);
        //echo "</br>";
        //construct the time values
        echo "</br> Time in:" .
        $timeIn = $InH . ":" . $InMin . ":00";
        echo "</br> Time Out:" .
        $timeOut = $OutH . ":" . $OutMin . ":00";
        echo "</br> Time Lunch:" .
        $timeLunchIn = $LInH . ":" . $LInMin . ":00";
        echo "</br> time Lunch End:" .
        $timeLunchEnd = $LOutH . ":" . $LOutMin . ":00";
        echo "</br>";

        require_once("connection.php");
        $conn = connectToDB();
        $params = array(5);
        echo $query = "EXEC uspUpdateSchedule '" . $usrId . "', '" . $roleId . "','" . $x . "','" . $timeIn . "','" . $timeOut . "','" . $timeLunchIn . "','" . $timeLunchEnd . "'";

        $updateSchedule = sqlsrv_query($conn, $query, $params);
        if ($updateSchedule == false) {
            echo "No update was made to the Schedule";
            echo die(FormatErrors(sqlsrv_errors()));
        } else {
            echo "Schedule succesfully updated";
        }
    }//end FOR	
}

function getRoleDescription($roleId) {
    require_once("connection.php");
    $conndb = connectToDB(); //Connection stablished		
    $params = array(5);
    $sqlquery = "exec uspGetRoleDescription " . $roleId;
    $getRoleDescr = sqlsrv_query($conndb, $sqlquery, $params);
    if ($getRoleDescr === false) {
        die(FormatErrors(sqlsrv_errors()));
    }
    $row = sqlsrv_fetch_array($getRoleDescr);
    $roleDes = $row["roleDesc"];
    return $roleDes;
}

function changePassword($userId) {
    $password = trim($_POST['passConfirm_txt']);
    $pwdsha1 = sha1($password);

    require_once("connection.php");
    $conndb = connectToDB(); //Connection stablished		
    $params = array(5);
    $sqlquery = "exec uspUpdatePassword '" . $userId . "','" . $pwdsha1 . "'";
    $updateResults = sqlsrv_query($conndb, $sqlquery, $params);

    if (sqlsrv_fetch_array($updateResults) == 1) {
        //$message= ("The password changed succesfully");
        closeDBConnetion();
        return false;
    }
}

function GetFDoM($dateThis) {
// Function for returning the First Day of the Month for the test dateThis value
    $retVal = NULL;
    if (is_numeric($dateThis)) {
        $dateSoM = strtotime(date('Y', $dateThis) . '-' . date('m', $dateThis) . '-01');
        if (is_numeric($dateSoM)) {
            $retVal = $dateSoM;
        }
    }
    return $retVal;
}

function GetLDoM($dateThis) {
// Function for returning the Last Day of the Month for the test dateThis value
    $retVal = NULL;
    if (is_numeric($dateThis)) {
        $dateSoM = strtotime(date('Y', $dateThis) . '-' . date('m', $dateThis) . '-01');
        $dateCog = strtotime('+1 month', $dateSoM);
        $dateEoM = strtotime('-1 day', $dateCog);
        if (is_numeric($dateEoM)) {
            $retVal = $dateEoM;
        }
    }
    return $retVal;
}

function productValue($product) {
    require_once("connection.php");
    $conndb = connectToDB(); //Connection stablished		
    $params = array(5);
    $sqlquery = "select caseValue from Product where productId='" . $product . "'";
    $updateResults = sqlsrv_query($conndb, $sqlquery, $params);

    if ($updateResults === false) {
        die(FormatErrors(sqlsrv_errors()));
    }
    $row = sqlsrv_fetch_array($updateResults);
    return $row["caseValue"];
}

// *******************************
// Added by dotb@hp.com
// Date: 15-Feb-2013
//
// function getTimeZone
// 
// Returns the timezone defined for the user, in case the user doesn't has a timezone defines
// returns the browser detected timezone
//

function getUserTimeZone () {
    require_once("connection.php");
    $conndb = connectToDB(); //Connection stablished		
    $params = array(5);
    $sqlquery = "select timeZone from UserDetails where usrName='" . $_SESSION['username'] . "'";
    $updateResults = sqlsrv_query($conndb, $sqlquery, $params);

    if ($updateResults === false) {
        die(FormatErrors(sqlsrv_errors()));
    }
    $row = sqlsrv_fetch_array($updateResults);
          
    return $row["timeZone"];                
}

// *******************************
// Added by dotb@hp.com
// Date: 19-Feb-2013
//
// function getAllTimeZones
// 
// Returns an Array with all the timezones around the world to
// populate user profile admin screen 
//
function getAllTimeZones(){
    static $regions = array(
    'Africa' => DateTimeZone::AFRICA,
    'America' => DateTimeZone::AMERICA,
    'Antarctica' => DateTimeZone::ANTARCTICA,
    'Asia' => DateTimeZone::ASIA,
    'Atlantic' => DateTimeZone::ATLANTIC,
    'Australia' => DateTimeZone::AUSTRALIA,
    'Europe' => DateTimeZone::EUROPE,
    'Indian' => DateTimeZone::INDIAN,
    'Pacific' => DateTimeZone::PACIFIC
    );

    foreach ($regions as $mask) {
        $tzlist[] = DateTimeZone::listIdentifiers($mask);
    }
    
    return $tzlist;    
}

//My profile page -- Return all the supported products by the engineer in a list format
function getAllSupportedProductsOnMyProfile($usrId) {
    $conndb = connectToDB(); //Connection stablished    
    $sqlquery = "exec uspGetProductsForProfile " . $usrId;
    $params = array(5);
    $getAllSupportedProductOnMyProfile = sqlsrv_query($conndb, $sqlquery, $params);
    if ($getAllSupportedProductOnMyProfile === false) {
        echo "No Products were found";
    }

    $x = 0;
    echo "<ul>";
    while ($row = sqlsrv_fetch_array($getAllSupportedProductOnMyProfile)) {
        echo "<li>".$row["productDesc"]."</li>";
        $x++;
    }
    echo "</ul>";
}
?>