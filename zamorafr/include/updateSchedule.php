<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['DBtoUse'])) {
    header("Cache-Control: no-cache");
    //header('Location:../login.php');
}
include_once("functions.php");
$profileDetails = getProfileValues(callSessionName());
?>
</head>
<body>
    <?php
    //echo $_POST['roleIdValue'];
    updateSchedule($profileDetails[0], $_POST['roleIdValue']);
    header('Location:../profile.php');
    ?>