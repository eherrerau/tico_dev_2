<?php
// Initial PHP Variables
ini_set('display_errors',true);

// Start Session 
session_start();
//

// Load Global Constants
define("APP_PATH",$_SERVER['DOCUMENT_ROOT']."/zamorafr");
define("SITE_TITLE","TICO: Tickets Control Center v2.0");
//

// Load Defaults and Security Measures.  Comment next line to bypass login redirection (for testing)
//if (!isset($_SESSION['appUser']) || $_SESSION['appUser'] == "") {header('Location:../login.php');}
//

// Load objects - REMEMBER: DO NOT add objects here that will output anything to the client, any echo this or print that will cause headers to be sent to the browser... breaking the entire site.
require_once(APP_PATH.'/classes/database.php');
require_once(APP_PATH.'/classes/user.php');
require_once(APP_PATH.'/classes/scheduler.php');
//

// Load functions -  REMEMBER: DO NOT add functions here that will output anything to the client, any echo this or print that will cause headers to be sent to the browser... breaking the entire site.
require_once(APP_PATH.'/include/functions.php');

// *******************************
// Added by dotb@hp.com
// Date: 14-Feb-2013
//
// Includes library for TZ support
//
require_once(APP_PATH.'/include/includes.php');
//

?>