<?php
if(!isset($_SESSION)){ 
session_start(); 
} 
if(!isset($_SESSION['DBtoUse'])){
	header("Cache-Control: no-cache");
	//header('Location:../login.php');
}
require_once("connection.php");
$conn = connectToDB();
$query = "exec uspAllActiveUsers";
$params = array(5);
$getUsers = sqlsrv_query( $conn, $query, $params);
if ( $getUsers === false)
	{ die( FormatErrors( sqlsrv_errors() ) ); }
echo"<select id=\"engineerlst\" name=\"engineerlst\" onChange=\"fillModUsr()\">";
echo "<option value=\"\">-- Select Engineer --</option>";
while($row = sqlsrv_fetch_array($getUsers)){
	echo "<option value=\"". $row["usrName"] ."\">".$row["nameToDisplay"]."</option>";	
}/**While*/
echo "</select>";
/* Close the connection. */
closeDBConnetion();
?>
