<?php
/**
 * mySQL enabled Ticket
 *
 * This is a mySQL enabled container for Ticket data at an airline site selling tickets for flights.
 *
 * @author Paul Morbitzer <pmorbitz@gmail.com>
 */

class Ticket {
	/*
	 * ticket id for the Ticket; this is the primary key
	 */
	private $ticketId;

	/*
	 * confirmation number for the Ticket; this is an unique field
	 */
	private $confirmationNumber;

	/*
	 * price of the Ticket
	 */
	private $price;

	/*
	 * the status of the Ticket; purchased, canceled, confirmed?
	 */
	private $status;

	/*
	 * the profile id for the purchaser; foreign key from Profile; indexed
	*/
	private $profileId;

	/*
	 * the traveler id for any additional travelers included in the Ticket purchase; foreign key from Traveler; indexed
	 */
	private $travelerId;

	/*
	 * the transaction id for the Ticket purchase; foreign key from Transaction
	 */
	private $transactionId;

	/*
	 * constructor for Ticket
	 *
	 * @param mixed $newTicketId ticket id (or null if new object)
	 * @param mixed $newConfirmationNumber confirmation number
	 * @param float $newPrice price
	 * @param mixed $newStatus status
	 * @param mixed $newProfileId profile id
	 * @param mixed $newTravelerId traveler id
	 * @param mixed $newTransactionId transaction
	 * @throws UnexpectedValueException when a parameter is of the wrong type
	 * @throw RangeException when a parameter is invalid
	*/

	public function __construct($newTicketId, $newConfirmationNumber, $newPrice, $newStatus, $newProfileId, $newTravelerId, $newTransactionId) {
		try {
			$this->setTicketId($newTicketId);
			$this->setConfirmationNumber($newConfirmationNumber);
			$this->setPrice($newPrice);
			$this->setStatus($newStatus);
			$this->setProfileId($newProfileId);
			$this->setTravelerId($newTravelerId);
			$this->setTransactionId($newTransactionId);
		} catch(UnexpectedValueException $unexpectedValue) {
			// rethrow the caller
			throw(new UnexpectedValueException("Unable to construct Ticket", 0, $unexpectedValue));
		} catch(RangeException $range) {
			// rethrow the caller
			throw(new RangeException("Unable to construct Ticket", 0, $range));
		}
	}

	/*
	 * gets the value of ticket id
	 *
	 * @return mixed ticket id (or null if new object)
	 */

	public function getTicketId() {
		return ($this->ticketId);
	}

