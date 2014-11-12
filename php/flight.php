<?php
/**
 * mySQL Enabled Flight
 *
 * This is a mySQL enabled container for all flights (and their related data) that are created and stored in
 * the database when a passenger selects a flight option from the search.
 *
 * @author Zach Grant <zgrant28@gmail.com>
 * @see Profile
 * @Date: 11/6/14
 * @Time: 10:13 AM
 */

class Flight {
	/**
	 * flight id that refers to a specific flight at a specific time on a specific day to the flight
	 **/
	private $flightId;
	/**
	 * the origin of a flight
	 **/
	private $origin;
	/**
	 * the destination of a flight
	 **/
	private $destination;
	/**
	 * specific date and time of departure
	 **/
	private $departureDateTime;
	/**
	 * specific date and time of arrival
	 **/
	private $arrivalDateTime;
	/**
	 * number of remaining seats on a given flight.   Once the flight is created and stored, this auto-decrements any
	 * time a user buys another ticket that contains this flight.
	 **/
	private $totalSeatsOnPlane;
	/**
 	* constant for number of seats on a plane. kept small so we can create fake user cases like sold-out flights.
 	**/
	//already inserted in database when seeding data:
	//private static $totalSeatsConstant = 20;


	/**
	 *Questions:
	 * ?? -- validating datetime for arrival and departure
	 * ?? -- similarly, for mysqli statements, do i list them all as ("iiii").
	 * ?? -- need constant?  if total seats created for each flight object, then can we not auto-decrement that field
	 *			for that specific flightId?
	 * ?? -- leave constant outside construct method
	 * ?? what is 0 in throw exceptions around line 94
	 *
	 * ToDo: seats decrementer/incrementer
	 * ToDo: search function to make tickets?
	 * ToDo: besides finding flight by flight ID? Do we need scheduleId by flightId or other similar?
	 * ToDo: loop within loop function to seed data from the schedule class
	 * ToDo: fix totalSeats function/calc
	 * ToDo: clean up __get() or change to __call()
	 * ToDo: see Dylan's code fragment for DATETIME objects
	 *
	 *
	 **/




	/**
	 * constructor for Flight
	 *
	 * @param mixed $newFlightId flight id (or null if new object)
	 * @param mixed $newScheduleId schedule id
	 * @param string $newDepartureDateTime departure time
	 * @param string $newArrivalDateTime arrival time
	 * @param string $newTotalSeatsOnPlane total seats left on plane
	 * @throws UnexpectedValueException when a parameter is of the wrong type
	 * @throws RangeException when a parameter is invalid
	 **/
	public function __construct($newFlightId, $newOrigin, $newDestination, $newDepartureDateTime, $newArrivalDateTime,$newTotalSeatsOnPlane) {
		try {
			$this->setFlightId($newFlightId);
			$this->setOrigin($newOrigin);
			$this->setDestination($newDestination);
			$this->setDepartureDateTime($newDepartureDateTime);
			$this->setArrivalDateTime($newArrivalDateTime);
			$this->setTotalSeatsOnPlane($newTotalSeatsOnPlane);
		} catch(UnexpectedValueException $unexpectedValue) {
			// rethrow to the caller
			throw(new UnexpectedValueException("Unable to construct Flight", 0, $unexpectedValue));
		} catch(RangeException $range) {
			// rethrow to the caller
			throw(new RangeException("Unable to construct Flight", 0, $range));
		}
	}


	/**
	 * gets the value of flight id
	 *
	 * @return mixed flight id (or null if new object)
	 **/
	public function getFlightId() {
		return($this->flightId);
	}


	/**
	 * sets the value of flight id
	 *
	 * @param mixed $newFlightId profile id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if flight id isn't positive
	 **/
	public function setFlightId($newFlightId) {
		// zeroth, set allow the flight id to be null if a new object
		if($newFlightId === null) {
			$this->$flightId = null;
			return;
		}

		// first, ensure the flight id is an integer
		if(filter_var($newFlightId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("flight id $newFlightId is not numeric"));
		}

		// second, convert the flight id to an integer and enforce it's positive
		$newFlightId = intval($newFlightId);
		if($newFlightId <= 0) {
			throw(new RangeException("flight id $newFlightId is not positive"));
		}

		// finally, take the flight id out of quarantine and assign it
		$this->flightId = $newFlightId;
	}


	/**
	 * gets the value of schedule id
	 *
	 * @return mixed schedule id
	 **/
	public function getScheduleId() {
		return($this->scheduleId);
	}

