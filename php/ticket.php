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

		// second, convert the ticket id to an integer and enforce it's positve
		$newTicketId = intval($newTicketId);
		if($newTicketId <= 0) {
			throw(new RangeException("ticket it $newTicketId is not positive"));
		}

		// finally, take the tickt id out of quarantine and assign it
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
			$this->priceId = null;
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
	 * @param mixed $newTravelerId traveler id (or null if new object)
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
		$this->transaction= $newTransactionId;
	}



}