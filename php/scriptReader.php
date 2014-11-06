<?php
function readSchedule(&$mysqli,$fileName)
{
	if(($filePointer = fopen($fileName, "r")) === false){
		throw(new RuntimeException("Unable to Open $fileName"));
	}

	$query = "INSERT INTO schedule (flightNumber, departureTime,
			arrivalTime, duration, dayOfWeek) VALUES(?, ?, ?, ?, ?)";
	$statement = $mysqli->prepare($query);

	if($statement === false) {
		throw(new mysqli_sql_exception("Unable to prepare statement"));
	}

	$row = 1;
	while(($output = fgetcsv($filePointer, 0, ",")) !== false) {

		$num = count($output);
		$wasClean = $statement->bind_param("sssii", $output[0], $output[1], $output[2], $output[3], $output[4]);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
		echo "Statement Executed, $num fields in line $row  \n";
		echo "--------------------------------------------- \n";
		echo "row: $row >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> \n";
		for ($c=0; $c < $num; $c++) {
			 echo "position: $c -> ".$output[$c]."\n";
		}
		$row++;
	}
	if(!fclose($filePointer)){
		throw(new RuntimeException("Unable to close $fileName"));
	}
}

function readAirport(&$mysqli,$fileName)
{
	if(($filePointer = fopen($fileName, "r")) === false){
		throw(new RuntimeException("Unable to Open $fileName"));
	}

	$query = "INSERT INTO airport (airportCode, airportDescription, airportSearchField)
				 VALUES(?, ?, ?)";
	$statement = $mysqli->prepare($query);

	if($statement === false) {
		throw(new mysqli_sql_exception("Unable to prepare statement"));
	}

	$row = 1;
	while(($output = fgetcsv($filePointer, 0, ",")) !== false) {

		$num = count($output);
		$wasClean = $statement->bind_param("sss", $output[0], $output[1], $output[2]);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
		echo "Statement Executed, $num fields in line $row  \n";
		echo "--------------------------------------------- \n";
		echo "row: $row >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> \n";
		for ($c=0; $c < $num; $c++) {
			echo "position: $c -> ".$output[$c]."\n";
		}
		$row++;
	}
	if(!fclose($filePointer)){
		throw(new RuntimeException("Unable to close $fileName"));
	}
}
?>