	/**
	 * sets the value of schedule id
	 *
	 * @param int $newScheduleId schedule id
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if schedule id isn't positive
	 **/
	public function setScheduleId($newScheduleId) {
		// first, ensure the schedule id is an integer
		if(filter_var($newScheduleId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("schedule id $newScheduleId is not numeric"));
		}

		// second, convert the schedule id to an integer and enforce it's positive
		$newScheduleId = intval($newScheduleId);
		if($newScheduleId <= 0) {
			throw(new RangeException("schedule id $newScheduleId is not positive"));
		}

		// finally, take the schedule id out of quarantine and assign it
		$this->scheduleId = $newScheduleId;
	}


	/**
	 * gets the value of the flight's departure datetime.
	 *
	 * @return mixed value of departure datetime
	 **/
	public function getDepartureDateTime() {
		return($this->departureDateTime);
	}


	/**
	 * sets the value of the departureDateTime
	 *
	 * @param string $newDepartureDateTime of the first name
	 * @throws UnexpectedValueException if the input doesn't appear to be a date
	 * @throws RangeException????
	 **/
	public function setDepartureDateTime($newDepartureDateTime) {
		// verify the date and time is a datetime
		$newDepartureDateTime = trim($newDepartureDateTime);

		if(filter_var($newDepartureDateTime, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("Departure date and time $newDepartureDateTime does not appear to be a date"));
		}

		// finally, take the departure datetime out of quarantine
		$this->departureDateTime = $newDepartureDateTime;
	}


	/**
	 * gets the value of arrival datetime
	 *
	 * @return mixed value of arrival datetime
	 **/
	public function getArrivalDateTime() {
		return($this->arrivalDateTime);
	}


	/**
	 * sets the value of the arrivalDateTime
	 *
	 * @param string $newArrivalDateTime of the arrival date and time
	 * @throws UnexpectedValueException if the input doesn't appear to be a date
	 * @throws RangeException???
	 **/
	public function setArrivalDateTime($newArrivalDateTime) {
		// verify the date and time is a datetime
		$newArrivalDateTime = trim($newArrivalDateTime);

		if(filter_var($newArrivalDateTime, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("Departure date and time $newArrivalDateTime does not appear to be a date"));
		}

		// finally, take the arrival datetime out of quarantine
		$this->arrivalDateTime = $newArrivalDateTime;
	}


		/**
		 * gets the value of schedule id
		 *
		 * @return mixed schedule id
		 **/
	public function getScheduleId() {
		return($this->scheduleId);
	}



	/**
	 * sets the value of totalSeatsOnPlane
	 *
	 * @param mixed $newTotalSeatsOnPlane available seats
	 * @throws UnexpectedValueException if not an integer
	 * @throws RangeException if totalSeatsOnPlane isn't positive
	 **/
	public function setTotalSeatsOnPlane($newFlightId) {
	// first, ensure the total seats on a plane is an integer
		if(filter_var($newTotalSeatsOnPlane, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("total seats on plane of $newTotalSeatsOnPlane is not numeric"));
		}

		// second, convert the TotalSeatsOnPlane to an integer and enforce it's positive
		$newTotalSeatsOnPlane = intval($newTotalSeatsOnPlane);
		if($newTotalSeatsOnPlane <= 0) {
			throw(new RangeException("total seats on plane of $newTotalSeatsOnPlane is not positive"));
		}

		// finally, take the total seats on plane out of quarantine and assign it
		$this->totalSeatsOnPlane = $newTotalSeatsOnPlane;
	}







