<?php
function readSchedule(&$mysqli,$fileName)
{
	if(($filePointer = fopen($fileName, "r")) === false){
		throw(new RuntimeException("Unable to Open $fileName"));
	}

	$query = "INSERT INTO schedule (origin, destination, duration, departure, arrival, flightNum, price) VALUES(?, ?, ?, ?, ?, ?,?)";
	$statement = $mysqli->prepare($query);

	if($statement === false) {
		throw(new mysqli_sql_exception("Unable to prepare statement"));
	}

	$row = 1;
	while(($output = fgetcsv($filePointer, 0, ",")) !== false) {
		$num = count($output);
		$wasClean = $statement->bind_param("ssi", $output[0], $output[1], $output[2]);
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

		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}
		if(empty($output[3]) === false) {


			if(empty($output[7] === false)) {

			}
		}


		// execute the statement

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
