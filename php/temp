<?php
/**
 * Created by PhpStorm.
 * User: zwg2
 * Date: 11/10/14
 * Time: 1:34 PM
 *
 *
 * Accesses data in the two CSV files (determined by whether the given date is a weekday or weekend)
 * to assign a flightId to each flight on each day for users to be able to search flights
 */


//set start date for schedule
private $startDate = "2014-11-15 00:00:00";

//create date object from start date with standard mySQL format
private $formatDateTime = "Y-m-d H:i:s";
private $date = DateTimeImmutable::createFromFormat($formatDateTime, $startDate);

//set initial seats available on each plane to 20
private $initialTotalSeatsOnPlane = 20;

//open the "weekDayCsv.csv" to have ready for use during loops
if(($weekDayPointer = fopen("weekDayCsv.csv", "r")) === false){
	throw(new RuntimeException("Unable to Open 'weekDay CSV export-Table 1.csv'"));
}


//open the "weekEndCsv.csv" to have ready for use during loops
if(($weekEndPointer = fopen("weekEndCsv.csv", "r")) === false){
	throw(new RuntimeException("Unable to Open 'weekEnd CSV export-Table 1.csv'"));
}


//fixme: add full doc-bloc with parameters etc.
/**
 * increment by 1 day, run through schedule files in a loop, build flights from seed data CSVs
 * @param resource $mysqli pointer to mySQL connection, by reference
 * @param $weekDayPointer
 * @param $weekEndPointer
 * @param $date
 * @param $initialTotalSeatsOnPlane
 * @throws mysqli_sql_exception when mySQL related errors occur
 **/
