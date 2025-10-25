
<div id="users">
    <div id="subMenu">
        <div id="opt1"><a href="javascript:newUser()">Create</a></div>
        <div id="opt2"><a href="javascript:modifyUser()">Modify</a></div>
        <div id="opt3">Enable</div>
    </div>
    <div id=logoUsers></div>
    <div id=tittleUsers><h4>Users Maintenance</h4></div>
    <!-- Create User Form-->
    <?php
    include("newUsr.php");
    ?>
    <!-- -------------------- -->
    <!-- Modify User Form-->
    <?php
    include("modUsr.php");
    ?>
    <!-- -------------------- -->
</div><!-- users -->
   