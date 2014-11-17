<?php
/**
 * @param        $mysqli
 * @param        $fileName
 * @param string $baseDate
 * @param int    $totalSeats
 * @param int    $numOfDays
 */
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
		for($i = 0; $i < $numOfDays; ++$i) {
			$baseDateDep = DateTime::createFromFormat("Y-m-d H:i:s", $baseDate . " " . $output[3]);
			echo "baseDeptObj";
			var_dump($baseDateDep);

			$newDateDepStr = $baseDateDep->format("Y-m-d H:i:s");
			//echo "newDeptStr";
			//var_dump($newDateDepStr);

			$baseDateArr = DateTime::createFromFormat("Y-m-d H:i:s", $baseDate . " " . $output[4]);
			echo "baseArrObj";
			var_dump($baseDateArr);

			$newDateArrStr = $baseDateArr->format("Y-m-d H:i:s");
			//echo "newArrStr";
			//var_dump($newDateArrStr);


			$wasClean = $statement->bind_param("ssssssdi", $output[0], $output[1], $output[2], $newDateDepStr,
				$newDateArrStr, $output[5], $output[6], $totalSeats);
			if($wasClean === false) {
				throw(new mysqli_sql_exception("Unable to bind parameters"));
			}
			if($statement->execute() === false) {
				throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
			}

			if(!empty($output[7])) {
				$baseDateDep = DateTime::createFromFormat("Y-m-d H:i:s", $baseDate . " " . $output[7]);

				$newDateDepStr = $baseDateDep->format("Y-m-d H:i:s");

				$baseDateArr = DateTime::createFromFormat("Y-m-d H:i:s", $baseDate . " " . $output[8]);

				$newDateArrStr = $baseDateArr->format("Y-m-d H:i:s");

				$wasClean = $statement->bind_param("ssssssdi", $output[0], $output[1], $output[2], $newDateDepStr,
					$newDateArrStr, $output[9], $output[10], $totalSeats);

				if($wasClean === false) {
					throw(new mysqli_sql_exception("Unable to bind parameters"));
				}
				if($statement->execute() === false) {
					throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
				}

				if(!empty($output[11])) {
					$baseDateDep = DateTime::createFromFormat("Y-m-d H:i:s", $baseDate . " " . $output[11]);

					$newDateDepStr = $baseDateDep->format("Y-m-d H:i:s");

					$baseDateArr = DateTime::createFromFormat("Y-m-d H:i:s", $baseDate . " " . $output[12]);

					$newDateArrStr = $baseDateArr->format("Y-m-d H:i:s");

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
			$baseDate = DateTime::createFromFormat("Y-m-d", $baseDate);

			echo "baseDate Obj Y-m-d";
			var_dump($baseDate);

			$baseDate->add(new DateInterval('P1D')); // P1D means a period of 1 day
			echo "baseDate + Interval";
			var_dump($baseDate);

			$baseDate = $baseDate->format('Y-m-d');
			//echo "baseDateStr Y-m-d";
			//var_dump($baseDate);
		}
	}
	fclose($filePointer);
	echo "Files were successfully inserted";

}
?>

