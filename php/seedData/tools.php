<?php

function readCSV(&$mysqli,$fileName, $baseDate = "2014-12-01", $totalSeats = 25, $numOfDays = 5)
{
	if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
		throw(new mysqli_sql_exception("input is not a mysqli object"));
	}

	if(($filePointer = fopen($fileName, "r")) === false) {
		throw(new RuntimeException("Unable to Open $fileName"));
	}

	$query = "INSERT INTO flight (origin, destination, duration, departureDateTime, arrivalDateTime, flightNumber, price,
											totalSeatsOnPlane)
				VALUES(?, ?, ?, ?, ?, ?, ?, ?)";

	$statement = $mysqli->prepare($query);

	if($statement === false) {
		throw(new mysqli_sql_exception("Unable to prepare statement"));
	}

	while(($output = fgetcsv($filePointer, 0, ",")) !== false) {
		var_dump($output);

		$baseDateDep = DateTime::createFromFormat("Y-m-d H:i:s", $baseDate." ".$output[3]);
		echo "baseDeptObj";
		var_dump($baseDateDep);
		$baseDateArr = DateTime::createFromFormat("Y-m-d H:i:s", $baseDate." ".$output[4]);
		$newDateDepStr = $baseDateDep->format("Y-m-d H:i:s");
		$newDateArrStr = $baseDateArr->format("Y-m-d H:i:s");
		echo "newDeptStr";
		var_dump($newDateDepStr);

		echo "baseArrObj";
		var_dump($baseDateArr);

		echo "newArrStr";
		var_dump($newDateArrStr);
		$baseDate = DateTime::createFromFormat("Y-m-d", $baseDate);
		echo "baseDate";
		var_dump($baseDate);
		$baseDate = $baseDate->format("Y-m-d");
		echo"baseDateStr";
		var_dump($baseDate);
		for($i = 0; $i < $numOfDays; ++$i){
			$wasClean = $statement->bind_param("ssssssdi", $output[0], $output[1], $output[2], $newDateDepStr,
							$newDateArrStr, $output[5], $output[6], $totalSeats);
			if($wasClean === false) {
				throw(new mysqli_sql_exception("Unable to bind parameters"));
			}
			if($statement->execute() === false) {
				throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
			}
			$newDepDateObj = DateTime::createFromFormat("Y-m-d", $newDateDepStr);
			$newDepTimeObj = DateTime::createFromFormat("H;i:s", $newDateDepStr);
			$newDepDateObj++;
			$addedDateObj = DateTime::createFromFormat("Y-m-d", $newDepDateObj->format("Y-m-d")." "
			$newDateDepStr = $addedDateObj->format("Y-m-d H:i:s");

		}
		if(!empty($output[7])){
				$baseDateDep = DateTime::createFromFormat("Y-m-d H:i:s", $baseDate." ".$output[7]);
				$baseDateArr = DateTime::createFromFormat("Y-m-d H:i:s", $baseDate." ".$output[8]);
				$newDateDepStr = $baseDateDep->format("Y-m-d H:i:s");
				$newDateArrStr = $baseDateArr->format("Y-m-d H:i:s");
				$baseDate = DateTime::createFromFormat("Y-m-d",$baseDate);
				$baseDate = $baseDate->format("Y-m-d");

				$wasClean = $statement->bind_param("ssssssdi", $output[0], $output[1], $output[2], $newDateDepStr,
								$newDateArrStr, $output[9], $output[10], $totalSeats);

				if($wasClean === false) {
					throw(new mysqli_sql_exception("Unable to bind parameters"));
				}
				if($statement->execute() === false) {
					throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
				}
			}
		if(!empty($output[11])){
				$baseDateDep = DateTime::createFromFormat("Y-m-d H:i:s", $baseDate." ".$output[11]);
				$baseDateArr = DateTime::createFromFormat("Y-m-d H:i:s", $baseDate." ".$output[12]);
				$newDateDepStr = $baseDateDep->format("Y-m-d H:i:s");
				$newDateArrStr = $baseDateArr->format("Y-m-d H:i:s");
				$baseDate = DateTime::createFromFormat("Y-m-d",$baseDate);
				$baseDate = $baseDate->format("Y-m-d");

				$wasClean = $statement->bind_param("ssssssdi", $output[0], $output[1], $output[2], $newDateDepStr,
								$newDateArrStr, $output[13], $output[14], $totalSeats);
				if($wasClean === false) {
					throw(new mysqli_sql_exception("Unable to bind parameters"));
				}
				if($statement->execute() === false) {
					throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
				}

			}
	}
	fclose($filePointer);
	echo "Files were successfully inserted";
}

function setWeekDay(&$mysqli, $flightId, $dividingRow){
	if($flightId <= $dividingRow) {
		$weekDay = 1;
	}
	else{
		$weekDay = 0;
	}
		$query = "INSERT INTO flight (weekDay)
					VALUES(?) WHERE flightId = ?";

		$statement = $mysqli->prepare($query);

		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("ii", $weekDay, $flightId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
}

function multiplyDays(){


}
?>

