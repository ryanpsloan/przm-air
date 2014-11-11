<?php
function readWeekdayCSV(&$mysqli,$fileName)
{





	if(($filePointer = fopen($fileName, "r")) === false){
		throw(new RuntimeException("Unable to Open $fileName"));
	}

	$query = "INSERT INTO weekdaySchedule (origin, destination, duration, departureTime, arrivalTime, flightNumber, price)
				VALUES(?, ?, ?, ?, ?, ?, ?)";
	$statement = $mysqli->prepare($query);
	if($statement === false) {
		throw(new mysqli_sql_exception("Unable to prepare statement"));
	}

	//$row = 1;
	while(($output = fgetcsv($filePointer, 0, ",")) !== false) {

		//$num = count($output);


		//$output[0, 1, 5, 9, 13] come in as strings and will be used as such for origin/destination/flight numbers

		//$output[2-4] and [7-8] and [11-12] come in as a string but have to be a number of hours to be used in calcs
		//except for $output[2] all of these also have to be added to the date of current loop to create a DATETIME

		$formatHourTime = "hh:mm";

		$duration = DateTime::createFromFormat($formatHourTime, $output[2]);
		$dateTimeDep1 = $date + DateTime::createFromFormat($formatHourTime, $output[3]);
		$dateTimeArr1 = $date + DateTime::createFromFormat($formatHourTime, $output[4]);
		$dateTimeDep2 = $date + DateTime::createFromFormat($formatHourTime, $output[7]);
		$dateTimeArr2 = $date + DateTime::createFromFormat($formatHourTime, $output[8]);
		$dateTimeDep3 = $date + DateTime::createFromFormat($formatHourTime, $output[11]);
		$dateTimeArr3 = $date + DateTime::createFromFormat($formatHourTime, $output[12});


		//$output[6,10,14] come in as a string but have to be an INT to calculate price
		$basePriceFlight1 = (int) $output[6];
		$basePriceFlight2 = (int) $output[10];
		$basePriceFlight3 = (int) $output[14]l




		$wasClean = $statement->bind_param("ssssssd", $output[0], $output[1], $output[2], $output[3],
			$output[4], $output[5], $output[6]);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		if(empty($output[7]) === false) {
			$wasClean = $statement->bind_param("ssssssd", $output[0], $output[1], $output[2], $output[7],
				$output[8], $output[9], $output[10]);
			if($wasClean === false) {
				throw(new mysqli_sql_exception("Unable to bind parameters"));
			}
			if($statement->execute() === false) {
				throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
			}

			if(empty($output[10]) === false) {
				$wasClean = $statement->bind_param("ssssssd", $output[0], $output[1], $output[2], $output[11],
					$output[12], $output[13], $output[14]);
				if($wasClean === false) {
					throw(new mysqli_sql_exception("Unable to bind parameters"));
				}
				if($statement->execute() === false) {
					throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
				}

			}
		}
		echo "Statement Executed, $num fields in line $row  \n";
		echo "--------------------------------------------- \n";
		for ($c=0; $c < $num; $c++) {
			echo "row: $row position: $c -> ".$output[$c]."\n";
		}
		//$row++;

	}
	if(!fclose($filePointer)){
		throw(new RuntimeException("Unable to close $fileName"));
	}
}
?>