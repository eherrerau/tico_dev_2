<?php
require_once("connection.php");
$conn = connectToGlobal();
$query = "EXEC uspTeamList";
$params = array(5);
$getTeams = sqlsrv_query($conn, $query, $params);
if ($getTeams === false) {
    die(FormatErrors(sqlsrv_errors()));
}
echo"<select id=\"teamDropmenu\" name=\"teamDropmenu\" tabindex=\"3\" title=\"Team\">";
while ($row = sqlsrv_fetch_array($getTeams)) {
//		echo "<option value=\"". $row["teamId"]."\">".$row["shortName"]."title=".$row["teamDesc"]."</option>";
    echo "<option value=" . $row["teamId"] . ">" . $row["shortName"] . "</option>";
}
echo "</select>";
closeDBConnetion();
?>