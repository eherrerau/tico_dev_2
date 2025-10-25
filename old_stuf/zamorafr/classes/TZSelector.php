<?php
/**************************************************
 * File: TZSelector.php
 * 
 * Author1: David Trejos <dotb@hp.com>
 * Author2: N/A
 * Date: Feb 11th, 2013
 * Release: x.x.x
 * Deprecated: N/A
 * External Libraries: pear/Date.php
 *  
 * Summary:
 * 
 * This file add functions to configure and show the timezone for each user,
 *
 ***************************************************/

require_once 'Date.php';

class TZSelector {
    
    // Currently hardcoded, needs to be taken from a configuration file!!!
    var $systemTZ = "America/Costa_Rica";
    
    var $convert;
    var $tz;
    
    function TZSelector($date, $tz) {
        $this->convert = new Date("$date");
        if (!isset($tz) || trim($tz) === '')
            $this->tz = $this->systemTZ;
        else
            $this->tz = $tz;
    }
    
    function getUserDate (){
        $this->convert->convertTZByID($this->tz);
        return $this->convert->format("%d-%m-%Y %H:%M");
    }
    
    function getUserTime (){
        $this->convert->convertTZByID($this->tz);
        return $this->convert->format("%H:%M");
    }
    
    function getSystemDate (){
        $this->convert->convertTZByID($this->systemTZ);
        return $this->convert->format("%d-%m-%Y %H:%M");
    }
    
    function getSystemTime (){ 
        $this->convert->convertTZByID($this->systemTZ);
        return $this->convert->format("%H:%M");
    }

}
?>