function	buildFlights (&$mysqli, $weekDayPointer, $weekEndPointer, $date, $initialTotalSeatsOnPlane) {

	//first, create query template
	$query = "INSERT INTO flight (origin, destination, duration, departureDateTime, arrivalDateTime, flightNumber, price, totalSeatsOnPlane)
				VALUES(?, ?, ?, ?, ?, ?, ?,?)";
	$statement = $mysqli->prepare($query);
	if($statement === false) {
		throw(new mysqli_sql_exception("Unable to prepare statement"));
	}

	//run loop for 2 years worth of data, checking which schedule to pull from on each day
	for($i = 0; $i < 730; $i++) { //counter number is wrong 845

		//fixme use DateTime to reformat this as an  //won't this mean i have to recreate the object as a string?  ie less elegant?  why do this way?
		//get day of week for the date in the current loop
		$dayOfWeek = date("N",$date->getTimestamp());

		//"if date is weekday, then do following:"
		if($dayOfWeek < 6) {


			//go through each row of the relevant schedule and build all flights for this date
			while(($output = fgetcsv($weekDayPointer, 0, ",")) !== false) {

				//$output[0, 1, 5, 9, 13] come in as strings and will be used as such for origin/destination/flight numbers

				//$output[2-4] and [7-8] and [11-12] come in as a string but have to be an interval of hours to be used in calcs
				//except for $output[2], all of these also have to be added to the date of current loop to create a DATETIME

				//first, explode the string into an array to be able to turn it into a DateInterval object.  Start with the
				//defualt case of the first flight which is always populated.  Then do same for Flight 2 and 3 when they exist.


				/*

				$explode2 = explode(":", $output[2]);
				$explode3 = explode(":", $output[3]);
				$explode4 = explode(":", $output[4]);


				//second, use the exploded strings to create the DateInteval.  Duration will remain a DateInterval.
				$duration = DateInterval::createFromDateString("$explode2[0] hour + $explode2[1] minutes");
				$departureTime1 = DateInterval::createFromDateString("$explode3[0] hour + $explode3[1] minutes");
				$arrivalTime1 = DateInterval::createFromDateString("$explode4[0] hour + $explode4[1] minutes");

				//third, add the relevant intervals to the current date in the loop to make a DATETIME object for each flight
				$dateTimeDep1 = $date->add($departureTime1);
				$dateTimeArr1 = $date->add($arrivalTime1);

				//FIXME: price formatting
				//fourth, $output[6,10,14] come in as a float and need precision set to two decimal places for eventual conversion to dollar format
				//		$basePriceFlight1 = (int) $output[6];
				//		$basePriceFlight2 = (int) $output[10];
				//		$basePriceFlight3 = (int) $output[14];

				// First, explode the duration string into an array to be able to turn it into a DateInterval object to be used in calcs
				$explode2 = explode(":", $output[2]);

				//second, use the exploded string to create the DateInteval.  Duration will remain a DateInterval.
				$duration = DateInterval::createFromDateString("$explode2[0] hour + $explode2[1] minutes");

				*/


				// set index base counter for do while loop to 3 for first flight arrival field
				$a = 3;

				do 	{
					//set second counter for ease
					$b = $a+1;

					//CSV $output[0, 1, 5, 9, 13] come in as strings and will be used as such for origin/destination/flight numbers

					//$output[2-4] and [7-8] and [11-12] come in as a string but have to be an interval of hours to be used in calcs
					//except for $output[2], all of these also have to be added to the date of current outer loop to create a DATETIME,
					//then re-formated once again as a string to be re-inserted to mySQL

					// First, explode the string into an array to be able to turn it into a DateInterval object.
					// Start with the defualt case of the first flight which is always populated.  Then do same for Flight 2 and 3 when they exist.

						//fixme to be more elegant
					$explodeA = explode(":", $output[$a]);
					$explodeB = explode(":", $output[$b]);


					//second, use the exploded strings to create the DateInteval.
					$departureTime = DateInterval::createFromDateString("$explodeA[0] hour + $explodeA[1] minutes");
					$arrivalTime = DateInterval::createFromDateString("$explodeB[0] hour + $explodeB[1] minutes");

					//third, add the relevant intervals to the current date in the loop to make a DATETIME object for each flight
					$dateTimeDep = $date->add($departureTime);
					$dateTimeArr = $date->add($arrivalTime);

					//convert all DateTime and DateInteval Objects to strings for re-insertion //fixme: don't we need them in DateInterval format for future math calculations?


					//array to hold all results//fixme: how do we build to an array before inserting

					$wasClean = $statement->bind_param("ssssssdi", $output[0], $output[1], $output[2], $dateTimeDep,
						$dateTimeArr, $output[$a+2], $output[$a+3], $initialTotalSeatsOnPlane);


					if($wasClean === false) {
						throw(new mysqli_sql_exception("Unable to bind parameters"));
					}
					if($statement->execute() === false) {
						throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
					}

					//increment counter to index of the next flight departure
					$a = $a + 4;

				} while (empty($output[$a]) !== false || $output[$a] !== false ); //FIXME: ask whether need both



				/*

					//fixme: above changes compared to below include: do all the ifs as a do while loop; change datetime objects to a string to insert
					//check for flight 2 on seed row and insert if exists:
						if(empty($output[7]) === false) {
							//repeat steps from default case for Flight 2
							$explode7 = explode(":", $output[7]);
							$explode8 = explode(":", $output[8]);
							$departureTime2 = DateInterval::createFromDateString("$explode7[0] hour + $explode7[1] minutes");
							$arrivalTime2 = DateInterval::createFromDateString("$explode8[0] hour + $explode8[1] minutes");
							$dateTimeDep2 = $date->add($departureTime2);
							$dateTimeArr2 = $date->add($arrivalTime2);

							$wasClean = $statement->bind_param("ssssssdi", $output[0], $output[1], $duration, $dateTimeDep2,
								$dateTimeArr2, $output[9], $output[10], $initialTotalSeatsOnPlane);

							if($wasClean === false) {
								throw(new mysqli_sql_exception("Unable to bind parameters"));
							}
							if($statement->execute() === false) {
								throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
							}


							//check for flight 3 on seed row and insert if it exists:
							if(empty($output[11]) === false) {
								//repeat steps from default case for Flight 3
								$explode11 = explode(":", $output[11]);
								$explode12 = explode(":", $output[12]);
								$departureTime3 = DateInterval::createFromDateString("$explode11[0] hour + $explode11[1] minutes");
								$arrivalTime3 = DateInterval::createFromDateString("$explode12[0] hour + $explode12[1] minutes");
								$dateTimeDep3 = $date->add($departureTime3);
								$dateTimeArr3 = $date->add($arrivalTime3);

								$wasClean = $statement->bind_param("ssssssdi", $output[0], $output[1], $duration, $dateTimeDep3,
																				$dateTimeArr3, $output[13], $output[14], $initialTotalSeatsOnPlane);
								if($wasClean === false) {
									throw(new mysqli_sql_exception("Unable to bind parameters"));
								}
								if($statement->execute() === false) {
									throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
								}

							}
						}
				*/
			}

			//repeat of code above with $weekEndPointer
		} else if($dayOfWeek === 6 || $dayOfWeek === 7) {
			//fixme: repeat code above, change file name to $weekEndPointer

		} else {
			throw(new Exception("DayOfWeek returned an unmatched value"));
		}

		//add 1 day to immutable $date object
		$loopByDay = DateInterval::createFromDateString("1 day");
		$date=$date->add ($loopByDay);

	}

	fclose($weekDayPointer);
	fclose($weekEndPointer);
}

