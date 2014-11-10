<?php
/**
 *
 * mySQL Enabled ticketFlight
 *
 * This is a mySQL enabled container for ticketFlight data at an airline site selling tickets for flights.
 *
 * @author Paul Morbitzer <pmorbitz@gmail.com>
 */

class TicketFlight {
	/*
	 * flight Id for ticketFlight; this is a foreign key from flight
	 */
	private $flightId;
	/*
	 * ticket Id for ticketFlight; this is a foreign key from profile
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
	public function insert(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// create query template
		$query     = "INSERT INTO ticketFlight(flightId, ticketId) VALUES(?, ?)";
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

		// update the ticketId with what mySQL just gave us
		$this->ticketId = $mysqli->insert_id;
	}


}


/**
 * Created by PhpStorm.
 * User: paulmorbitzer
 * Date: 11/6/14
 * Time: 10:18
 */
/**
 * @Paul Hi Paul,
 *    Ryan
 *
 */

//Discussion area filled with a list of questions you want to solve
//=======================================================================
/**
 * -> Hi Paul this is the Discussion board, we are inside a block comment so everything we place in here is invisible
 * to the program
 *
 *
 *
 *
 */

//1 I am at your assistance paul, feel free to leave questions here I will help you when I have extra time
//2 If you prefer to work alone, please let  me know in note in my code somewhere I will find it easily (top of
//traveler class) and I will not visit or assist on your classes

//copy the user class example and modify it for this class
//how many fields are there in the class's sql table? That's how many properties you'll need.
//every property has two methods associated with it, getProperty setProperty you'll need to write those too