<?php 
    $user = new user(); 
 
	$user->load(1);	
		//var_dump($_SESSION['username']);
	   
    if(isset($_GET['action']) && $_GET['action'] == 'do')
    {
        $user->setNameToDisplay($_POST['Name']) ;
		$user->setUsrMail($_POST['Mail']) ;
		$user->setPhoneExt($_POST['Phone']) ;
		$user->setTimeZone($_POST['Timezone']) ;
		$user->setBirthday(date($_POST['Bday']), 'Y-m-d');
        $user->write();
		
		  var_dump($user);
    }
    
?>
<div id="personalInfo">
    <div id="personalInfoTitle"><h1>Personal Information</h1></div>
    <div id="formul">
        <form action="profile.php?action=do" method="post" id="MyProfileForm" name="MyProfileForm">
            <div id="nameHolder">
                <div id="displayNameT" >Name:</div>
            <div id="displayName" >
                <!--<input name="Name" id="Name" type="text" size="20" value="<?php //echo $profileDetails[2]; ?>" autofocus="on" autocomplete="on" required="" />-->
                <input name="Name" id="Name" type="text" size="20" value="<?php echo $user->getNameToDisplay(); ?>" autofocus="on" autocomplete="on" required="" />
            </div>
            </div>                         
            <div id="mailHolder">
                <div id="displayMailT" >e-mail:</div>
                <div id="displayMail" >
                    <input name="Mail" id="Mail" type="email" value="<?php echo $user->getUsrMail(); ?>">
                </div>
            </div>
            <div id="timezoneHolder">
                <!--Added by dotb@hp.com - Feb 19th, 2013 -->            
                <div id="displayTimezoneT" >Timezone:</div>
                <div id="displayTimezone" >                              
                    <select name="Timezone" id="Timezone">                                  
                        <?php
                        // Get all timezones
                        $tzlist = getAllTimeZones();

                        foreach ($tzlist as $timezone) {
                            foreach ($timezone as $area) {
                                if ($area == $_SESSION['timezone'])
                                    echo "<option value='{$area}' selected='selected'>{$area}</option>";
                                else
                                    echo "<option value='{$area}'>{$area}</option>";
                            }
                        }
                        ?>                                                                        
                    </select>
                </div>  
                <!-- ************************************************** -->
            </div>            
            <div id="phoneHolder">
                <div id="displayPhoneT">Phone #:</div>
                <div id="displayPhone">
                    <input name="Phone" id="Phone" type="tel" value="<?php echo $user->getPhoneExt(); ?>" />
                </div>
            </div>                          
            <div id="bdayHolder">
                <div id="displayBdayT">Birthday: (DD-MM-YYYY)</div>
                <div id="displayBday">
                    <input name="Bday" id="Bday" type="text" value="<?php echo $user->getBirthday(); ?>" />
                </div>
            </div>
            <div id="teamHolder">
                <div id="displayTeamT">Team:</div>
                <div id="displayTeam"><label><?php echo $profileDetails[7]; ?></label></div>
            </div> 
            <div id="premierHolder">
                <div id="displayPremierT" >Premier Engineer:</div>
                <?php                
                if ($user->getPremier() == '1') {
                    echo "<div id=\"displayPremier\">Yes</div>";
                }else{
                    echo "<div id=\"displayPremier\">No</div>";
                }
                ?>
            </div>
            <div id="rolesHolder">
                <div id="displayRolesT" >Roles:</div>
                <div id="displayRoles" ><?php echo $roles = implode(", ", getRolesOnMyProfile($profileDetails[0])); ?></div>
            </div>                     
            <div id="productHolder">
                <div id="displayProductT" >Supported Products:</div>
                <div id="displayProduct" ><?php echo getAllSupportedProductsOnMyProfile($profileDetails[0]); ?> </div>
            </div>                             
            <div id="statusHolder">
                <div id="displayStatusT" >Actual status:</div>
                <div id="displayStatus" >My Status</div>
            </div>            
            <div id="personalInfoButton" class="genericPrimaryButtonSlim" onClick="javascript:document.MyProfileForm.submit();">
                <a href="personalInfo.php?action=do" onClick="return false;">Save</a>
            </div>
            <div id="chgpass" class="genericSecondaryButtonSlim" onClick="MM_openBrWindow('include/passwordChange.php', 'Password', 'width=550,height=250');">
                <a href="#">Change your password</a>
            </div>
        </form> 
    </div>                   
</div>