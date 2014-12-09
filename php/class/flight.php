<?php
/**
 * TODO: write bloody doc block
 *
 * @param $firstPath
 * @param $secondPath
 * @return int
 */
function sortByPrice($firstPath, $secondPath) {
	if($firstPath[count($firstPath) - 1] > $secondPath[count($secondPath) - 1]){
		$compareVal = ceil ($firstPath[count($firstPath) - 1] - $secondPath[count($secondPath) - 1]);
	} else if ($firstPath[count($firstPath) - 1] = $secondPath[count($secondPath) - 1]) {
		$compareVal = ($firstPath[count($firstPath) - 1] - $secondPath[count($secondPath) - 1]);
	} else {
		$compareVal = floor ($firstPath[count($firstPath) - 1] - $secondPath[count($secondPath) - 1]);
	}
	return $compareVal;
}

/**
 * mySQL Enabled Flight
 *
 * fixme: rewrite this heading
 * This is a mySQL enabled container for all flights (and their related data) that are created and stored in
 * the database when a passenger selects a flight option from the search.
 *
 * @author Zach Grant <zgrant28@gmail.com>
 * @see Profile // fixme
 * @Date: 11/6/14
 * @Time: 10:13 AM
 */
require_once("/etc/apache2/capstone-mysql/przm.php");
require_once("results.php");