//end function
//
//
//
//
//
//
//
//
//
//
//

/*//
//$num = count($output);


	//$date->add(new DateInterval('P1D'));

	// create second query template to insert
	$query2 = "INSERT INTO flight (flightId, origin, destination, duration, departureTime, arrivalTime,
																flightNumber, price, totalSeatsOnPlane) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$statement2 = $mysqli->prepare($query2);
	if($statement2 === false) {
		throw(new mysqli_sql_exception("Unable to prepare statement"));
	}

	// bind the member variables to the place holders in the template
	$wasClean2 = $statement2->bind_param("issssssii", $flightId, $row["origin"], $row["destination"], $row["duration"],
		$row["departureTime"], $row["arrivalDateTime"], $row["flightNumber"],
		$row["price"], $totalSeatsOnPlane);

	if($wasClean2 === false) {
		throw(new mysqli_sql_exception("Unable to bind parameters"));
	}

	// execute the statement
	if($statement2->execute() === false) {
		throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
	}



	// create second query template to insert
				$query2 = "INSERT INTO flight (flightId, origin, destination, duration, departureTime, arrivalTime,
																flightNumber, price, totalSeatsOnPlane) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$statement2 = $mysqli->prepare($query2);
				if($statement2 === false) {
					throw(new mysqli_sql_exception("Unable to prepare statement"));
				}

				// bind the member variables to the place holders in the template
				$wasClean2 = $statement2->bind_param("issssssii", $flightId, $row["origin"], $row["destination"], $row["duration"],
					$row["departureTime"], $row["arrivalDateTime"], $row["flightNumber"],
					$row["price"], $totalSeatsOnPlane);

				if($wasClean2 === false) {
					throw(new mysqli_sql_exception("Unable to bind parameters"));
				}

				// execute the statement
				if($statement2->execute() === false) {
					throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
				}










	// convert the associative array to a Flight
			if($row !== null) {
				try {
					$flight = new Flight ($flightId, $row["origin"], $row["destination"], $row["duration"], $row["departureTime"],
						$row["arrivalDateTime"], $row["flightNumber"],$row["price"], $totalSeatsOnPlane);
				} catch(Exception $exception) {
					// if the row couldn't be converted, rethrow it
					throw(new mysqli_sql_exception("Unable to convert row to Flight", 0, $exception));
				}

				// if we got here, the Flight is good - return it
				return ($flight);
			} else {
				// 404 User not found - return null instead
				return (null);
			}
		}


			insert flightId, $date, everything on row $i of tableWeekDaySchedule;

				$flightId++;

			}
	}
	else for ($i=0,$i< count(tableWeekEndSchedule), $i++) {

		insert flightId, $date, everything on row $i of tableWeekEndSchedule;

				$flightId++;
		}

	$date++;

}


	//CREATE QUERY TEMPLATE
	$query = "SELECT origin, destination, duration, departureTime, arrivalTime, flightNum, price
					FROM weekdaySchedule WHERE weekdayScheduleId = ? ";
	$statement = $mysqli->prepare($query);
	if($statement === false) {
		throw(new mysqli_sql_exception("Unable to prepare statement"));
	}
	$i = 0;
	do {


		//bind the profileId to the place holder in the template
		$wasClean = $statement->bind_param("i", $i);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		//execute statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		//get result from the SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("Unable to get result set"));
		}

		$row = $result->fetch_assoc();

		if(empty($row) === false) {
			INSERT INTO flight(flightId, origin, destination, etc) VALUES(?, ? , ?)

			bind_params("iss", null, $row['origin'], $row['destination'], $row['duration'] ['departureTime']);
		}
		$i++;
	} while ($row !== null);



















	$format = "Y-m-d";
	$date = DateTime::createFromFormat($format, $startDate);

	for($i=0; $i<730; $i++) {


	}




























		ini_set('date.timezone', 'Europe/Lisbon');

		$cal = new IntlGregorianCalendar(NULL, 'en_US');
		$cal->set(2013, 6, 7); // a Sunday

		var_dump($cal->isWeekend()); // true

		$date = 2014-12-01;//php has a function which will tell you the day of week
		if ($date = weekday) {

			for($i = 0, $i < count(tableWeekDaySchedule), $i++) {

				insert flightId, $date, everything on row $i of tableWeekDaySchedule;

				$flightId++;

			}
		}
		else for ($i=0,$i< count(tableWeekEndSchedule), $i++) {

				insert flightId, $date, everything on row $i of tableWeekEndSchedule;

				$flightId++;
		}

		$date++;

	}





}



*/






?>