	/**
	 * inserts this Flight to mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the flightId is null (i.e., don't insert a new flight if it already exists)
		if($this->flightId !== null) {
			throw(new mysqli_sql_exception("not a new flight"));
		}

		// create query template
		$query     = "INSERT INTO flight (scheduleId, departureDateTime, arrivalDateTime, totalSeatsOnPlane) VALUES(?, ?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("issi", $this->scheduleId, $this->departureDateTime,
			$this->arrivalDateTime, $this->totalSeatsOnPlane);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		// update the null flightId with what mySQL just gave us
		$this->flightId = $mysqli->insert_id;
	}



	/**
	 * deletes this Flight from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the flightId is not null (i.e., don't delete a flight that hasn't been inserted)
		if($this->flightId === null) {
			throw(new mysqli_sql_exception("Unable to delete a flight that does not exist"));
		}

		// create query template
		$query     = "DELETE FROM flight WHERE flightId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("i", $this->flightId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}

	/**
	 * updates this Flight in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the userId is not null (i.e., don't update a user that hasn't been inserted)
		if($this->flightId === null) {
			throw(new mysqli_sql_exception("Unable to update a flight that does not exist"));
		}

		// create query template
		$query     = 	"UPDATE flight SET scheduleId = ?, departureDateTime = ?, arrivalDateTime = ?,
							totalSeatsOnPlane = ? WHERE flightId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("issii", $this->scheduleId, $this->departureDateTime, $this->arrivalDateTime,
														$this->totalSeatsOnPlane, $this->flightId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}


	/**
	 * decrements the totalSeatsOnPlane
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param mixed $totalSeatsOnPlane available seats to change
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/








	/**
	 * gets the Flight by flightId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $flightId flight ID to search for
	 * @return mixed Flight found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getFlightByFlightId(&$mysqli, $flightID) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}


		// first trim, then validate, then sanitize the flightId int before searching.
		$flightId = trim($flightId);

		if (filter_var($flightId, FILTER_SANITIZE_NUMBER_INT) === false) {
			throw (new UnexpectedValueException ("flight id $flightId does not appear to be an integer"));
		}
		else {
			$flightId = filter_var($flightId, FILTER_SANITIZE_NUMBER_INT);
		}

		// create query template
		$query = "SELECT flightId, scheduleId, departureDateTime, arrivalDateTime FROM flight WHERE $flightId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the flightId to the place holder in the template
		$wasClean = $statement->bind_param("i", $flightId);
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
		$row = $result->fetch_assoc(); // fetch_assoc() returns a row as an associative array

		// convert the associative array to a Flight
		if($row !== null) {
			try {
				$flight = new Flight ($row["flightId"], $row["scheduleId"], $row["departureDateTime"],
											$row["arrivalDateTime"], $row["totalSeatsOnPlane"]);
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
















	/**
	 * @return string describing info in class Flight
	 */
	public function __toString() {
		return ("<p>" . "Flight Id: " . $this->flightId . "<br/>" . "Schedule ID: " . $this->scheduleId . "<br/>" .
			"Departure: " . $this->departureDateTime . "<br/>" . "Arrival: " . $this->arrivalDateTime . "<br/>" .
			"Remaining Seats Available " . $this->totalSeatsOnPlane . "<br/>" . "</p>");
	}


	/**
	 * @param $searchedField
	 * @returns searched field, or null if the needed array key does not exist in database
	 * @throws E_USER_NOTICE trigger error if searched field does not exist as an array key.
	 */
	public function __get($searchedField) {
		echo "Getting '$searchedField'\n";
		if (array_key_exists($searchedField, $this->data)) {
			return $this->data[$searchedField];
		}

		//if else question
		else {
			throw (new UnexpectedValueException ("We searched for $searchedField and it does not seem to be an appropriate array key"));
			return null;
		}
		//$trace = debug_backtrace();
		//trigger_error("Undefined property via __get(): " . $searchedField . " in " . $trace[0]['file'] . " on line " . $trace[0]["line"],
		//	E_USER_NOTICE);


	}
}







/**
 * gets any existing Profile by lastName
 *
 * @param resource $mysqli pointer to mySQL connection, by reference
 * @param string $lastName last name to search for
 * @return mixed Profile found or null if not found
 * @throws mysqli_sql_exception when mySQL related errors occur
 **/
public static function getProfileByLastName(&$mysqli, $lastName)
{
	// handle degenerate cases
	if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
		throw(new mysqli_sql_exception("input is not a mysqli object"));
	}

	// first trim, then validate, then sanitize the lastName string before searching.
	$lastName = trim($lastName);

	if (filter_var($lastName, FILTER_SANITIZE_STRING) === false) {
		throw (new UnexpectedValueException ("last name of $lastName does not appear to be a string"));
	}
	else {
		$lastName = filter_var($lastName, FILTER_SANITIZE_STRING);
	}

	// create query template
	$query = "SELECT profileId, userId, firstName, lastName FROM profile WHERE lastName = ?";
	$statement = $mysqli->prepare($query);
	if($statement === false) {
		throw(new mysqli_sql_exception("Unable to prepare statement"));
	}

	// bind the last name to the place holder in the template
	$wasClean = $statement->bind_param("s", $lastName);
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

	// since this is not a unique field, this will return as many results as there are profiles with  same last name.
	// 1) if there's no result, we can just return null
	// 2) if there's a result, we can make it into Profile objects normally
	// fetch_assoc() returns row as associative arr until row is null
//		$arrayCounter = 0;
	$profileArray = array();
	// convert the associative array to a Profile and repeat for all last names equal to lastName.
	while(($row = $result->fetch_assoc()) !== null) {

		// convert the associative array to a Profile for all last names equal to lastName.
		try {
			$profile = new Profile($row["profileId"], $row["userId"], $row["firstName"], $row["lastName"]);
			$profileArray[] = $profile;

		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new mysqli_sql_exception("Unable to convert row to Profile", 0, $exception));
		}

	}

	if(empty($profileArray)) {
		// 404 User not found - return null
		return (null);
	}
	else {
		return ($profileArray);
	}
}


?>