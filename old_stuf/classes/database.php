<?php

class database {
#############################################
#											#
# Database Connection Class for SQL Server.	# 
# contact franklin@hp.com for more info.	#
#											#
#############################################

    var $__hostname;
    var $__dbname;
    var $__conn;
    var $__connectionInfo;

    function __construct() {
        // constructor de la conexion a la base de datos   	
        $this->__hostname = "(local)";
        //$this->__dbname = $this->getDbname(); 
        $this->__dbname = 'TICO_DB';
        $this->__connectionInfo = array("Database" => $this->getDbname());
        $this->__conn = sqlsrv_connect($this->getHostname(), $this->getConnectionInfo());
    }

    function setConn($conn) {
        $this->__conn = $conn;
    }

    function getConn() {
        return $this->__conn;
    }

    function setHostname($hostname) {
        $this->__hostname = $hostname;
    }

    function getHostname() {
        return $this->__hostname;
    }

    function setDbname($dbname) {
        $this->__dbname = $dbname;
    }

    function getDbname() {
        return $this->__dbname;
    }

    function setConnectionInfo($connectionInfo) {
        $this->__connectionInfo = $connectionInfo;
    }

    function getConnectionInfo() {
        return $this->__connectionInfo;
    }

    function setSQLQuery($query) {
        // Inserta una consulta, puede ser un Insert, Update o Delete.
        sqlsrv_query($this->getConn(), $query) or die(sqlsrv_errors());
    }

    function getSQLQuery($query) {
        // Devuelve una consulta, tiene que ser un Select
        $RS = sqlsrv_query($this->getConn(), $query) or die(sqlsrv_errors());

        // Esto es para armar el array con los datos, estos son los campos de la tabla
        if ($RS) {
            $row = sqlsrv_fetch_array($RS);
        }
        $result = array();
        $count = 0;
        if ($row) {
            do {
                $result[$count] = $row;
                $count++;
            } while ($row = sqlsrv_fetch_array($RS));
        }
        return $result;
    }

}

?>