	/*
	 * sets the value of ticket id
	 *
	 * @param mixed $newTicketId ticket id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if ticket id isn't positive
	 */
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
			throw(new RangeException("ticket it $newTicketId is not positive"));
		}

		// finally, take the ticket id out of quarantine and assign it
		$this->ticketId = $newTicketId;
	}


	/*
	 * gets the value of confirmation number
	 *
	 * @return string value of confirmation number
	 */
	public function getConfirmationNumber() {
		return ($this->confirmationNumber);
	}

	/*
	 * sets the value of confirmation number
	 *
	 * @param mixed $newConfirmationNumber confirmation number (10 hexadecimal bytes) (or null if active Ticket)
	 * @throws RangeException when input isn't 10 hexadecimal bytes
	 */
	public function setConfirmationNumber($newConfirmationNumber) {
		// zeroth, allow the confirmation number to be null if active object
		if($newConfirmationNumber === null) {
			$newConfirmationNumber = null;
			return;
		}

		// verify the confirmation number is 10 hex characters
		$newConfirmationNumber = trim($newConfirmationNumber);
		$newConfirmationNumber = strtolower($newConfirmationNumber);
		$filterOptions = array("options" => array("regexp" => "/^[\da-f]{10}$/"));
		if(filter_var($newConfirmationNumber, FILTER_VALIDATE_REGEXP, $filterOptions) === false) {
			throw(new RangeException("confirmation number is not 10 hexadecimal bytes"));
		}

		// finally, take the confirmation number out of quarantine
		$this->confirmationNumber = $newConfirmationNumber;
	}

	/*
	 * gets the price
	 *
	 * @return float value of price
	 */
	public function getPrice() {
		return ($this->price);
	}

	/*
	 * sets the price
	 *
	 * @param float $newPrice price
	 * @throws UnexpectedValuelException if not a double
	 * @throws RangeException if price isn't positive
	 */

	public function setPrice($newPrice) {
		// first, ensure the price is a double
		if(filter_var($newPrice, FILTER_VALIDATE_FLOAT) === false) {
			throw(new UnexpectedValueException("price $newPrice is not numeric"));
		}

		// second, convert the price to a double and enforce it's positive
		$newPrice = floatval($newPrice);
		if($newPrice <= 0) {
			throw(new RangeException("price $newPrice is not positive"));
		}

		// finally, take the price out of quarantine and assign it
		$this->price = $newPrice;
	}

	/**
	 * gets the value of status
	 *
	 * @return string status
	 **/
	public function getStatus() {
		return ($this->status);
	}

	/**
	 * sets the value of status
	 *
	 * @param string $newStatus status
	 **/
	public function setStatus($newStatus) {
		// filter the status as a generic string
		$newStatus = trim($newStatus);
		$newStatus = filter_var($newStatus, FILTER_SANITIZE_STRING);

		// then just take the status out of quarantine
		$this->status = $newStatus;
	}

	/*
	 * gets profile id
	 *
	 * @return mixed profile id
	 */
	public function getProfileId(){
		return($this->profileId);
	}

	/*
	 * sets profile id
	 *
	 * @param mixed $newProfileId profile id
	 * @throws UnexpectedValueException if not an integer
	 * @throws RangeException is not positive
	 */

	public function setProfileId($newProfileId) {
		// zeroth, set allow the profile id to be null if a new object
		if($newProfileId === null) {
			$this->profileId = null;
			return;
		}

		// first, ensure the profile id is an integer
		if(filter_var($newProfileId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("profile id $newProfileId is not numeric"));
		}

		// second, convert the profile id to an integer and enforce it's positive
		$newProfileId = intval($newProfileId);
		if($newProfileId <= 0) {
			throw(new RangeException("profile id $newProfileId is not positive"));
		}

		// finally, take the product id out of quarantine and assign it
		$this->profileId = $newProfileId;
	}

	/*
	 * get traveler id
	 *
	 * @returns mixed traveler id
	 */
	public function getTravelerId() {
		return($this->travelerId);
	}

	/*
	 * sets the value of traveler id
	 *
	 * @param mixed $newTravelerId traveler id
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if traveler id is not positive
	 */
	public function setTravelerId($newTravelerId) {
		// zeroth, set allow the traveler id to be null if a new object
		if($newTravelerId === null) {
			$this->travelerId = null;
			return;
		}

		// first, ensure the traveler id is an integer
		if(filter_var($newTravelerId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("traveler id $newTravelerId is not numeric"));
		}

		// second, convert the traveler id to an integer and enforce it's positive
		$newTravelerId= intval($newTravelerId);
		if($newTravelerId<= 0) {
			throw(new RangeException("traveler id $newTravelerId is not positive"));
		}

		// finally, take the traveler id out of quarantine and assign it
		$this->travelerId= $newTravelerId;
	}

	/*
	 * gets the value of transaction id
	 *
	 * @returns mixed transaction id
	 */
	public function getTransactionId() {
		return($this->transactionId);
	}

	/*
	 * sets transaction id
	 *
	 * @param mixed $newTransactionId transaction id (or null if new object)
	 * @throws UnexpectedValueException if not an integer
	 * @throws RangeException if traveler id isn't positive
	 */
	public function setTransactionId($newTransactionId) {
		// zeroth, set allow the transaction id to be null if a new object
		if($newTransactionId === null) {
			$this->transactionId = null;
			return;
		}

		// first, ensure the transaction id is an integer
		if(filter_var($newTransactionId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("transaction id $newTransactionId is not numeric"));
		}

		// second, convert the transaction id to an integer and enforce it's positive
		$newTransactionId= intval($newTransactionId);
		if($newTransactionId <= 0) {
			throw(new RangeException("transaction id $newTransactionId is not positive"));
		}

		// finally, take the transaction id out of quarantine and assign it
		$this->transactionId = $newTransactionId;
	}

	/**
	 * inserts this Ticket to mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the ticketId is null (i.e., don't insert a ticket that already exists)
		if($this->ticketId !== null) {
			throw(new mysqli_sql_exception("not a new ticket"));
		}

		// create query template
		$query     = "INSERT INTO ticket(confirmationNumber, price, status, profileId, travelerId, transactionId) VALUES (?, ?, ?, ?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("sdsiii", $this->confirmationNumber, $this->price,
																	$this->status, 				$this->profileId,
																	$this->travelerId, 			$this->transactionId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		// update the null ticketId with what mySQL just gave us
		$this->ticketId = $mysqli->insert_id;
	}

	/**
	 * deletes this Ticket from mySQL
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
		$query     = "DELETE FROM ticket WHERE ticketId = ?";
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
	 * updates this Ticket in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the ticketId is not null (i.e., don't update a ticket that hasn't been inserted)
		if($this->ticketId === null) {
			throw(new mysqli_sql_exception("Unable to update a ticket that does not exist"));
		}

		// create query template
		$query     = "UPDATE ticket SET confirmationNumber = ?, price = ?, status = ?, profileId = ?, travelerId = ?, transactionId = ? WHERE ticketId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("sdsiiii",  $this->confirmationNumber, $this->price,
																	  $this->status, 				  $this->profileId,
																	  $this->travelerId, 		  $this->transactionId,
																	  $this->ticketId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}

	/**
	 * gets the Ticket by TicketId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param mixed $ticketId ticket id to search for
	 * @return mixed Ticket found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getTicketByTicketId(&$mysqli, $ticketId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// first, ensure the ticket id is an integer
		if(filter_var($ticketId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("ticket id $ticketId is not numeric"));
		}

		// second, convert the ticket id to an integer and enforce it's positive
		$ticketId = intval($ticketId);
		if($ticketId <= 0) {
			throw(new RangeException("ticket id $ticketId is not positive"));
		}

		// create query template
		$query     = "SELECT ticketId, confirmationNumber, price, status, profileId, travelerId, transactionId FROM ticket WHERE ticketId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the ticketId to the place holder in the template
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

		// since this is a unique field, this will only return 0 or 1 results. So...
		// 1) if there's a result, we can make it into a Ticket object normally
		// 2) if there's no result, we can just return null
		$row = $result->fetch_assoc(); // fetch_assoc() returns a row as an associative array

		// convert the associative array to a Ticket
		if($row !== null) {
			try {
				$ticket = new Ticket($row["ticketId"],$row["confirmationNumber"], $row["price"], $row["status"], $row["profileId"], $row["travelerId"], $row["transactionId"]);
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception("Unable to convert row to Ticket", 0, $exception));
			}

			// if we got here, the Ticket is good - return it
			return($ticket);
		} else {
			// 404 Ticket not found - return null instead
			return(null);
		}
	}

	/**
	 * gets the Ticket by Confirmation Number
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $confirmationNumber confirmation number to search for
	 * @return mixed Ticket found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getTicketByConfirmationNumber(&$mysqli, $confirmationNumber) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the Confirmation Number before searching
		$confirmationNumber = trim($confirmationNumber);
		$confirmationNumber = filter_var($confirmationNumber, FILTER_SANITIZE_STRING);

		// create query template
		$query     = "SELECT ticketId, confirmationNumber, price, status, profileId, travelerId, transactionId FROM ticket WHERE confirmationNumber = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the email to the place holder in the template
		$wasClean = $statement->bind_param("s", $confirmationNumber);
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
		// 1) if there's a result, we can make it into a User object normally
		// 2) if there's no result, we can just return null
		$row = $result->fetch_assoc(); // fetch_assoc() returns a row as an associative array

		// convert the associative array to a Ticket
		if($row !== null) {
			try {
				$ticket = new Ticket($row["ticketId"],$row["confirmationNumber"], $row["price"], $row["status"], $row["profileId"], $row["travelerId"], $row["transactionId"]);
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception("Unable to convert row to User", 0, $exception));
			}

			// if we got here, the User is good - return it
			return($ticket);
		} else {
			// 404 Ticket not found - return null instead
			return(null);
		}
	}


	/**
	 * gets the Ticket by ProfileId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param mixed $profileId profile to search for
	 * @return mixed Ticket found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getTicketByProfileId(&$mysqli, $profileId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// first, ensure the profile id is an integer
		if(filter_var($profileId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("profile id $profileId is not numeric"));
		}

		// second, convert the profile id to an integer and enforce it's positive
		$profileId= intval($profileId);
		if($profileId<= 0) {
			throw(new RangeException("profile id $profileId is not positive"));
		}

		// create query template
		$query     = "SELECT ticketId, confirmationNumber, price, status, profileId, travelerId, transactionId FROM ticket WHERE profileId= ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the profileId to the place holder in the template
		$wasClean = $statement->bind_param("i", $profileId);
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
		// 1) if there's a result, we can make it into a Ticket object normally
		// 2) if there's no result, we can just return null
		$row = $result->fetch_assoc(); // fetch_assoc() returns a row as an associative array

		// convert the associative array to a Ticket
		if($row !== null) {
			try {
				$ticket = new Ticket($row["ticketId"],$row["confirmationNumber"], $row["price"], $row["status"], $row["profileId"], $row["travelerId"], $row["transactionId"]);
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception("Unable to convert row to Ticket", 0, $exception));
			}

			// if we got here, the Ticket is good - return it
			return($ticket);
		} else {
			// 404 Ticket not found - return null instead
			return(null);
		}
	}

	/**
	 * gets the Ticket by TravelerId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param mixed $travelerId traveler id to search for
	 * @return mixed Ticket found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getTicketByTravelerId(&$mysqli, $travelerId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// first, ensure the traveler id is an integer
		if(filter_var($travelerId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("traveler id $travelerId is not numeric"));
		}

		// second, convert the traveler id to an integer and enforce it's positive
		$travelerId= intval($travelerId);
		if($travelerId <= 0) {
			throw(new RangeException("traveler id $travelerId is not positive"));
		}

		// create query template
		$query     = "SELECT ticketId, confirmationNumber, price, status, profileId, travelerId, transactionId FROM ticket WHERE travelerId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the travelerId to the place holder in the template
		$wasClean = $statement->bind_param("i", $travelerId);
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
		// 1) if there's a result, we can make it into a Ticket object normally
		// 2) if there's no result, we can just return null
		$row = $result->fetch_assoc(); // fetch_assoc() returns a row as an associative array

		// convert the associative array to a Ticket
		if($row !== null) {
			try {
				$ticket = new Ticket($row["ticketId"],$row["confirmationNumber"], $row["price"], $row["status"], $row["profileId"], $row["travelerId"], $row["transactionId"]);
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception("Unable to convert row to Ticket", 0, $exception));
			}

			// if we got here, the User is good - return it
			return($ticket);
		} else {
			// 404 Ticket not found - return null instead
			return(null);
		}
	}

	/**
	 * gets the Ticket by TransactionId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param mixed $transactionId transaction id to search for
	 * @return mixed Ticket found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getTicketByTransactionId(&$mysqli, $transactionId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// first, ensure the transaction id is an integer
		if(filter_var($transactionId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("transaction id $transactionId is not numeric"));
		}

		// second, convert the transaction id to an integer and enforce it's positive
		$transactionId= intval($transactionId);
		if($transactionId <= 0) {
			throw(new RangeException("transaction id $transactionId is not positive"));
		}

		// create query template
		$query     = "SELECT ticketId, confirmationNumber, price, status, profileId, travelerId, transactionId FROM ticket WHERE transactionId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the transactionId to the place holder in the template
		$wasClean = $statement->bind_param("i", $transactionId);
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
		// 1) if there's a result, we can make it into a Ticket object normally
		// 2) if there's no result, we can just return null
		$row = $result->fetch_assoc(); // fetch_assoc() returns a row as an associative array

		// convert the associative array to a Ticket
		if($row !== null) {
			try {
				$ticket = new Ticket($row["ticketId"],$row["confirmationNumber"], $row["price"], $row["status"], $row["profileId"], $row["travelerId"], $row["transactionId"]);
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception("Unable to convert row to Ticket", 0, $exception));
			}

			// if we got here, the User is good - return it
			return($ticket);
		} else {
			// 404 Ticket not found - return null instead
			return(null);
		}
	}
}