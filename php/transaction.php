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
	 * product id for the Product; this is the primary key
	 **/
	private $productId;
	/**
	 * product name
	 **/
	private $productName;
	/**
	 * product description
	 **/
	private $description;
	/**
	 * product price
	 **/
	private $price;

	/**
	 * constructor for Product
	 *
	 * @param mixed $newProductId product id (or null if new object)
	 * @param string $newProductName product name
	 * @param string $newDescription description
	 * @param float $newPrice price
	 * @throws UnexpectedValueException when a parameter is of the wrong type
	 * @throws RangeException when a parameter is invalid
	 **/
	public function __construct($newProductId, $newProductName, $newDescription, $newPrice) {
		try {
			$this->setProductId($newProductId);
			$this->setProductName($newProductName);
			$this->setDescription($newDescription);
			$this->setPrice($newPrice);
		} catch(UnexpectedValueException $unexpectedValue) {
			// rethrow to the caller
			throw(new UnexpectedValueException("Unable to construct Product", 0, $unexpectedValue));
		} catch(RangeException $range) {
			// rethrow to the caller
			throw(new RangeException("Unable to construct Product", 0, $range));
		}
	}







}