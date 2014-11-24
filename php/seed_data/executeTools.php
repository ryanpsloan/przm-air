<?php
require_once("/etc/apache2/capstone-mysql/przm.php");
$mysqli = MysqliConfiguration::getMysqli();

$numOfWeeks = 7;

$dateTimeObjWeekday = DateTime::createFromFormat("Y-m-d", "2014-12-01");
$dateTimeObjWeekend = DateTime::createFromFormat("Y-m-d", "2014-12-06");

for($i = 0; $i < $numOfWeeks; ++$i) {

	require_once("tools.php");
	$baseDate = $dateTimeObjWeekday->format("Y-m-d");
	$fileName = "weekDayCsv.csv";
	$totalSeats = 20;
	$numDays = 5;
	readCSV($mysqli, $fileName, $baseDate, $totalSeats, $numDays);
	echo "<p> weekDay seed data set to flight </p>";
	echo "***********************************************************************************************";

	$baseDate = $dateTimeObjWeekend->format("Y-m-d");
	$fileName = "weekEndCsv.csv";
	$totalSeats = 20;
	$numDays = 2;
	readCSV($mysqli, $fileName, $baseDate, $totalSeats, $numDays);
	echo "<p> weekEnd seed data set to flight </p>";

	$dateTimeObjWeekday->add(new DateInterval("P7D"));
	$dateTimeObjWeekend->add(new DateInterval("P7D"));
}
?>