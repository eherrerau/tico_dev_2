<?php
$user = new user();
$user->__construct();
$user->load($_SESSION['appUser']);

?>
<div id="header">
    <div id="headerImage"><img src= "../assets/media/images/HPR_White_RGB_150_SM.png" title="HP TICO"></div>
    <div id="headerTitleH1"><h1><?php echo SITE_TITLE;?></h1></div>
    <div id=rightInfo>
        <div id="userLogin"><?php echo $user->getNameToDisplay(); ?></div>
        <div id=QMbar>    	
            <div id="QMName"></div>
            <div id="QMLabel">QM on Duty:</div>                        
        </div>
    </div>
</div><!-- header --> 
<?php 
$user->__destroy();
?>    
