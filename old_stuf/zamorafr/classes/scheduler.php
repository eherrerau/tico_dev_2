<?php

class scheduler
#############################################
#											#
# Scheduler Class							#
# contact franklin@hp.com for more info.	#
#											#
#############################################
{
	
	var $__scheduleId;
	var $__dayOfTheWeek;
	var $__timeIn;
	var $__timeOff;
	var $__timeLunch;
	var $__timeLunchEnd;
	var $__roleId;
	
	
	function __construct() {
		// constructor
		$this->__scheduleId = -1;
		$this->__dayOfTheWeek = -1;
		$this->__timeIn = "-1";
		$this->__timeOff = "";
		$this->__timeLunch = "";
		$this->__timeLunchEnd = 1;
		$this->__roleId = "";
		//Database Connection
		$this->__dbConn = new database();
		$this->__dbConn->__construct();
	}
	
	function __destroy() {
		// Destructor
		$this->__scheduleId = -1;
		$this->__dayOfTheWeek = -1;
		$this->__timeIn = "-1";
		$this->__timeOff = "";
		$this->__timeLunch = "";
		$this->__timeLunchEnd = 1;
		$this->__roleId = "";
		//Database Connection
		$this->__dbConn->__destroy();
	}
	
	// Get - Set
	function getScheduleId() { return $this->__scheduleId; }
	function getDayOfTheWeek() { return $this->__dayOfTheWeek; }
	function getTimeIn() { return $this->__timeIn; }
	function getTimeOff() { return $this->__timeOff; }
	function getTimeLunch() { return $this->__timeLunch; }
	function getTimeLunchEnd() { return $this->__timeLunchEnd; }
	function getRoleId() { return $this->__roleId; }
	function getDbConn() { return $this->__dbConn; }
	//
	function setScheduleId($scheduleId) { $this->__scheduleId = $scheduleId; }
	function setDayOfTheWeek($dayOfTheWeek) { $this->__dayOfTheWeek = $dayOfTheWeek; }
	function setTimeIn($timeIn) { $this->__timeIn = $timeIn; }
	function setTimeOff($timeOff) { $this->__timeOff = $timeOff; }
	function setTimeLunch($timeLunch) { $this->__timeLunch = $timeLunch; }
	function setTimeLunchEnd($timeLunchEnd) { $this->__timeLunchEnd = $timeLunchEnd; }
	function setRoleId($roleId) { $this->__roleId = $roleId; }	
	function setDbConn($dbConn) { $this->__dbConn = $dbConn; }
	
	function loadUserSchedule($usrId,$roleId) {
	# Returns the entire schedule for a user on a given Role.  Method returns Boolean true if succesful, but actual data is loaded into the object and not returned.
	
		# Fetch all data from the Database
		if ($usrId == "" && $roleId == "") {return null;};
		
		$tmpResult = $this->getDbConn();
		$sql ="
		SELECT * FROM [{$this->getDbConn()->getDbname()}].[dbo].[Schedule] s
		RIGHT OUTER JOIN [{$this->getDbConn()->getDbname()}].[dbo].[ScheduleByUser] u
		ON s.scheduleId = u.scheduleId where u.usrId = {$usrId} and s.roleId = {$roleId} order by dayOfTheWeek asc
		";
		$result = $tmpResult->getSQLQuery($sql);
			
		if (!empty($result)) {
			
			$this->setScheduleId($result[0]['scheduleId']);
			$this->setDayOfTheWeek($result[0]['dayOfTheWeek']);
			$this->setTimeIn($result[0]['timeIn']);
			$this->setTimeOff($result[0]['timeOff']);
			$this->setTimeLunch($result[0]['timeLunch']);
			$this->setTimeLunchEnd($result[0]['timeLunchEnd']);
			$this->setRoleId($result[0]['roleId']);
		}
		// Dont return anything because this method is VOID and just sets variables from the database.
		return null;
	}
}
?>