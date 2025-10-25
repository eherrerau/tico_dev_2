<?php

class user 
#############################################
#											#
# User Class 								# 
# contact franklin@hp.com for more info.	#
#											#
#############################################
{

	var $__usrId;
	var $__usrName;
	var $__hashedPass;
	var $__usrMail;
	var $__phoneExt;
	var $__teamID;
	var $__birthday;
	var $__nameToDisplay;
	var $__premier;
	var $__active;
	var $__globalUser;
	var $__timeZone;
	var $__dbconn;
	var $__roles;

	
	function __construct() {
		// constructor 
		$this->__usrId = -1;
		$this->__usrName = "";
		$this->__hashedPass = "-1";
		$this->__usrMail = "";
		$this->__phoneExt = "";
		$this->__teamID = 1;
		$this->__birthday = "";
		$this->__nameToDisplay = "No User Selected";
		$this->__premier = 0;
		$this->__active = 0;
		$this->__globalUser = 0;
		$this->__timeZone = "";
		$this->__roles = "";
	 	//Database Connection
	 	$this->__dbConn = new database();	
	 	$this->__dbConn->__construct();	
	}
	
 	function __destroy() {
 		// Destructor 
 		$this->__usrId = -1;
 		$this->__usrName = "";
 		$this->__hashedPass = "-1";
 		$this->__usrMail = "";
 		$this->__phoneExt = "";
 		$this->__teamID = -1;
 		$this->__birthday = "";
 		$this->__nameToDisplay = "No User Selected";
 		$this->__premier = 0;
 		$this->__active = -1;
 		$this->__globalUser = 0;
 		$this->__timeZone = "";
 		$this->__roles = "";
 		//Database Connection
 		$this->__dbConn->__destroy();
 	}
	
 	// Get - Set
 	function getUsrId() { return $this->__usrId; }
 	function getUsrName() { return $this->__usrName; }
 	function getHashedPass() { return $this->__hashedPass; }
 	function getUsrMail() { return $this->__usrMail; }
 	function getPhoneExt() { return $this->__phoneExt; }
 	function getTeamID() { return $this->__teamID; }
 	function getBirthday() { return $this->__birthday; }
 	function getNameToDisplay() { return $this->__nameToDisplay; }
 	function getPremier() { return $this->__premier; }
 	function getActive() { return $this->__active; }
 	function getGlobalUser() { return $this->__globalUser; }
 	function getTimeZone() { return $this->__timeZone; }
 	function getRoles() { return $this->__roles; }
 	function getDbConn() { return $this->__dbConn; }
	//
	function setUsrId($usrId) { $this->__usrId = $usrId; }
	function setUsrName($usrName) { $this->__usrName = $usrName; }
	function setHashedPass($hashedPass) { $this->__hashedPass = $hashedPass; }
	function setUsrMail($usrMail) { $this->__usrMail = $usrMail; }
	function setPhoneExt($phoneExt) { $this->__phoneExt = $phoneExt; }
	function setTeamID($teamID) { $this->__teamID = $teamID; }
	function setBirthday($birthday) { $this->__birthday = $birthday; }
	function setNameToDisplay($nameToDisplay) { $this->__nameToDisplay = $nameToDisplay; }
	function setPremier($premier) { $this->__premier = $premier; }
	function setActive($active) { $this->__active = $active; }
	function setGlobalUser($globalUser) { $this->__globalUser = $globalUser; }
	function setTimeZone($timeZone) { $this->__timeZone = $timeZone; }
	function setRoles($roles) { $this->__roles = $roles; }
	function setDbConn($dbConn) { $this->__dbConn = $dbConn; } 		
 	
 	
 	
 	function load($id) {
	# Loads data from the database to populate the User Class. Requires a usrID.
 		if ($id == "") {return null;};
 	
 		$tmpResult = $this->getDbConn();	
 		$result = $tmpResult->getSQLQuery("SELECT * FROM {$this->getDbConn()->getDbname()}.dbo.UserDetails WHERE usrId = {$id}");
 		
 		if (!empty($result)) {	
 			$this->setUsrId($result[0]['usrId']);
 			$this->setUsrName($result[0]['usrName']); 
 			$this->setHashedPass($result[0]['hashedPass']);
 			$this->setUsrMail($result[0]['usrMail']); 
 			$this->setPhoneExt($result[0]['phoneExt']);
 			$this->setTeamID($result[0]['teamID']); 
 			$this->setBirthday($result[0]['birthday']); 
 			$this->setNameToDisplay($result[0]['nameToDisplay']); 
 			$this->setPremier($result[0]['premier']);
 			$this->setActive($result[0]['active']);
 			$this->setGlobalUser($result[0]['globalUser']);
 			$this->setTimeZone($result[0]['timeZone']); 
 			
 			// load user Roles
 			$role_result = $tmpResult->getSQLQuery("SELECT * FROM {$this->getDbConn()->getDbname()}.dbo.RoleByUsr WHERE usrId = {$id}");
 			if (!empty($role_result)) {				
 				$i = 0;
 				foreach ($role_result as $role) { 					
 					$localRole[$i] = $role['roleId'];
 					$i++;
 				}
 				$this->setRoles($localRole); 				
 			}
 			else {
 				$this->setRoles("");
 			}
 		}
 		else {
 			$this->setUsrId(-1);
 		}
 	}
 	
 	function loadFromUsername($usrName) {
 		# Loads data from the database to populate the User Class. Requires a usrName.
 		if ($usrName == "") {return null;};
 	
 		$tmpResult = $this->getDbConn();
 		$result = $tmpResult->getSQLQuery("SELECT * FROM {$this->getDbConn()->getDbname()}.dbo.UserDetails WHERE usrName = '{$usrName}'");
 			
 		if (!empty($result)) {
 			$this->setUsrId($result[0]['usrId']);
 			$this->setUsrName($result[0]['usrName']);
 			$this->setHashedPass($result[0]['hashedPass']);
 			$this->setUsrMail($result[0]['usrMail']);
 			$this->setPhoneExt($result[0]['phoneExt']);
 			$this->setTeamID($result[0]['teamID']);
 			$this->setBirthday($result[0]['birthday']);
 			$this->setNameToDisplay($result[0]['nameToDisplay']);
 			$this->setPremier($result[0]['premier']);
 			$this->setActive($result[0]['active']);
 			$this->setGlobalUser($result[0]['globalUser']);
 			$this->setTimeZone($result[0]['timeZone']);
 		}
 		else {
 			$this->setUsrId(-1);
 		}
 	} 	
 	 	 	
 	function write(){
 	# Writes the information of this Class onto the database
 		$tmpResult = $this->getDbConn();
  		$sql = "SELECT * FROM {$this->getDbConn()->getDbname()}.dbo.UserDetails WHERE usrId = ". $this->getUsrId();
  		$result = $tmpResult->getSQLQuery($sql);
 		
  		if (empty($result) and $this->getUsrId() != "") { 	
  			$sql = "INSERT INTO	{$this->getDbConn()->getDbname()}.dbo.UserDetails(
 			usrName,
 			hashedPass,
			usrMail,
			phoneExt,
			teamID,
			birthday,
			nameToDisplay,
			premier,
			active,
			globalUser,
			timeZone)
			VALUES(
	 		'".$this->getUsrName()."',
	 		'".$this->getHashedPass()."',
	 		'".$this->getUsrMail()."',
	 		'".$this->getPhoneExt()."',
	 		".$this->getTeamID().",
	 		'".$this->getBirthday()."',
	 		'".$this->getNameToDisplay()."',
	 		'".$this->getPremier()."',
	 		".$this->getActive().",
	 		'".$this->getGlobalUser()."',
	 		'".$this->getTimeZone()."')";

 			$tmpResult->setSQLQuery($sql);
 		}
 		else {
 			$sql = "UPDATE {$this->getDbConn()->getDbname()}.dbo.UserDetails SET
			usrName = '".$this->getUsrName()."',
	 		hashedPass =  '".$this->getHashedPass()."',
	 		usrMail = '".$this->getUsrMail()."',
	 		phoneExt = '".$this->getPhoneExt()."',
	 		teamID = ".$this->getTeamID().",
	 		birthday = '".$this->getBirthday()."',
	 		nameToDisplay =	'".$this->getNameToDisplay()."',
	 		premier = '".$this->getPremier()."',
	 		active = ".$this->getActive().",
	 		globalUser = '".$this->getGlobalUser()."',
	 		timeZone = '".$this->getTimeZone()."'
			WHERE usrId = " . $this->getUsrId();
 			
 			$tmpResult->setSQLQuery($sql); 			
 		} 		
 	}
 	
	
}

?>