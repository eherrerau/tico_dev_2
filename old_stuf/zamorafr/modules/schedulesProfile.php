<div id="schedulesBox">
    <div id="schedMenus">
        <ul>
            <?php
            if (isProfile(3)) {
                echo "<li><a href=\"#\" onclick=\"getSchedule('../include/getSchedule.php',3)\">QM</a></li>";
            }
            if (isProfile(4)) {
                echo "<li><a href=\"#\" onclick=\"getSchedule('../include/getSchedule.php',4)\">Expert</a></li>";
            }
            if (isProfile(5)) {
                echo "<li><a href=\"#\" onclick=\"getSchedule('../include/getSchedule.php',5)\">Engineer</a></li>";
//                include_once '../include/getSchedule.php';
            }
            ?>
        </ul>
    </div>
    <div id="scheduleDays"></div>
</div>