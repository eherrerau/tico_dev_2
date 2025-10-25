<?php

function myDBTrans($query) {
    require_once("../../../include/connection.php");
    include_once("../../../include/functions.php");
    //$userDetails = getProfileValues(callSessionName());
    $conn = connectToDB();
    
    $params = array(5);
    $request= sqlsrv_query($conn, $query, $params);
    if ($request === false) {
        die(var_dump(sqlsrv_errors()));
    }
    else{
    if ( $row=sqlsrv_fetch_array($request)){
       
    }else{
       
       return $row;}
    
}
closeDBConnetion();
}


function returnTrans($query) {
    require_once("../../../include/connection.php");
    include_once("../../../include/functions.php");
    //$userDetails = getProfileValues(callSessionName());
    $conn = connectToDB();
    
    $params = array(5);
    $request= sqlsrv_query($conn, $query, $params);
    if ($request === false) {
        die(var_dump(sqlsrv_errors()));
    }
    else{
    while ( $row=sqlsrv_fetch_array($request)){
        return $row["usrId"];
        
    }//else{
      //  $test= $row["usrId"];
      //  return $row;}
    
}
closeDBConnetion();
}
?>
