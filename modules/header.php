<?php
include_once("include/functions.php");
$userDetails = getProfileValues(callSessionName());

//session_start();
// Load objects - REMEMBER: Cannot modify headers after this line... cuz they will be already sent.
require_once($_SERVER['DOCUMENT_ROOT'].'/zamorafr/classes/database.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/zamorafr/classes/user.php');



// Load functions

// *******************************
// Added by dotb@hp.com
// Date: 14-Feb-2013
//
// Includes library for TZ support
//

require_once $_SERVER['DOCUMENT_ROOT'].'/include/includes.php';

// *******************************

?>
<div id="header">
    <div id="headerImage"><img src= "../assets/media/images/HPR_White_RGB_150_SM.png" title="HP TICO"></div>
    <div id="headerTitleH1"><h1>TICO-Tickets Control Center</h1></div>
    <div id=rightInfo>
        <div id="userLogin"><?php echo $userDetails[1]; ?></div>
        <div id=QMbar>    	
            <div id="QMName"></div>
            <div id="QMLabel">QM on Duty:</div>                        
        </div>
    </div>
</div><!-- header -->     
