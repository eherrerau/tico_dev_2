<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>Team Schedule</title>
        <link href="../assets/css/schedule.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <p>
            <?php
            if(!isset($_SESSION)){ 
                session_start(); 
            } 
            if (!isset($_SESSION['DBtoUse'])) {
                header("Cache-Control: no-cache");
                //header('Location:../login.php');
            }
            require_once("../include/connection.php"); //including the connection functions
            $todayDate = "2012-06-01"; // date("Y-m-d");
            $mes = date("m", strtotime($todayDate));
            $anho = date("Y", strtotime($todayDate));

            $conndb = connectToDB(); //Connection stablished
            //----------------------------------
//--------getting total days of month-------------
            $query = "select dbo.fnDaysInMonth('" . $todayDate . "') as totalDays";
            $params = array(5);
            $result = sqlsrv_query($conndb, $query, $params);
            if ($result === false) {
                die(FormatErrors(sqlsrv_errors()));
            }

            While ($row = sqlsrv_fetch_array($result)) { //For each different timeIn
                //$row["totalDays"] total days of the month	
                $totalDays = $row["totalDays"];
            }//While
//-------------------------------------------------------
//--------getting engineer list-------------
            echo "<div id=\"mainContentMonth\">";

            echo "<div id=\"rowByEngineer\">";
            echo "<div id=\"engineerName\" style=\"text-align:center; font-weight:bold;\">";
            echo "NAME";
            echo "</div> <!--engineerName-->";
            for ($i = 1; $i <= $totalDays; $i++) {
                echo "<div id=\"dayNum" . $i . "\" style=\"font-weight:bold;\">" . $i . "</div>";
            }
            echo "</div> <!--rowByEngineer-->";
            $query2 = "SELECT usrId, nameToDisplay FROM UserDetails WHERE active=1 order by nameToDisplay ";
            $params2 = array(5);
            $resultE = sqlsrv_query($conndb, $query2, $params2);
            if ($resultE === false) {
                die(FormatErrors(sqlsrv_errors()));
            }

            While ($rowEng = sqlsrv_fetch_array($resultE)) { //For each user
                echo "<div id=\"rowByEngineer\">";
                echo "<div id=\"engineerName\">";
                echo $rowEng["nameToDisplay"];
                echo "</div> <!--engineerName-->";

                for ($i = 1; $i <= $totalDays; $i++) {
                    $diaFecha = $anho . "-" . $mes . "-" . $i;
                    $weekDay = date('l', strtotime($diaFecha));
                    echo "<div id=\"dayNum" . $i . "\"";
                    if (($weekDay == "Saturday") or ($weekDay == "Sunday")) {
                        echo " style=\"background-color:#EBE6E6;\"";
                    }

                    echo " >";

                    //------------------------------------------------------------------


                    $query3 = "exec uspGetExpByDayByUsr " . $rowEng["usrId"] . ", '" . $diaFecha . "'";
                    $params3 = array(5);
                    $resultSch = sqlsrv_query($conndb, $query3, $params3);
                    if ($resultSch === false) {
                        die(FormatErrors(sqlsrv_errors()));
                    }
                    $numero = 0;
                    While ($rowSch = sqlsrv_fetch_array($resultSch)) { //For each different timeIn
                        //echo $rowSch['schExcepTypeId'];
                        $numero = $numero + 1;
                        if ($numero > 3) {
                            ?>
                        <style>
                            #mainContentMonth #rowByEngineer {
                                height: 25px;
                            }

                            #mainContentMonth #rowByEngineer {
                                height: 25px;
                            }
                            #mainContentMonth #rowByEngineer #engineerNameTitle{
                                height: 25px;
                            }
                        </style>

                        <?php
                    }
                    switch ($rowSch['schExcepTypeId']) {
                        case "1";
                            echo "<img src=\"../assets/media/images/vacations.gif\" width=\"10\" height=\"10\" alt=\"Vacations\" title=\"Vacations\" style=\"margin:1px;\">";
                            break;
                        case "2";
                            echo "<img src=\"../assets/media/images/training.gif\" width=\"10\" height=\"10\" alt=\"Vacations\" title=\"Training\" style=\"margin:1px;\">";
                            break;
                        case "3";
                            echo "<img src=\"../assets/media/images/sick.gif\" width=\"10\" height=\"10\" alt=\"Vacations\" title=\"Sick\" style=\"margin:1px;\">";
                            break;
                        case "4";
                            echo "<img src=\"../assets/media/images/permission.gif\" width=\"10\" height=\"10\" alt=\"Vacations\" title=\"permission\" style=\"margin:1px;\">";
                            break;
                        case "5";
                            echo "<img src=\"../assets/media/images/ooq.gif\" width=\"10\" height=\"10\" alt=\"Vacations\" title=\"OOQ\" style=\"margin:1px;\">";
                            break;
                        case "6";
                            echo "<img src=\"../assets/media/images/qm.gif\" width=\"10\" height=\"10\" alt=\"Vacations\" title=\"QM\" style=\"margin:1px;\">";
                            break;
                        case "7";
                            echo "<img src=\"../assets/media/images/2case.gif\" width=\"10\" height=\"10\" alt=\"Vacations\" title=\"Holiday\" style=\"margin:1px;\">";
                            break;
                        case "8";
                            echo "<img src=\"../assets/media/images/house.gif\" width=\"10\" height=\"10\" alt=\"Vacations\" title=\"WFH\" style=\"margin:1px;\">";
                            break;
                    }
                }
                //------------------------------------------------------------------
                echo "</div>";
            }
            echo "</div> <!--rowByEngineer-->";
        }//While
//-------------------------------------------------------


        echo "</div><!-- main content -->";
        closeDBConnetion();
        ?>
    </body>
</html>