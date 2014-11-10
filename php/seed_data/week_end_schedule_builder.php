<?php
function readWeekendCSV(&$mysqli,$fileName)
{
	if(($filePointer = fopen($fileName, "r")) === false){
		throw(new RuntimeException("Unable to Open $fileName"));
	}

	$query = "INSERT INTO weekendSchedule (origin, destination, duration, departure, arrival, flightNum, price) VALUES(?, ?, ?,
?, ?,
				?,?)";
	$statement = $mysqli->prepare($query);
	if($statement === false) {
		throw(new mysqli_sql_exception("Unable to prepare statement"));
	}

	$row = 1;
	while(($output = fgetcsv($filePointer, 0, ",")) !== false) {

		$num = count($output);
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
				$output[8], $output[8], $output[9]);
			if($wasClean === false) {
				throw(new mysqli_sql_exception("Unable to bind parameters"));
			}
			if($statement->execute() === false) {
				throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
			}

			if(empty($output[10]) === false) {
				$wasClean = $statement->bind_param("ssssssd", $output[0], $output[1], $output[2], $output[10],
					$output[11], $output[12], $output[13]);
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
		$row++;

	}
	if(!fclose($filePointer)){
		throw(new RuntimeException("Unable to close $fileName"));
	}
}
?>