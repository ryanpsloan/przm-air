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
	private $transaction;

	/*
	 * constructor for Ticket
	 *
	 * @param mixed $newTicketId ticket id (or null if new object)
	 * @param mixed $newConfirmationNumber confirmation number
	 * @param float $newPrice price
	 * @param mixed $newStatus status
	 * @param mixed $newProfileId profile id
	 * @param mixed $newTravelerId traveler id
	 * @param mixed $newTransaction transaction
	 * @throws UnexpectedValueException when a parameter is of the wrong type
	 * @throw RangeException when a parameter is invalid
	*/

	public function __construct($newTicketId, $newConfirmationNumber, $newPrice, $newStatus, $newProfileId, $newTravelerId, $newTransaction) {
		try {
			$this->setTicketId($newTicketId);
			$this->setConfirmationNumber($newConfirmationNumber);
			$this->setPrice($newPrice);
			$this->setStatus($newStatus);
			$this->setProfileId($newProfileId);
			$this->setTravelerId($newTravelerId);
			$this->setTransaction($newTransaction);
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
		return($this->ticketId);
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


}