//fixme add in conversions of incoming date formats from datepicker in jQuery to to needed formats, then change back to end back to front end

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
	 * time of travel from origin to destination, set as DateInterval object
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
			//var_dump($range);
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
			$this->flightId = null;
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
	 * gets the value of the flight's duration as a DateTimeObject.
	 *
	 * @return mixed value of duration
	 **/
	public function getDuration() {
		return($this->duration);
	}


	/**
	 * sets the value of the duration as a DateTimeObject
	 *
	 * @param string $newDuration of the duration
	 * @throws UnexpectedValueException if the input doesn't appear to be a time
	 * @throws RangeException????
	 **/
	public function setDuration($newDuration) {
		// zeroth, allow a DateTime object to be directly assigned
		if(gettype($newDuration) === "object" && get_class($newDuration) === "DateInterval") {
			$this->duration = $newDuration;
			return;
		}

		// treat the date as a mySQL date string
		$newDuration = trim($newDuration);
		if((preg_match("/^(\d{2}):(\d{2}):(\d{2})$/", $newDuration, $matches)) !== 1) {
			throw(new RangeException("$newDuration is not a valid duration time"));
		}

		// finally, make the duration a DateInterval and take out of quarantine

		$explode = explode(":", $newDuration);
		$newDuration = DateInterval::createFromDateString("$explode[0] hour + $explode[1] minutes + 0 seconds");
		$this->duration = $newDuration;
	}



	/**
	 * gets the value of the flight's departure datetime as a DateTimeObject.
	 *
	 * @return mixed value of departure datetime
	 **/
	public function getDepartureDateTime() {
		return($this->departureDateTime);
	}


	/**
	 * sets the value of the departureDateTime as a DateTimeObject
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
	 * gets the value of arrival datetime as a DateTimeObject
	 *
	 * @return mixed value of arrival datetime
	 **/
	public function getArrivalDateTime() {
		return($this->arrivalDateTime);
	}


	/**
	 * sets the value of the arrivalDateTime as a DateTimeObject
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
		return($this->price);
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
		if($newPrice <= 0.00) {
			throw(new RangeException("price $newPrice is not positive"));
		}

		// finally, take the price out of quarantine and assign it
		$this->price = floatval($newPrice);
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

		//echo "<p>line 467 of Flight var dump of duration in insert method before formatting</p>";
		//var_dump($this->duration);
		// convert duration to string
		if($this->duration === null) {
			$duration = "fix me!";
		} else {
			$duration = $this->duration->format("%H:%I:%S");
		}

		//echo "<p>line 467 of Flight var dump of duration in insert method after formatting</p>";
		//var_dump($duration);

		// convert departureDateTime to string
		if($this->departureDateTime === null) {
			$departureDateTime = null;
		} else {
			$departureDateTime = $this->departureDateTime->format("Y-m-d H:i:s");
		}

		// convert arrivalDateTime to string
		if($this->arrivalDateTime === null) {
			$arrivalDateTime = null;
		} else {
			$arrivalDateTime = $this->arrivalDateTime->format("Y-m-d H:i:s");
		}

		// create query template
		$query     = "INSERT INTO flight (origin, destination, duration, departureDateTime, arrivalDateTime,
							flightNumber, price, totalSeatsOnPlane) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ssssssdi", $this->origin, $this->destination, $duration, $departureDateTime,
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
			$duration = $this->duration->format("H:i:s");
		}


		// convert departureDateTime to string
		if($this->departureDateTime === null) {
			$departureDateTime = null;
		} else {
			$departureDateTime = $this->departureDateTime->format("Y-m-d H:i:s");
		}

		// convert arrivalDateTime to string
		if($this->arrivalDateTime === null) {
			$arrivalDateTime = null;
		} else {
			$arrivalDateTime = $this->arrivalDateTime->format("Y-m-d H:i:s");
		}

		// create query template
		$query     = 	"UPDATE flight SET origin = ?, destination = ?, duration = ?, departureDateTime = ?,
							arrivalDateTime = ?, flightNumber = ?, price = ?, totalSeatsOnPlane = ? WHERE flightId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ssssssdii", $this->origin, $this->destination, $duration, $departureDateTime,
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



	// ****CUSTOM FUNCTIONS: searchByFlightId, increment/decrement, searchByUser********


	/**
	 * gets the Flight by flightId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $flightId flight ID to search for
	 * @return mixed $flight found or null if not found
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
					price, totalSeatsOnPlane FROM flight WHERE flightId = ?";

		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		//echo "<p>1017 flightId that comes into get flight by flight id before binding</p>";
		//var_dump($flightId);


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


		//echo "<p>line 1039 of FLIGHT var dump of RESULT OBJECT in getFlightByFlightID before fetchassoc</p>";
		//var_dump($result->num_rows);


		// since this is a unique field, this will only return 0 or 1 results. So...
		// 1) if there's a result, we can make it into a Flight object normally
		// 2) if there's no result, we can just return null
		// fetch_assoc() returns a row as an associative array
		$row = $result->fetch_assoc();

		//echo "<p>line 1035 of FLIGHT var dump of ROW in getFlightByFlightID object after fetchassoc</p>";
		//var_dump($row);

		// convert the associative array to a Flight
		if($row !== null) {
			try {
				//$floatPrice = (float) $row['price'];

				////echo "<p>line 1042 of FLIGHT var dump of float price in getFlightByFlightID object after fetchassoc</p>";
				////var_dump($floatPrice);

				$flight = new Flight ($row["flightId"], $row["origin"], $row["destination"], $row["duration"],
					$row["departureDateTime"], $row["arrivalDateTime"], $row["flightNumber"],
					$row["price"], $row["totalSeatsOnPlane"]);



			} catch(Exception $exception) {

				//echo "<p>line 1054 dump of exception before throws</p>";
				//var_dump($exception);

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
	 * increments or decrements totalSeatsOnPlane for given flightId in mySQL
	 * @param mixed $flightId flight ID to search for
	 *	@param mixed $changeBy positive or negative number indicating requested change to number of seats
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param mixed $totalSeatsConstant total seats on a plane for use in incrementing
	 * @return mixed Flight found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function changeNumberOfSeats(&$mysqli, $flightId, $changeBy) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the flightId is not null (i.e., don't update a flight that hasn't been inserted)
		if($flightId === null) {
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

		// since this field is unique to this flightId, this will only return 0 or 1 results. So...
		// 1) if there's a result, we can use it to calc
		// 2) if there's no result, we can just return null
		$row1 = $result->fetch_assoc(); // fetch_assoc() returns a row as an associative array

		// convert the associative array to a variable
		if($row1 !== null) {
			try {
				$currentSeatsAvailable = $row1["totalSeatsOnPlane"];

			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception("Unable to convert row to integer", 0, $exception));
			}
		}
		//next, check that there's enough seats left to execute the $changeBy calc
		if ($currentSeatsAvailable + $changeBy>=0 && $currentSeatsAvailable + $changeBy <= self::$totalSeatsConstant) {
			$totalSeatsOnPlane = $currentSeatsAvailable + $changeBy;
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
		$wasClean = $makeChange->bind_param("ii", $totalSeatsOnPlane, $flightId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($makeChange->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}


	/**
	 * searches all flights based on user input to return route options with calculated total price and duration
	 * NOTE that when user loads the return route page after selecting an outbound route, this function will need to be
	 * called again but with the user's origin inputted to function as $userDestination, and the destination as the
	 * $userOrigin, and the return date inputted as $userFlyDate
	 * @param 	resource $mysqli pointer to temp mySQL connection, by reference
	 * @param 	string $userOrigin with 3 letter origin city
	 * @param 	string $userDestination with 3 letter destination city
	 * @param 	string $userFlyDateStart of midnight on user's chosen fly date
	 * @param 	int $userFlyDateRange defined by range of time in hours that all paths must be complete by.
	 * @param 	mixed $numberOfPassengers of number of passengers flying together on the same flight path as part of same
	 * 			search and eventual purchase
	 * @param 	mixed $minLayover the number of minutes a user requires between flights in a given path
	 * @throws 	RangeException if origin or destination codes are not 3 letters.
	 * @throws 	mysqli_sql_exception when mySQL related errors occur
	 * @return 	mixed $allFlightsArray array of all flight paths, each of which is an array of flight object(s) plus
	 * 			DateInterval object for total path duration and integer of total price for path.
	 **/
