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

$startDate = "2014/12/01";
function	buildFlights (&$mysqli, $startDate) {

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