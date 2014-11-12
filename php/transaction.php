<?php
/**
 * mySQL Enabled Transaction
 *
 * This is a mySQL enabled container for Transaction processing at an airline site selling tickets. It can easily be extended to include more fields as necessary.
 *
 * @author Paul Morbitzer <pmorbitz@gmail.com>
 **/

class Transaction {
	/**
	 * transaction id for the Transaction; this is the primary key
	 **/
	private $transactionId;
	/**
	 * profile id; this is a foreign key
	 **/
	private $profileId;
	/**
	 * amount of the transaction
	 **/
	private $amount;
	/**
	 * date the transaction was approved
	 **/
	private $dateApproved;
	/*
	 * card token for the transaction from API stripe.com
	 */
	private $cardToken;
	/*
	 * stripe token for the transaction from stripe.com
	 */
	private $stripeToken;

	/**
	 * constructor for Transaction
	 *
	 * @param mixed $newTransactionId transaction id (or null if new object)
	 * @param mixed $newProfileId profile id
	 * @param float $newAmount amount
	 * @param string $newDateApproved date approved
	 * @param string $newCardToken card token
	 * @param string $newStripeToken stripe token
	 * @throws UnexpectedValueException when a parameter is of the wrong type
	 * @throws RangeException when a parameter is invalid
	 **/
	public function __construct($newTransactionId, $newProfileId, $newAmount, $newDateApproved, $newCardToken, $newStripeToken) {
		try {
			$this->setTransactionId($newTransactionId);
			$this->setProfileId($newProfileId);
			$this->setAmount($newAmount);
			$this->setDateApproved($newDateApproved);
			$this->setCardToken($newCardToken);
			$this->setStripeToken($newStripeToken);
		} catch(UnexpectedValueException $unexpectedValue) {
			// rethrow to the caller
			throw(new UnexpectedValueException("Unable to construct Transaction", 0, $unexpectedValue));
		} catch(RangeException $range) {
			// rethrow to the caller
			throw(new RangeException("Unable to construct Transaction", 0, $range));
		}
	}







}