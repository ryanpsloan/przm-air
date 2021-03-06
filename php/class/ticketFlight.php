<?php
/**
 *
 * mySQL Enabled ticketFlight
 *
 * This is a mySQL enabled container for ticketFlight data that will connect tickets and flights.
 *
 * @author Paul Morbitzer <pmorbitz@gmail.com>
 */

require_once("flight.php");
require_once("ticket.php");

class TicketFlight {
	/*
	 * flight Id for ticketFlight; this is a foreign key from flight
	 */
	private $flightId;
	/*
	 * ticket Id for ticketFlight; this is a foreign key from ticket
	 */
	private $ticketId;


	/**
	 * constructor for ticketFlight
	 *
	 * @param mixed $newFlightId flight Id  (or null if new object)
	 * @param mixed $newTicketId ticket Id
	 * @throws UnexpectedValueException when a parameter is of the wrong type
	 * @throws RangeException when a parameter is invalid
	 **/
	public function __construct($newFlightId, $newTicketId) {
		try {
			$this->setFlightId($newFlightId);
			$this->setTicketId($newTicketId);
		} catch(UnexpectedValueException $unexpectedValue) {
			// rethrow to the caller
			throw(new UnexpectedValueException("Unable to construct TicketFlight", 0, $unexpectedValue));
		} catch(RangeException $range) {
			// rethrow to the caller
			throw(new RangeException("Unable to construct TicketFlight", 0, $range));
		}
	}

	/*
	 * gets the value of flight id
	 *
	 * @return mixed flight id (or null if new object)
	*/

	public function getFlightId() {
		return($this->flightId);
	}

	/**
	 * sets the value of flight id
	 *
	 * @param mixed $newFlightId flight id (or null if new object)
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
		$this->flightId= $newFlightId;
	}


	/**
	 * gets the value of ticket id
	 *
	 * @return mixed ticket id (or null if new object)
	 **/
	public function getTicketId() {
		return($this->ticketId);
	}

	/**
	 * sets the value of ticket id
	 *
	 * @param mixed $newTicketId flight id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if ticket id isn't positive
	 **/
	public function setTicketId($newTicketId) {
		// zeroth, set allow the ticket id to be null if a new object
		if($newTicketId === null) {
			$this->ticketId = null;
			return;
		}

		// first, ensure the ticket id is an integer
		if(filter_var($newTicketId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("ticket id $newTicketId is not numeric"));
		}

		// second, convert the ticket id to an integer and enforce it's positive
		$newTicketId = intval($newTicketId);
		if($newTicketId <= 0) {
			throw(new RangeException("ticket id $newTicketId is not positive"));
		}

		// finally, take the ticket id out of quarantine and assign it
		$this->ticketId = $newTicketId;
	}

	/**
	 * inserts this TicketFlight to mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli)
	{
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// create query template
		$query = "INSERT INTO ticketFlight(flightId, ticketId) VALUES(?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ii", $this->flightId, $this->ticketId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}
	/**
	 * deletes this TicketFlight from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the ticketId is not null (i.e., don't delete a ticket that hasn't been inserted)
		if($this->ticketId === null) {
			throw(new mysqli_sql_exception("Unable to delete a ticket that does not exist"));
		}

		// create query template
		$query     = "DELETE FROM ticketFlight WHERE ticketId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("i", $this->ticketId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}

	/**
	 * gets the TicketFlight by flightId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $flightId to search for
	 * @return mixed array of TicketId found and Flight Id found, or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getTicketFlightByFlightId(&$mysqli, $flightId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the description before searching
		$flightId= trim($flightId);
		$flightId= filter_var($flightId, FILTER_SANITIZE_NUMBER_INT);

		// create query template
		$query     = "SELECT flightId, ticketId FROM ticketFlight WHERE flightId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the flight id to the place holder in the template
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

		// build an array of ticketFlights
		$ticketFlights= array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$ticketFlight = new TicketFlight($row["flightId"], $row["ticketId"]);
				$ticketFlights[] = $ticketFlight;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception("Unable to convert row to TicketFlight", 0, $exception));
			}
		}

		// count the results in the array and return:
		// 1) null if 0 results
		// 2) a single object if 1 result
		// 3) the entire array if > 1 result
		$numberOfTicketFlights = count($ticketFlights);
		if($numberOfTicketFlights === 0) {
			return(null);
		} else if($numberOfTicketFlights === 1) {
			return($ticketFlights[0]);
		} else {
			return($ticketFlights);
		}
	}

	/**
	 * gets the TicketFlight by ticketId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $ticketId to search for
	 * @return mixed array of TicketId found and Flight Id found, or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getTicketFlightByTicketId(&$mysqli, $ticketId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the description before searching
		$ticketId = trim($ticketId);
		$ticketId = filter_var($ticketId, FILTER_SANITIZE_NUMBER_INT);

		// create query template
		$query     = "SELECT flightId, ticketId FROM ticketFlight WHERE ticketId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the ticket id to the place holder in the template
		$wasClean = $statement->bind_param("i", $ticketId);
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

		// build an array of ticketFlights
		$ticketFlights= array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$ticketFlight = new TicketFlight($row["flightId"], $row["ticketId"]);
				$ticketFlights[] = $ticketFlight;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception("Unable to convert row to TicketFlight", 0, $exception));
			}
		}

		// count the results in the array and return:
		// 1) null if 0 results
		// 2) a single object if 1 result
		// 3) the entire array if > 1 result
		$numberOfTicketFlights = count($ticketFlights);
		if($numberOfTicketFlights === 0) {
			return(null);
		} else if($numberOfTicketFlights === 1) {
			return($ticketFlights[0]);
		} else {
			return($ticketFlights);
		}
	}
}

