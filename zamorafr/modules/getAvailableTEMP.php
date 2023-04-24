<?php

// build db
$db_getavailable = new database();
$db_getavailable->__construct();

$result_getavailable = $db_getavailable->getSQLQuery("SELECT usrId FROM [TICO_DB_PC].[dbo].[UserDetails]");

$i = 0;
$user_getavailable = new user();
$user_getavailable->__construct();
foreach ($result_getavailable as $user_getavailable_local) {
	
	$user_getavailable->load($user_getavailable_local['usrId']);
	
	// Load all roles for this user
	$roles_getavailable = $user_getavailable->getRoles();
	
	if(!empty($roles_getavailable[0]) && ($roles_getavailable[0] != 1 || $roles_getavailable[0] != 2) ) { 
		
		echo 'here';
		var_dump($roles_getavailable[0]);
	
		echo "User: ".$user_getavailable->getNameToDisplay();
		echo "'<br><br>'";
		
		// Load user's schedule
		$ii = 0;
		foreach ($roles_getavailable as $role_getavailable_local) {
			if ($role_getavailable_local != 1 && $role_getavailable_local != 2) {
				# Validates roles 1 and 2.  These roles are admin and manager and they dont have schedules.
				$userSchedule[$ii] = new scheduler();
				$userSchedule[$ii]->__construct();
				$userSchedule[$ii]->loadUserSchedule($user_getavailable->getUsrId(), $role_getavailable_local);
				// output
				
				echo "Role: '.$role_getavailable_local.'";
		//	echo " in: '.date_format($userSchedule[$ii]->getTimeIn(),'d-m-Y').'";
		//	echo " Lunch: '.date_format($userSchedule[$ii]->getTimeLunch(),'d-m-Y').'";
		//	echo " in (from lunch): '.date_format($userSchedule[$ii]->getTimeLunchEnd(),'d-m-Y').'";
		//	echo " out: '.date_format($userSchedule[$ii]->getTimeOff(),'d-m-Y').'";
			echo "'<br><br>'";
				
				
				$ii++;
			}
		}
	}
	$i++;
	
	//$finalvar->__destroy();
}



//var_dump($userSchedule);

?>