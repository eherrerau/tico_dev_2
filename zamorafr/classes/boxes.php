<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of boxes
 *
 * @author david
 */

class boxes {
    
    var $smarty;   
    
    function boxes ($smarty, $groupID, $color, $showCounter){      
        $this->smarty = $smarty;        
    }
    
    function __destroy() {
        $this->smarty = "";        
    }
    
    function getAvailableMembers (){
        // Need to implement get users from DB
        // Order query by timein
        
        $scheduleIn = new TZSelector(date_format("10:00:00.0000000", "Y-m-d H:i"), $_SESSION['timezone']);
        $scheduleOut = new TZSelector(date_format("19:00:00.0000000", "Y-m-d H:i"), $_SESSION['timezone']);
        $scheduleLunch = new TZSelector(date_format("13:00:00.0000000", "Y-m-d H:i"), $_SESSION['timezone']);
        $scheduleLunchOff = new TZSelector(date_format("14:00:00.0000000", "Y-m-d H:i"), $_SESSION['timezone']);	
        
        
        $user1 = array ("usrId"=>4, "usrName"=>	"trejos", "usrMail"=>	"david.trejos@hp.com", "phoneExt"=> "", "teamID"=> 1, "birthday"=> "2013-03-04", "nameToDisplay"=> "David Trejos", "premier"=> 1, "active"=> 1, "globalUser"=> 1, "timeZone"=> "America/New_York");
        $members = array($user1);
        return $members;
    }
    
    function display (){
        $this->smarty->display("boxes.tpl");
    }
    
    
    
}

require_once("Smarty.class.php");

$smarty = new Smarty();

$smarty->setTemplateDir("../templates");
$smarty->setCompileDir("../templates_c");

//$smarty->testInstall();

$smarty->assign('counter', 2);


$box = new boxes($smarty);

$box->display();


?>
