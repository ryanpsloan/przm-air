<?php
require_once("/etc/apache2/capstone-mysql/przm.php");
$mysqli = MysqliConfiguration::getMysqli();

require_once("tools.php");
/*$baseDate = "2014-12-01";
$fileName = "weekDay01.csv";
readCSV($mysqli, $fileName,$baseDate,25,5);
echo"<p> weekDay seed data set to flight </p><br>";*/


$baseDate = "2014-12-06";
$fileName = "weekEnd01.csv";
$totalSeats = 25;
$numDays = 2;
readCSV($mysqli, $fileName,$baseDate,$totalSeats,$numDays);
echo"<p> weekEnd seed data set to flight </p><br>";

?>