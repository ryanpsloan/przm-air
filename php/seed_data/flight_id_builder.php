<?php
/**
 * Created by PhpStorm.
 * User: zwg2
 * Date: 11/10/14
 * Time: 1:34 PM
 *
 *
 * Accesses data in the two schedule tables (determined by whether the given date is a weekday or weekend)
 * to assign a flightId to each flight on each day for users to be able to search flights
 */

$startDate = "2014-12-01 00:00:00";
$totalSeatsOnPlane = 20;

function	buildFlights (&$mysqli, $startDate) {

	for($i = 0; $i < 730; $i++) {

		$format = "Y-m-d hh:mm:ss";
		$date = DateTime::createFromFormat($format, $startDate);
		$dayOfWeek = date("N", $date);
		//fixme!

		if($date = weekday) { //fixme!

			while ($i = 0, $i < count(weekdaySchedule), $i++) {




				//CREATE QUERY TEMPLATE
				$query = "SELECT origin, destination, duration, departureTime, arrivalTime, flightNumber, price
						FROM weekdaySchedule WHERE weekdayScheduleId = ? ";
				$statement = $mysqli->prepare($query);
				if($statement === false) {
					throw(new mysqli_sql_exception("Unable to prepare statement"));
				}

				// bind the parameters to the place holders in the template
				$wasClean = $statement->bind_param("i", $i);
				if($wasClean === false) {
					throw(new mysqli_sql_exception("Unable to bind parameters"));
				}

				// execute the statement
				if($statement->execute() === false) {
					throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
				}

				// get result from the SELECT query *pounds fists*
				$result = $statement->get_result();
				if($result === false) {
					throw(new mysqli_sql_exception("Unable to get result set"));
				}


				// since this is a unique field, this will only return 0 or 1 results. So...
				// 1) if there's a result, we can make it into a Flight object normally
				// 2) if there's no result, we can just return null
				// fetch_assoc() returns a row as an associative array
				$row = $result->fetch_assoc();

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


				$flightId++;

			}
		} else
			for($i = 0, $i < count(weekendSchedule), $i++) {

				//CREATE QUERY TEMPLATE
				$query = "SELECT origin, destination, duration, departureTime, arrivalTime, flightNumber, price
						FROM weekdaySchedule WHERE weekendScheduleId = ? ";
				$statement = $mysqli->prepare($query);
				if($statement === false) {
					throw(new mysqli_sql_exception("Unable to prepare statement"));
				}

				// bind the parameters to the place holders in the template
				$wasClean = $statement->bind_param("i", $i);
				if($wasClean === false) {
					throw(new mysqli_sql_exception("Unable to bind parameters"));
				}

				// execute the statement
				if($statement->execute() === false) {
					throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
				}

				// get result from the SELECT query *pounds fists*
				$result = $statement->get_result();
				if($result === false) {
					throw(new mysqli_sql_exception("Unable to get result set"));
				}


				// since this is a unique field, this will only return 0 or 1 results. So...
				// 1) if there's a result, we can make it into a Flight object normally
				// 2) if there's no result, we can just return null
				// fetch_assoc() returns a row as an associative array
				$row = $result->fetch_assoc();

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


				$flightId++;
			}

		$date->add(new DateInterval('P1D'));

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
		$cal->set(2013, 6 /* July */, 7); // a Sunday

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










?>