<?php
require_once("/home/gaster15/przm.php");
$mysqli = MysqliConfiguration::getMysqli();

$numOfWeeks = 104;

$dateTimeObjWeekday = DateTime::createFromFormat("Y-m-d", "2014-12-15");
$dateTimeObjWeekend = DateTime::createFromFormat("Y-m-d", "2014-12-20");

for($i = 0; $i < $numOfWeeks; ++$i) {

	require_once("tools.php");
	$baseDate = $dateTimeObjWeekday->format("Y-m-d");
	$fileName = "weekDayCsv.csv";
	$totalSeats = 20;
	readCSV($mysqli, $fileName, $baseDate, $totalSeats);
	echo "<p> weekDay seed data set to flight </p>";
	echo "***********************************************************************************************";

	$baseDate = $dateTimeObjWeekend->format("Y-m-d");
	$fileName = "weekEndCsv.csv";
	$numDays = 2;
	readCSV($mysqli, $fileName, $baseDate, $totalSeats, $numDays);
	echo "<p> weekEnd seed data set to flight </p>";

	$dateTimeObjWeekday->add(new DateInterval("P7D"));
	$dateTimeObjWeekend->add(new DateInterval("P7D"));
}
?>