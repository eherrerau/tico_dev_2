<?php

include($_SERVER['DOCUMENT_ROOT'].'/zamorafr/classes/database.php');
include($_SERVER['DOCUMENT_ROOT'].'/zamorafr/classes/user.php');

$db = 'test';

echo '<hr>';
echo 'Creating Connection:';
echo '<hr>';

$conn = new database();
$conn->__construct();

var_dump($conn);

echo '<hr>';
echo 'Executing: INSERT INTO dbo.Team(TeamDesc,shortName) VALUES(\'zamorafr\',\'zamorafr\')';
echo '<hr>';

$conn->setSQLQuery("INSERT INTO dbo.Team(TeamDesc,shortName) VALUES('zamorafr','zamorafr')");

echo '<hr>';
echo 'Executing: SELECT * FROM dbo.Team';
echo '<hr>';

$result = $conn->getSQLQuery("SELECT * FROM dbo.Team");

echo '<hr>';
echo 'Formatting Output:';
echo '<hr>';

echo 'NOT DONE YET... SORRY ;-)';

echo '<hr>';
echo 'Destroying Connection:';
echo '<hr>';

$conn->__destroy();


echo '<hr>';
echo 'Destroying Object:';
echo '<hr>';

unset($conn);


echo '<hr>';
echo '<hr>';
echo 'Creating a User';
echo '<hr>';
$user = new user();
$user->getDbConn()->setDatabase('TICO_DB_NA');

$user->load('2018');

$user->setUsrName('Panki2');

$user->setBirthday('1978-10-23');

$user->write();



?>