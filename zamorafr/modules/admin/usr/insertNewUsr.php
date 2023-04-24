<?php
$errors=False;
session_start();
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");
}
if(isset($_POST["premier"])) {
    $premier=1;
}
else{
    $premier=0;
}
if(isset($_POST["active"])) {
    $active=1;
}
else{
    $active=0;
}
  require_once("DBtransactions.php");
//include_once("functions.php");
$query = "exec uspInsertNewUsr " . $_POST['txtUsrName'] . ", " . $_POST['txtDisplayName'] . ", '" . $_POST["txtMail"] . "', " . $premier . ", " . $active . ", 0";
$row=myDBTrans($query);

if ($row) {
     echo "Failed to create user.";
} else {
    
   $query2="select * from UserDetails where usrMail= '".$_POST["txtMail"]."'";
   echo $query2;
    $userId=returnTrans($query2);
    //echo $userId;
    
    //Now each role selected is inserted
 if(!empty($_POST["roleChk"])) {
    foreach($_POST["roleChk"] as $checkR) {
        if(isset($checkR))   { 
            echo $checkR;
          $query3="exec uspInsertRoleByUsr ".$userId.", ".$checkR;
            if (myDBTrans($query3)) {
                $errors=True;
            } 
            //else {echo " Role created succesfully.";}                 
        }
    }
 }
 //Here each product selected is inserted
if(!empty($_POST["Products"])) {
    foreach($_POST["Products"] as $checkP) {
        if(isset($checkP))   { 
          $query4="exec uspInsertProductByUsr ".$userId.", ".$checkP;
            if (myDBTrans($query4)) {
                $errors=True;
            } 
           // else {echo " Product(s) created succesfully.";}                 
        }
    }
 }
//echo $_POST["roleChk"];
echo "User created succesfully.";
if ($errors){echo " <span style=\"color:#D7410B;\">There was an error saving a role and/or a product</span>";}
}

?>
