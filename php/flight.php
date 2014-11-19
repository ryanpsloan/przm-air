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
	 * time of travel from origin to destination
	 **/
	private $duration
	;/**
	 * specific date and time of departure
	 **/
	private $departureDateTime;
	/**
	 * specific date and time of arrival
	 **/
	private $arrivalDateTime;
	/**
	 * flight Number that references which flight within any given week, i.e. unique to a given week Monday-Sunday.
	 **/
	private $flightNumber;
	/**
	 * base price of flight
	 **/
	private $price;
	/**
	 * number of remaining seats on a given flight.   Once the flight is created and stored, this auto-decrements any
	 * time a user buys another ticket that contains this flight.
	 **/
	private $totalSeatsOnPlane;
	/**
	 * constant for number of seats on a plane. kept small so we can create fake user cases like sold-out flights.
 	**/
	private static $totalSeatsConstant = 20;


	/**
	 * constructor for Flight
	 * @param mixed $newFlightId flight id (or null if new object)
	 * @param string $newOrigin origin
	 * @param string $newDestination destination
	 * @param string $newDuration duration DateInterval object
	 * @param string $newDepartureDateTime departure time
	 * @param string $newArrivalDateTime arrival time
	 * @param string $newFlightNumber flight number
	 * @param mixed $newPrice price
	 * @param mixed $newTotalSeatsOnPlane total seats left on plane
	 * @throws UnexpectedValueException when a parameter is of the wrong type
	 * @throws RangeException when a parameter is invalid
	 **/
	public function __construct($newFlightId, $newOrigin, $newDestination, $newDuration, $newDepartureDateTime,
										 $newArrivalDateTime, $newFlightNumber, $newPrice, $newTotalSeatsOnPlane) {
		try {
			$this->setFlightId($newFlightId);
			$this->setOrigin($newOrigin);
			$this->setDestination($newDestination);
			$this->setDuration($newDuration);
			$this->setDepartureDateTime($newDepartureDateTime);
			$this->setArrivalDateTime($newArrivalDateTime);
			$this->setFlightNumber($newFlightNumber);
			$this->setPrice($newPrice);
			$this->setTotalSeatsOnPlane($newTotalSeatsOnPlane);

		} catch(UnexpectedValueException $unexpectedValue) {
			// rethrow to the caller
			throw(new UnexpectedValueException("Unable to construct Flight", 0, $unexpectedValue));
		} catch(RangeException $range) {
			// rethrow to the caller
			throw(new RangeException("Unable to construct Flight", 0, $range));
		}
	}




	// ****SETS AND GETS ********

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
	 * gets the value of the flight's origin.
	 *
	 * @return string of origin
	 **/
	public function getOrigin() {
		return($this->origin);
	}

	/**
	 * sets the value of the origin
	 *
	 * @param string $newOrigin origin
	 * @throws UnexpectedValueException if the input doesn't appear to be a string
	 * @throws RangeException for any string that is not 3 characters
	 **/
	public function setOrigin($newOrigin) {
		// verify the origin is a string
		$newOrigin = trim($newOrigin);

		if(filter_var($newOrigin, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("Origin $newOrigin does not appear to be a string"));
		}

		//check that string is the appropriate length for an airport code
		if(strlen($newOrigin) !== 3) {
			throw(new RangeException("Origin $newOrigin does not appear to be a three-letter code."));
		}

		// finally, take the origin out of quarantine
		$this->origin = $newOrigin;
	}



	/**
	 * gets the value of the flight's destination.
	 *
	 * @return string of destination
	 **/
	public function getDestination() {
		return($this->destination);
	}


	/**
	 * sets the value of the destination
	 *
	 * @param string $newDestination destination
	 * @throws UnexpectedValueException if the input doesn't appear to be a string
	 * @throws RangeException for any string that is not 3 characters
	 **/
	public function setDestination($newDestination) {
		// verify the destination is a string
		$newDestination = trim($newDestination);

		if(filter_var($newDestination, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("Destination $newDestination does not appear to be a string"));
		}

		//check that string is the appropriate length for an airport code
		if(strlen($newDestination) !== 3) {
			throw(new RangeException("Destination $newDestination does not appear to be a three-letter code."));
		}
		// finally, take the departure out of quarantine
		$this->destination = $newDestination;
	}




	/**
	 * gets the value of the flight's duration.
	 *
	 * @return mixed value of duration
	 **/
	public function getDuration() {
		return($this->duration);
	}


	/**
	 * sets the value of the duration
	 *
	 * @param string $newDuration of the duration
	 * @throws UnexpectedValueException if the input doesn't appear to be a time
	 * @throws RangeException????
	 **/
	public function setDuration($newDuration) {
		// zeroth, allow a DateTime object to be directly assigned
		if(gettype($newDuration) === "object" && get_class($newDuration) === "DateTime") {
			$this->duration = $newDuration;
			return;
		}

		// treat the date as a mySQL date string
		$newDuration = trim($newDuration);
		if((preg_match("/^(\d{2}):(\d{2}))$/", $newDuration, $matches)) !== 1) {
			throw(new RangeException("$newDuration is not a valid duration time"));
		}

		// finally, take the date out of quarantine
		$newDuration = DateTime::createFromFormat("H:i", $newDuration);
		$this->duration = $newDuration;
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
	 * @param string $newDepartureDateTime of the departure's date and time
	 * @throws UnexpectedValueException if the input doesn't appear to be a date
	 * @throws RangeException????
	 **/
	public function setDepartureDateTime($newDepartureDateTime) {
	// zeroth, allow a DateTime object to be directly assigned
		if(gettype($newDepartureDateTime) === "object" && get_class($newDepartureDateTime) === "DateTime") {
			$this->$departureDateTime = $newDepartureDateTime;
			return;
		}

		// treat the date as a mySQL date string
		$newDepartureDateTime = trim($newDepartureDateTime);
		if((preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $newDepartureDateTime, $matches)) !== 1) {
			throw(new RangeException("$newDepartureDateTime is not a valid date"));
		}

		// verify the date is really a valid calendar date
		$year  = intval($matches[1]);
		$month = intval($matches[2]);
		$day   = intval($matches[3]);
		if(checkdate($month, $day, $year) === false) {
			throw(new RangeException("$newDepartureDateTime is not a Gregorian date"));
		}

		// finally, take the date out of quarantine
		$newDepartureDateTime = DateTime::createFromFormat("Y-m-d H:i:s", $newDepartureDateTime);
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
		// zeroth, allow a DateTime object to be directly assigned
		if(gettype($newArrivalDateTime) === "object" && get_class($newArrivalDateTime) === "DateTime") {
			$this->arrivalDateTime = $newArrivalDateTime;
			return;
		}

		// treat the date as a mySQL date string
		$newArrivalDateTime = trim($newArrivalDateTime);
		if((preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $newArrivalDateTime, $matches)) !== 1) {
			throw(new RangeException("$newArrivalDateTime is not a valid date"));
		}

		// verify the date is really a valid calendar date
		$year  = intval($matches[1]);
		$month = intval($matches[2]);
		$day   = intval($matches[3]);
		if(checkdate($month, $day, $year) === false) {
			throw(new RangeException("$newArrivalDateTime is not a Gregorian date"));
		}

		// finally, take the date out of quarantine
		$newArrivalDateTime = DateTime::createFromFormat("Y-m-d H:i:s", $newArrivalDateTime);
		$this->arrivalDateTime = $newArrivalDateTime;

	}


	/**
	 * gets the flight's flight number.
	 *
	 * @return string of flight number
	 **/
	public function getFlightNumber() {
		return($this->flightNumber);
	}


	/**
	 * sets the value of the flightNumber
	 *
	 * @param string $newFlightNumber flight number
	 * @throws UnexpectedValueException if the input doesn't appear to be a string
	 **/
	public function setFlightNumber($newFlightNumber) {
		// verify the flight number is a string
		$newFlightNumber = trim($newFlightNumber);

		if(filter_var($newFlightNumber, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("Flight number $newFlightNumber does not appear to be a string"));
		}

		// finally, take the flight number out of quarantine
		$this->flightNumber = $newFlightNumber;
	}


	/**
	 * gets the value of price
	 *
	 * @return mixed price
	 **/
	public function getPrice() {
		return($this->Price);
	}

	/**
	 * sets the value of the price
	 *
	 * @param mixed $newPrice price
	 * @throws UnexpectedValueException if not a number or null
	 * @throws RangeException if schedule id isn't positive
	 **/
	public function setPrice($newPrice) {
		// first, ensure the price is a number
		if(filter_var($newPrice, FILTER_VALIDATE_FLOAT) === false) {
			throw(new UnexpectedValueException("price $newPrice is not numeric"));
		}

		// second, convert the price to a float and enforce it's positive
		$newPrice = floatval($newPrice);
		if($newPrice <= 0) {
			throw(new RangeException("price $newPrice is not positive"));
		}

		// finally, take the price out of quarantine and assign it
		$this->price = $newPrice;
	}



	/**
	 * gets the value of totalSeatsOnPlane
	 *
	 * @return mixed totalSeatsOnPlane
	 **/
	public function getTotalSeatsOnPlane() {
		return($this->totalSeatsOnPlane);
	}




	/**
	 * sets the value of totalSeatsOnPlane
	 *
	 * @param mixed $newTotalSeatsOnPlane available seats
	 * @throws UnexpectedValueException if not an integer
	 * @throws RangeException if totalSeatsOnPlane isn't positive
	 **/
	public function setTotalSeatsOnPlane($newTotalSeatsOnPlane) {
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








	// ****INSERT UPDATE DELETE ********




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

		// convert duration to string
		if($this->duration === null) {
			$duration = null;
		} else {
			$duration = $this->duration->format("H:i");
		}


		// convert departureDateTime to string
		if($this->departureDateTime === null) {
			$departureDateTime = null;
		} else {
			$departureDateTime = $this->departureDateTime->format("Y-d-m H:i:s");
		}

		// convert arrivalDateTime to string
		if($this->arrivalDateTime === null) {
			$arrivalDateTime = null;
		} else {
			$arrivalDateTime = $this->arrivalDateTime->format("Y-d-m H:i:s");
		}

		// create query template
		$query     = "INSERT INTO flight (origin, destination, duration, departureDateTime, arrivalDateTime,
							flightNumber, price, totalSeatsOnPlane) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ssssssfi", $this->origin, $this->destination, $duration, $departureDateTime,
														$arrivalDateTime, $this->flightNumber, $this->price,
														$this->totalSeatsOnPlane);
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

		// enforce the flightId is not null (i.e., don't update a flight that hasn't been inserted)
		if($this->flightId === null) {
			throw(new mysqli_sql_exception("Unable to update a flight that does not exist"));
		}

		// convert duration to string
		if($this->duration === null) {
			$duration = null;
		} else {
			$duration = $this->duration->format("H:i");
		}


		// convert departureDateTime to string
		if($this->departureDateTime === null) {
			$departureDateTime = null;
		} else {
			$departureDateTime = $this->departureDateTime->format("Y-d-m H:i:s");
		}

		// convert arrivalDateTime to string
		if($this->arrivalDateTime === null) {
			$arrivalDateTime = null;
		} else {
			$arrivalDateTime = $this->arrivalDateTime->format("Y-d-m H:i:s");
		}

		// create query template
		$query     = 	"UPDATE flight SET origin = ?, destination = ?, duration = ?, departureDateTime = ?,
							arrivalDateTime = ?, flightNumber = ?, price = ?, totalSeatsOnPlane = ? WHERE flightId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ssssssfii", $this->origin, $this->destination, $duration, $departureDateTime,
													$arrivalDateTime, $this->flightNumber, $this->price, $this->totalSeatsOnPlane,
													$this->flightId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}



	// ****CUSTOM FUNCTIONS: increment/decrement, searchByUser, searchByFlightId********

	/**
	 * searches all flights based on user input to return route options
	 * NOTE that when user loads the return route page after selecting an outbound route, this function will need to be
	 * called again but with the user's origin inputted to function as $userDestination, and the destination as the
	 * $userOrigin, and the return date inputted as $userFlyDate
	 *
	 * @param resource $concreteMysqli pointer to concrete mySQL connection, by reference
	 * @param string $userOrigin
	 * @param string $userDestination
	 * @param string $userFlyDateStart to search for
	 * @param string $userFlyDateEnd to search for
	 * @param string $numberOfPassengers to search for
	 * @throws RangeException if number
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 * @return mixed $allFlightsArray of flight and flight combos/paths found or null if not found

	 **/
	public static function getRoutesByUserInput(&$concreteMysqli, $userOrigin, $userDestination, $userFlyDateStart,
															  $userFlyDateEnd, $numberOfPassengers)
	{
		// handle degenerate cases
		if(gettype($concreteMysqli) !== "object" || get_class($concreteMysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}


		// first trim, then validate, then sanitize the USER inputs before searching.
		// verify all strings as a string and dates as strings in correct format

		// 1.:
		$userOrigin = trim($userOrigin);

		if(filter_var($userOrigin, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("Origin $userOrigin does not appear to be a string"));
		}

		//check that string is the appropriate length for an airport code
		if(strlen($userOrigin) !== 3) {
			throw(new RangeException("Origin $userOrigin does not appear to be a three-letter code."));
		}


		// 2.:
		$userDestination = trim($userDestination);

		if(filter_var($userDestination, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("Origin $userDestination does not appear to be a string"));
		}

		//check that string is the appropriate length for an airport code
		if(strlen($userDestination) !== 3) {
			throw(new RangeException("Destination $userDestination does not appear to be a three-letter code."));
		}


		// 3.:
		$userFlyDateStart = trim ($userFlyDateStart);

		if(filter_var($userFlyDateStart, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("Start date $userFlyDateStart does not appear to be a string"));
		}

		// treat the date as a mySQL date string
		$userFlyDateStart = trim($userFlyDateStart);
		if((preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $userFlyDateStart, $matches)) !== 1) {
			throw(new RangeException("$userFlyDateStart is not a valid date"));
		}

		// verify the date is really a valid calendar date
		$year  = intval($matches[1]);
		$month = intval($matches[2]);
		$day   = intval($matches[3]);
		if(checkdate($month, $day, $year) === false) {
			throw(new RangeException("$userFlyDateStart is not a Gregorian date"));
		}


		// 4.:
		$userFlyDateEnd = trim($userFlyDateEnd);

		if(filter_var($userFlyDateEnd, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("End date $userFlyDateEnd does not appear to be a string"));
		}

		// treat the date as a mySQL date string
		$userFlyDateEnd = trim($userFlyDateEnd);
		if((preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $userFlyDateEnd, $matches)) !== 1) {
			throw(new RangeException("$userFlyDateEnd is not a valid date"));
		}

		// verify the date is really a valid calendar date
		$year  = intval($matches[1]);
		$month = intval($matches[2]);
		$day   = intval($matches[3]);
		if(checkdate($month, $day, $year) === false) {
			throw(new RangeException("$userFlyDateEnd is not a Gregorian date"));
		}


		// 5.:
		$numberOfPassengers = trim($numberOfPassengers);

		if (filter_var($numberOfPassengers, FILTER_SANITIZE_NUMBER_INT) === false) {
		throw (new UnexpectedValueException ("Number of requested seats $numberOfPassengers does not appear to be an
														integer"));
		}
		else {
			$numberOfPassengers = filter_var($numberOfPassengers, FILTER_SANITIZE_NUMBER_INT);
		}

		// convert the $numberOfPassengers to an integer and enforce it's positive
		$numberOfPassengers = intval($numberOfPassengers);
		if($numberOfPassengers <= 0) {
			throw(new RangeException("Number of requested seats $numberOfPassengers is not positive"));
		}



		// Next, create query template to call the stored procedure and execute search in MySQL
		$query = "CALL spFlightSearchR(?, ?, ?, ?, ?)";

		$statement = $concreteMysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the user inputs to the place holder in the template
		$wasClean = $statement->bind_param("ssssi", $userOrigin, $userDestination, $userFlyDateStart, $userFlyDateEnd,
			$numberOfPassengers);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		// get result from the SELECT query *pounds fists*
		$getStoredProcResults = $statement->get_result();
		if($getStoredProcResults === false) {
			throw(new mysqli_sql_exception("Unable to get result set"));
		}

		// this will return as many results as there are flights and flight combos with same origin + departure + date.
		//	1) if there's no result, we can just return null
		// 2) if there's a result, we can make it into flight objects by using the flight path string
		// fetch_assoc() returns row as associative arr until row is null

		// create query to take results from stored procedure and get all related info for each flight returned
		$query = "SELECT flightId, origin, destination, duration, departureDateTime, arrivalDateTime, flightNumber, price,
 					totalSeatsOnPlane FROM flight WHERE flightId IN (?)";

		$statement2 = $concreteMysqli->prepare($query);
		if($statement2 === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}


		// set up array to hold all results
		$allFlightPathsArray = array();



		// convert the associative array to a Flight for all origin + departure + date equal to $userOrigin,
		// $userDestination, and $userFlyDate range.
		// fixme: should fetch assoc be fetch array?
		while(($row = $getStoredProcResults->fetch_assoc()) !== null) {

			try {

				// bind the user inputs to the place holder in the template to make a 2 dimensional array (array of arrays
				// of all related info for each flight ID in a path)
				$wasClean = $statement2->bind_param("s", $row["path"]);

				if($wasClean === false) {
					throw(new mysqli_sql_exception("Unable to bind parameters"));
				}

				// execute the statement
				if($statement2->execute() === false) {
					throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
				}

				// get result from the SELECT query *pounds fists*
				$eachFlightPath = $statement->get_result();
				if($eachFlightPath === false) {
					throw(new mysqli_sql_exception("Unable to get result set"));
				}

				//put the two dimensional array into another array of all the flight paths each with all
				//relative data for each flight


				$allFlightPathsArray[] = $eachFlightPath;


					//"SELECT * FROM flight WHERE flightId IN ($row[3])";
					//	new Flight ($row["flightId"], $row["origin"], $row["destination"], $row["duration"],
					// $row["departureDateTime"], $row["arrivalDateTime"], $row["flightNumber"],
					//	$row["price"], $row["totalSeatsOnPlane"]);

			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception("Unable to convert row to Flight path", 0, $exception));
			}

		} // end while loop


		if(empty($allFlightsArray)) {
			// 404 User not found - return null
			return (null);
		} else {
			return ($allFlightsArray);
		}


	}


	/**
	 * increments or decrements totalSeatsOnPlane for given flightId in mySQL
	 * @param mixed $flightId flight ID to search for
	 *	@param mixed $changeBy positive or negative number indicating requested change to number of seats
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param mixed $totalSeatsConstant total seats on a plane for use in incrementing
	 * @return mixed Flight found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/

	public function changeNumberOfSeats(&$mysqli, $flightId, $changeBy, $totalSeatsConstant) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the flightId is not null (i.e., don't update a flight that hasn't been inserted)
		if($this->flightId === null) {
			throw(new mysqli_sql_exception("Unable to update a flight that does not exist"));
		}

		// ensure the changeBy is an integer
		if(filter_var($changeBy, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("Change amount for number of seats, $changeBy, is not numeric"));
		}

		$changeBy = intval($changeBy);

		// first, get the total seats left on this flightId
		// create query template for SELECT
		$querySelect = "SELECT totalSeatsOnPlane FROM flight WHERE $flightId = ?";
		$statement = $mysqli->prepare($querySelect);
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

		//next, check that there's enough seats left to execute the $changeBy calc
		if ($result + $changeBy>=0 && $result + $changeBy <= $totalSeatsConstant) {
			$totalSeatsOnPlane = $result + $changeBy;
		} else {
			throw (new RangeException("There are not enough seats on this flight to increment or decrement by $changeBy
												seats."));
		}


		// create query template for UPDATE to update flight with new number
		$queryUpdate     = 	"UPDATE flight SET totalSeatsOnPlane = ? WHERE flightId = ?";

		//prepare the statement
		$makeChange = $mysqli->prepare($queryUpdate);
		if($makeChange === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $makeChange->bind_param("ii", $totalSeatsOnPlane, $this->flightId);//fixme - no $this for totalSeats but yes for flightId?
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($makeChange->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}


	/**
	 * gets the Flight by flightId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $flightId flight ID to search for
	 * @return mixed Flight found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getFlightByFlightId(&$mysqli, $flightId) {
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
		$query = "SELECT flightId, origin, destination, duration, departureDateTime, arrivalDateTime, flightNumber,
					price, totalSeatsOnPlane FROM flight WHERE $flightId = ?";
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
				$flight = new Flight ($row["flightId"], $row["origin"], $row["destination"], $row["duration"],
												$row["departureDateTime"], $row["arrivalDateTime"], $row["flightNumber"],
												$row["price"], $row["totalSeatsOnPlane"]);



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


	// ****AUXILIARY FUNCTION********

	/**
	 * @return string describing info in class Flight
	 */
	public function __toString() {
		return ("<p>" . "Flight Id: " . $this->flightId . "<br/>" . "Origin: " . $this->origin . "<br/>" . "Destination: "
			. $this->destination . "<br/>" . "Duration: " . $this->duration . "<br/>" . "Departure: " .
			$this->departureDateTime . "<br/>" . "Arrival: " .	$this->arrivalDateTime . "<br/>" . "Flight Number: " .
			$this->flightNumber . "<br/>" . "Price: " . $this->price . "<br/>" . "Remaining Seats Available: " .
			$this->totalSeatsOnPlane . "<br/>" . "</p>");
	}

}
?>