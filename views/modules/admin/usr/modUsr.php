<form action="xxx.php" method="post" name="modifyUserF" style="display:none"> 
        <div id="tittleTab">Modify User</div>
        <div id=dynamicForm>
            <div id="existusrLbl">User Name:</div>
            <div id="usrdrp"></div>
            <div id="usrLbl">User Name:</div>
            <div id="usrTxt"><input name="txtUsrName" type="text" size="20" maxlength="20"></div>

            <div id="nameLbl">Name to Display:</div>
            <div id="nameTxt"><input name="txtDisplayName" type="text" size="20" maxlength="20"></div>

            <div id="premierLbl">Premier</div>
            <div id="premierChk"><input name="premier" type="checkbox" value=""></div>

            <div id="roles">
                <div id="rolesTittle">Roles</div> 
                <div id="rolesContentM"></div>
            </div>
            <div id="products">
                <div id="productsTittle">Products</div> 
                <div id="productsContentM"><?php echo implode("<br>", getAllProductsOnMyProfile()); ?> </div>
            </div>
            <div id="button"><a href="" >Update</a></div>

        </div>
    </form>