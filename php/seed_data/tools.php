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
	//store baseDate
	$storeDate = $baseDate;
	while(($output = fgetcsv($filePointer, 0, ",")) !== false) {
		//var_dump($output);
		//reset baseDate
		$baseDate = $storeDate;
		//echo "storeDate outside of for loop";
		//var_dump($baseDate);
		for($i = 0; $i < $numOfDays; ++$i) {
			//echo "baseDate at top of for loop";
			//var_dump($baseDate);
			$baseDateDep = DateTime::createFromFormat("Y-m-d H:i:s", $baseDate . " " . $output[3],
				new DateTimeZone("America/Phoenix"));
			$baseDateDep->setTimezone(new DateTimeZone("UTC"));
			//echo "baseDeptObj";
			//var_dump($baseDateDep);

			$newDateDepStr = $baseDateDep->format("Y--d H:i:s");
			////echo "newDeptStr";
			//var_dump($newDateDepStr);

			$baseDateArr = DateTime::createFromFormat("Y-m-d H:i:s", $baseDate . " " . $output[4],
					new DateTimeZone("America/Phoenix"));
			//echo" baseArrObj Phoenix Timezone";
			//var_dump($baseDateArr);
			$baseDateArr->setTimezone(new DateTimeZone("UTC"));
			//echo "baseArrObj after setTimezone UTC";
			//var_dump($baseDateArr);

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
				$baseDateDep = DateTime::createFromFormat("Y-m-d H:i:s", $baseDate . " " . $output[7],
					new DateTimeZone("America/Phoenix"));
				//echo "7 baseDateDep Arizona";
				//var_dump($baseDateDep);
				$baseDateDep->setTimezone(new DateTimeZone("UTC"));
				//echo "7 baseDateDep UTC";
				//var_dump($baseDateDep);
				$newDateDepStr = $baseDateDep->format("Y-m-d H:i:s");
				//echo "7 newDeptStr";
				//var_dump($newDateDepStr);
				$baseDateArr = DateTime::createFromFormat("Y-m-d H:i:s", $baseDate . " " . $output[8],
					new DateTimeZone("America/Phoenix"));
				//echo "7 baseDateArr Phoenix";
				//var_dump($baseDateDep);
				$baseDateArr->setTimezone(new DateTimeZone("UTC"));
				//echo "7 baseDateDep";
				//var_dump($baseDateArr);
				$newDateArrStr = $baseDateArr->format("Y-m-d H:i:s");
				//echo "7 newArrStr";
				//var_dump($newDateArrStr);
				$wasClean = $statement->bind_param("ssssssdi", $output[0], $output[1], $output[2], $newDateDepStr,
						                                            $newDateArrStr, $output[9], $output[10], $totalSeats);

				if($wasClean === false) {
					throw(new mysqli_sql_exception("Unable to bind parameters"));
				}
				if($statement->execute() === false) {
					throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
				}

				if(!empty($output[11])) {
					$baseDateDep = DateTime::createFromFormat("Y-m-d H:i:s", $baseDate . " " . $output[11],
						new DateTimeZone("America/Phoenix"));
					$baseDateDep->setTimezone(new DateTimeZone("UTC"));
					////echo "11 baseDateDep";
					////var_dump($baseDateDep);
					$newDateDepStr = $baseDateDep->format("Y-m-d H:i:s");
					////echo "11 newDeptStr";
					//var_dump($newDateDepStr);
					$baseDateArr = DateTime::createFromFormat("Y-m-d H:i:s", $baseDate . " " . $output[12],
						new DateTimeZone("America/Phoenix"));
					$baseDateArr->setTimezone(new DateTimeZone("UTC"));
					////echo "11 baseDateArr";
					//var_dump($baseDateArr);
					$newDateArrStr = $baseDateArr->format("Y-m-d H:i:s");
					////echo "11 newArrStr";
					//var_dump($newDateArrStr);
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

				$baseDate = DateTime::createFromFormat("Y-m-d H:i:s", $baseDate." 00:00:00");

//				//echo "baseDate Obj Y-m-d 00:00:00";
//				//var_dump($baseDate);

				$baseDate->add(new DateInterval('P1D')); // P1D means a period of 1 day
				////echo "baseDate + Interval";
				//var_dump($baseDate);

				$baseDate = $baseDate->format('Y-m-d');
//				//echo "final transformation baseDateStr Y-m-d";
//				//var_dump($baseDate);

		}
	}
	fclose($filePointer);
	////echo "Files were successfully inserted";

}

function getTimeZone($cityCode){

	switch($cityCode){//or an enum couldve worked here
		case 'ABQ':

			break;
		case 'ATL':
			break;
		case 'DEN':
			break;
		case 'DFW':
			break;
		case 'DTW':
			break;
		case 'JFK':
			break;
		case 'LAX':
			break;
		case 'LGA':
			break;
		case 'MDW':
			break;
		case 'MIA':
			break;
		case 'ORD':
			break;
		case 'SEA':
			break;
		default:

	}

}
?>