// fixme: add configuration file or actually hardwire as public static variables at top of class for businss logic numbers
// fixme: add ability to do return flight search so that it only searches dates after the depart date's last arrival, even if on same day
	public static function getRoutesByUserInput(&$mysqli, $userOrigin, $userDestination, $userFlyDateStart,
															  $userFlyDateRange, $numberOfPassengers, $minLayover) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
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


		// 3.: fixme add restriction that date has to be a future one.
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
		$userFlyDateRange = trim($userFlyDateRange);

		if (filter_var($userFlyDateRange, FILTER_SANITIZE_NUMBER_INT) === false) {
			throw (new UnexpectedValueException ("Number of hours of $userFlyDateRange to complete trip does not appear to
														be an integer"));
		}

		$userFlyDateRange = filter_var($userFlyDateRange, FILTER_SANITIZE_NUMBER_INT);


		// convert the $userFlyDateRange to an integer and enforce it's positive
		$userFlyDateRange = intval($userFlyDateRange);
		if($userFlyDateRange <= 0) {
			throw(new RangeException("Number of hours to complete the trip $userFlyDateRange is not positive"));
		}

		// convert to string for input into the stored procedure call
		$userEndFlyDateInterval = DateInterval::createFromDateString($userFlyDateRange . "hours");

		$userStartFlyDateTime = DateTime::createFromFormat("Y-m-d H:i:s", $userFlyDateStart);
		$userFlyDateEndObj = $userStartFlyDateTime->add($userEndFlyDateInterval);

		$userFlyDateEnd = $userFlyDateEndObj->format("Y-m-d H:i:s");

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

		$numberOfPassengers = filter_var($numberOfPassengers, FILTER_SANITIZE_NUMBER_INT);


		// convert the $numberOfPassengers to an integer and enforce it's positive
		$numberOfPassengers = intval($numberOfPassengers);
		if($numberOfPassengers <= 0) {
			throw(new RangeException("Number of requested seats $numberOfPassengers is not positive"));
		}

		// 6.:
		$minLayover = trim($minLayover);

		if (filter_var($minLayover, FILTER_SANITIZE_NUMBER_INT) === false) {
			throw (new UnexpectedValueException ("Number of layover minutes of $minLayover does not appear to be an
														integer"));
		}

		$minLayover = filter_var($minLayover, FILTER_SANITIZE_NUMBER_INT);


		// convert the $numberOfPassengers to an integer and enforce it's positive
		$minLayover = intval($minLayover);
		if($minLayover <= 0) {
			throw(new RangeException("Number of layover minutes of $minLayover is not positive"));
		}

		$sessionId = session_id();

		// fixme, create query template if possible to call the stored procedure and execute search in MySQL.  IF not possible COMMENT LIKE CRAZY so people aware of this bug.
		// run stored procedure in MySQL and then get results from the results.php file.
		$query = "CALL spFlightSearchR('$userOrigin', '$userDestination', '$userFlyDateStart', '$userFlyDateEnd',
			$numberOfPassengers, $minLayover, '$sessionId')";


		// get 2D array of results from the stored procedure:
		$getStoredProcResults = Results::db_all($query);
//
//		echo "<p>line 967 dump of getStoredProcResults in user search in flight</p>";
//		var_dump($getStoredProcResults);


		// set up array to hold all actual User Search results after processing the Stored Procedure Results
		$allFlightPathsArray = array();

		// convert the path within associative array to individual Flight objects for all origin + departure + date equal to $userOrigin,
		// $userDestination, and $userFlyDate range.  Do math with these objects, then add these objects and the math results to the array for all paths.
		foreach($getStoredProcResults as $a => $elementA) {

			try {

				// explode the path result from delimited string to array of strings
				// set up array to hold flight objects for each path
				// set up counter for loop
				$explodedPath = explode(",", $getStoredProcResults[$a]["path"]);
				$arrayOfPathFlightObjects[] = array();
//
//				echo "<p>line 992 dump of arrayOfPathFlightObjects at start of while loop</p>";
//				var_dump($arrayOfPathFlightObjects);

				$counterWithinPath = 0;

//				echo "<p>line 997 dump of explodedPath in user search in flight</p>";
//				var_dump($explodedPath);

				do {

					$singleFlight = intval($explodedPath[$counterWithinPath]);

//					echo "<p>line 998 dump of singleFlight, should be int in user search in flight</p>";
//					var_dump($singleFlight);

					$flightObject = Flight::getFlightByFlightId($mysqli, $singleFlight);
					$arrayOfPathFlightObjects[$counterWithinPath] = $flightObject;
					$counterWithinPath++;

				} while ($counterWithinPath < count($explodedPath));
//
//				echo "<p>line 1013 dump of arrayOfPathFlightObjects after do loop</p>";
//				var_dump($arrayOfPathFlightObjects);


				// before adding this 2D array to 3D array containing all paths, calc price and duration per path
				// get size of array for calc of price and duration
				$sizeEachFlightPath = $getStoredProcResults[$a]["Stops"] + 1;

				// calc discount for paths with multiple flights
				if($sizeEachFlightPath < 2) {
					$multipleFlightDiscount = 1;
				}
				else if($sizeEachFlightPath >= 2 &&  $sizeEachFlightPath <= 3) {
					$multipleFlightDiscount = 1 - (.25 * $sizeEachFlightPath);
				}
				else $multipleFlightDiscount = 0.40;

				// calc additional charge for nearby time windows.
				// first set today's date as of 12 noon to use as marker for calc.
				$today = DateTime::createFromFormat("H:i:s", "12:00:00");

				// then get difference with first flight Id's departure
				$firstFlightIdDeparture = $arrayOfPathFlightObjects[0]->getDepartureDateTime();
				$daysTillFlightInterval = $today->diff($firstFlightIdDeparture);
				$daysTillFlight = intval($daysTillFlightInterval->format("%d"));

//				echo "<p>line 1030 dump of daysTillFlight, should be int in user search in flight</p>";
//				var_dump($daysTillFlight);


				// set value of factor for each window
				if($daysTillFlight <= 7) {
					$timeWindowFactor = 1.75;
				}
				else if($daysTillFlight <= 14 && $daysTillFlight > 7) {
					$timeWindowFactor = 1.4;
				}
				else if($daysTillFlight <= 28 && $daysTillFlight > 14) {
					$timeWindowFactor = 1.15;
				}
				else $timeWindowFactor = 1;



				// calc total base price in path
				$sumBasePricesInPath = 0;

				foreach ($arrayOfPathFlightObjects as $b => $elementB) {
					$sumBasePricesInPath = $sumBasePricesInPath + $arrayOfPathFlightObjects[$b]->getPrice();

//					echo "<p>line 1055 dump of sumBasePricesInPath and price for this flight</p>";
//					var_dump($sumBasePricesInPath);
//					var_dump($arrayOfPathFlightObjects[$i]->getPrice());
//					var_dump($arrayOfPathFlightObjects[$i]);
				}

				// calc total price for the path using the discount and time factor and base price
				$totalPriceForPath = $timeWindowFactor * $multipleFlightDiscount * $sumBasePricesInPath;


				//Calc the duration
				$lastFlightIdArrival = 		$arrayOfPathFlightObjects[$sizeEachFlightPath-1]->getArrivalDateTime();
				$totalDurationForPath = 	$firstFlightIdDeparture->diff($lastFlightIdArrival);

				//push the duration and the price into the $arrayOfPathFlightObjects array
				$arrayOfPathFlightObjects[] = $totalDurationForPath;
				$arrayOfPathFlightObjects[] = $totalPriceForPath;
//
//				echo "<p>line 1078 dump of arrayOfPathFlightObjects before adding to allFlightPathsArray</p>";
//				var_dump($arrayOfPathFlightObjects);

				// put the array of objects into another array of all the possible flight paths each with all
				// relative data for each flight, with index equal to price of each path
				// $totalPriceForPathIndex = strval($totalPriceForPath);

				$allFlightPathsArray[] = $arrayOfPathFlightObjects;

//
//				echo "<p>line 1078 dump of departure date time in flight</p>";
//				var_dump($allFlightPathsArray[0][0]->getDepartureDateTime());


// fixme old code from other project for advanced pre-sorting.  delete if not needed.

//				// loop through the Lavu results, create a new Inventory object, calc par and pad it to create associative
//
//				// loop through the Lavu results, create a new Inventory object, calc par and prepend and postpend, i.e. pad, it to create associative
//				// index, then add object to associative array.
//				foreach($dataArray as $index => $element) {
//					$inventoryObject = new Inventory(	$element->title, $element->qty, $element->unit,
//						$element->low, $element->high, $element->id, $element->category, $element->cost,
//						$element->loc_id, $element->chain_reporting_group);
//					$zeros = "0000000000";
//					$par = round($inventoryObject->getPar(),4) * pow(10, 4);
//					$zerosLength = strlen($zeros);
//					$prePad = $zeros.$par;
//					$prePadLength = strlen($prePad);
//					$paddedPar = substr($prePad,$prePadLength-$zerosLength, $zerosLength);
//					$parIndex = $paddedPar.$inventoryObject->title;
//					$resultsArray[$parIndex] = $inventoryObject;
//







				// clear out the array of flight objects, price, and duration of paths, to be used again on next loop.
				unset ($arrayOfPathFlightObjects);
				unset ($totalDurationForPath);
				unset ($totalPriceForPath);
				unset ($sumBasePricesInPath);

//				echo "<p>line 1085 dump of allFlightPathsArray before relooping</p>";
//				var_dump($allFlightPathsArray);

			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception("Unable to convert row to Flight path", 0, $exception));
			}

		} // end while loop

//		echo "<p>line 1096 dump of allFlightPathsArray before RETURNING</p>";
//		var_dump($allFlightPathsArray);




		if(empty($allFlightPathsArray)) {
			// 404 path not found - return null
			return (null);
		} else {
			// sort final array by price using a usort, compare function and closure
			usort($allFlightPathsArray, "sortByPrice");

//			echo "IN FLIGHT ALL PATHS ARRAY AFTER SORT";
//			var_dump($allFlightPathsArray);

			return ($allFlightPathsArray);
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