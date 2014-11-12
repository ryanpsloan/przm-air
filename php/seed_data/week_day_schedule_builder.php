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

		//$output[2-4] and [7-8] and [11-12] come in as a string but have to be an interval of hours to be used in calcs
		//except for $output[2] all of these also have to be added to the date of current loop to create a DATETIME

		//first, explode the string into an array to be able to turn it into a DateInterval object
		$explode2 	= explode(":",$output[2]);
		$explode3 	= explode(":",$output[3]);
		$explode4 	= explode(":",$output[4]);



		//second, use the exploded strings to create the DateInteval
		$duration 			= DateInterval::createFromDateString("$explode2[0] hour + $explode2[1] minutes");
		$departureTime1 	= DateInterval::createFromDateString("$explode3[0] hour + $explode3[1] minutes");
		$arrivalTime1 		= DateInterval::createFromDateString("$explode4[0] hour + $explode4[1] minutes");

		//third, add the relevant intervals to the current date in the loop to make a DATETIME object for each flight
		$dateTimeDep1 = $date->add($departureTime1);
		$dateTimeArr1 = $date->add($arrivalTime1);



		//FIXME
		//fourth, $output[6,10,14] come in as a float and need precision set to two decimal places for eventual conversion to dollar format
		//		$basePriceFlight1 = (int) $output[6];
		//		$basePriceFlight2 = (int) $output[10];
		//		$basePriceFlight3 = (int) $output[14];




		$wasClean = $statement->bind_param("ssssssd", $output[0], $output[1], $duration, $dateTimeDep1,
														$dateTimeArr1, $output[5], $output[6]);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		if(empty($output[7]) === false) {
			$explode7 	= explode(":",$output[7]);
			$explode8 	= explode(":",$output[8]);
			$departureTime2 	= DateInterval::createFromDateString("$explode7[0] hour + $explode7[1] minutes");
			$arrivalTime2 		= DateInterval::createFromDateString("$explode8[0] hour + $explode8[1] minutes");
			$dateTimeDep2 = $date->add($departureTime2);
			$dateTimeArr2 = $date->add($arrivalTime2);



			$wasClean = $statement->bind_param("ssssssd", $output[0], $output[1], $output[2], $duration, $dateTimeDep2,
				$dateTimeArr2, $output[9], $output[10]);

			if($wasClean === false) {
				throw(new mysqli_sql_exception("Unable to bind parameters"));
			}
			if($statement->execute() === false) {
				throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
			}



			if(empty($output[11]) === false) {
				$explode11	= explode(":",$output[11]);
				$explode12 	= explode(":",$output[12]);
				$departureTime3 	= DateInterval::createFromDateString("$explode11[0] hour + $explode11[1] minutes");
				$arrivalTime3 		= DateInterval::createFromDateString("$explode12[0] hour + $explode12[1] minutes");
				$dateTimeDep3 = $date->add($departureTime3);
				$dateTimeArr3 = $date->add($arrivalTime3);

				$wasClean = $statement->bind_param("ssssssd", $output[0], $output[1], $output[2], $duration, $dateTimeDep3,
					$dateTimeArr3, $output[13], $output[14]);
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