<?php
	require_once("week_day_schedule_builder.php");
	require_once("week_end_schedule_builder.php");
	mysqli_report(MYSQLI_REPORT_STRICT);

	echo "TEST BEGIN";
	try {
		//localhost, userName, password, DBname
		$mysqli = new mysqli("localhost", "store_ryan", "trillpontlureactscala", "przm");
		readWeekdayCSV($mysqli, "weekDAY_CSV_export-Table_1.csv");
		readWeekendCSV($mysqli, "weekEND_CSV_export-Table_1.csv");
	} catch (Exception $exception){
			echo $exception->getMessage();
		}
	echo "TEST END";
?>