<?php
function connectToGlobal() {
    //1. Define Variables for DB connection
    $myDBG = "TICO_Global";
    $serverName = "(local)";
    //2. Create a DB connection
    $connectionInfo = array("Database" => $myDBG);
//    echo $connectionInfo;
    $globalConn = sqlsrv_connect($serverName, $connectionInfo);
    if ($globalConn) {        
        return $globalConn;
    } else {
        echo " Connection could not be established.\n";
        die(print_r(sqlsrv_errors(), true) . "Error aca");
    }
}

function connectToDB(){
	//1. Define Variables for DB connection
	//$_SESSION['DBtoUse'] = "TICO_DB";
//        session_start();
    if(isset($_SESSION['DBtoUse'])){
        $myDB =$_SESSION['DBtoUse'];
}
//        $myDB = "TICO_DB_PC";
	$serverName = "(local)";
	//2. Create a DB connection
        if(isset($myDB)){
            $connectionInfo = array( "Database"=> $myDB);
        }	
        if (isset($connectionInfo)) {
            $conn = sqlsrv_connect($serverName,$connectionInfo);
        }
	if (isset($conn)) {
            if( $conn ){
		// echo "Connection established.\n";
		 return $conn;
		}
	else{
		 echo "Connection could not be established.\n";
		 die( print_r( sqlsrv_errors(), true)."ConnectToDB error ".$myDB.".");
		 }
        }
	
}

/* Close the connection. */
function closeDBConnetion(){
	if(isset($conn)){
	sqlsrv_close( $conn);
	}	
}

function execQuery($query){
//	include_once("include/connection.php");
	$conndb = connectToDB();//Connection stablished		
	$params = array(5);
	//$queryResults = sqlsrv_query( $conndb, $query, $params);
	/*$row = sqlsrv_fetch_array($queryResults);
		if ($queryResults==false){
			$row= "No results on this query";
			}
			else
			$row = sqlsrv_fetch_array($queryResults);		   
	//closeDBConnetion();	*/
	return sqlsrv_query( $conndb, $query, $params);
}
?>
