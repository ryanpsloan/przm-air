<?php
require_once("seed_data/weekDAY_CSV_export-Table_1.csv");
require_once("seed_data/week_day_schedule_builder");
	mysqli_report(MYSQLI_REPORT_STRICT);
	//localhost, userName, password, DBname
	echo "TEST BEGIN";
	try {
		$mysqli = new mysqli("localhost", "store_ryan", "trillpontlureactscala", "przm");
		readWeekdayCSV($mysqli, weekDAY_CSV_export - Table_1 . csv);
		readWeekendCSV($mysqli, weekEND_CSV_export - Table_1 . csv);
	} catch (Exception $exception){
			$exception->getMessage();
		}
	echo "TEST END";
?>