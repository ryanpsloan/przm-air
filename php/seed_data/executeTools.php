<?php
require_once("/var/www/html/przm.php");
require_once("tools.php");
$mysqli = MysqliConfiguration::getMysqli();
$numOfWeeks = 2;

$dateTimeObjWeekday = DateTime::createFromFormat("Y-m-d", "2015-02-09", new DateTimeZone('UTC'));
$dateTimeObjWeekend = DateTime::createFromFormat("Y-m-d", "2015-02-14", new DateTimeZone('UTC'));

for($i = 0; $i < $numOfWeeks; ++$i) {

	$baseDate = $dateTimeObjWeekday->format("Y-m-d");
	$fileName = "weekDayCsv.csv";
	$totalSeats = 20;
	readCSV($mysqli, $fileName, $baseDate, $totalSeats);
	echo "<p>$i weekDay seed data set to flight </p>";
	//---------------------------------------------------------------------------
	$baseDate = $dateTimeObjWeekend->format("Y-m-d");
	$fileName = "weekEndCsv.csv";
	$numDays = 2;
	readCSV($mysqli, $fileName, $baseDate, $totalSeats, $numDays);
	echo "<p>$i weekEnd seed data set to flight </p>";
	echo "***********************************************************************************************";
	$dateTimeObjWeekday->add(new DateInterval("P7D"));
	$dateTimeObjWeekend->add(new DateInterval("P7D"));
}
echo "<p>++++++++++++++++++++++++++++ END +++++++++++++++++++++++++++++++++++++</p>";
?>