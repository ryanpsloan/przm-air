<?php
require_once("/etc/apache2/capstone-mysql/przm.php");
$mysqli = MysqliConfiguration::getMysqli();

require_once("tools.php");
$baseDate = "2014-12-01";
$fileName = "weekDayCsv.csv";
$totalSeats = 25;
$numDays = 5;
readCSV($mysqli, $fileName,$baseDate, $totalSeats, $numDays);
echo"<p> weekDay seed data set to flight </p>";
echo"***********************************************************************************************";
/*
$baseDate = "2014-12-06";
$fileName = "weekEndCsv.csv";
$totalSeats = 25;
$numDays = 2;
readCSV($mysqli, $fileName, $baseDate, $totalSeats, $numDays);
echo"<p> weekEnd seed data set to flight </p>";
*/
?>