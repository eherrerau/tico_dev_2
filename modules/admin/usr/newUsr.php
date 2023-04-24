
<form action="insertNewUsr.php" method="post" name="createUserF" style="display:block" > 
        <div id="tittleTab">Create User</div>
        <div id=dynamicForm>
            <div id="usrLbl">User Name:</div>
            <div id="usrTxt"><input name="txtUsrName" type="text" size="20" maxlength="20"></div>

            <div id="nameLbl">Name to Display:</div>
            <div id="nameTxt"><input name="txtDisplayName" type="text" size="20" maxlength="20"></div>
            
            <div id="mailLbl">Main e-mail:</div>
            <div id="mailTxt"><input name="txtMail" type="text" size="20" maxlength="20"></div>
            
            <div id="premierLbl">Premier</div>
            <div id="premierChk"><input name="premier" type="checkbox" ></div>
            
            <div id="activeLbl">Active</div>
            <div id="activeChk"><input name="active" type="checkbox" ></div>

            <div id="roles">
                <div id="rolesTittle">Roles</div> 
                <div id="rolesContent"><?php echo implode("<br>", getAllRoles()); ?> </div>
            </div>
            <div id="products">
                <div id="productsTittle">Products</div> 
                <div id="productsContent"><?php echo implode("<br>", getAllProductsOnMyProfile()); ?> </div>
            </div>
            <div id="button"><a href="insertNewUsr.php" onclick="javascript:newAjax('modules/admin/usr/insertNewUsr.php', 'createUserF');return false;">Create</a></div>
            
        </div>
    </form>
