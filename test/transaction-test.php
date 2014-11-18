<?php
 /**
  * test to verify the Transaction
  *
  * @author Paul Morbitzer <pmorbitz@gamil.com>
  */


// first require the SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");

//then require the class under scrutiny
require_once("../php/ticket.php");

// require the mysqli
require_once("/etc/apache2/capstone-mysql/przm.php");

// require the classes for foreign key
require_once("../php/user.php");
require_once("../php/profile.php");

// the TransactionTest is a container for all our tests
class TransactionTest extends UnitTestCase
{
	// variable to hold the mySQL connection
	private $mysqli = null;
	// variable to hold the test database row
	private $transaction = null;

// a few "global" variables for creating test data
	private $AMOUNT = "100.00";
	private $DATE_APPROVED = "2014-11-11 12:00:00";
	private $CARD_TOKEN = 1;
	private $STRIPE_TOKEN = 1;
	private $USER = null;
	private $PROFILE = null;

// setUp () is a method that is run before each test
	// here, we use it to connect to my SQL
	public function setUp()
	{
		$mysqli = MysqliConfiguration::getMysqli();

		$salt = bin2hex(openssl_random_pseudo_bytes(32));
		$authenticationToken = bin2hex(openssl_random_pseudo_bytes(16));
		$hash = hash_pbkdf2("sha512", "password", $salt, 2048, 128);

		$this->USER = new User(null, "a@b.net", $hash, $salt, $authenticationToken);
		$this->USER->insert($mysqli);

		$this->PROFILE = new Profile(null, $this->USER->getUserId(), "Homer", "J", "Simpson", "1956-03-15", "Token");
		$this->PROFILE->insert($mysqli);
	}

	// tearDown () is a method that is run after each test
	// here, we use it to delete the test record and disconnect from mySQL
	public function tearDown()
	{
		// delete the profile if we can

		if($this->PROFILE !== null) {
			$this->PROFILE->delete($this->mysqli);
			$this->PROFILE = null;
		}

		if($this->USER !== null) {
			$this->USER->delete($this->mysqli);
			$this->USER = null;
		}

		if($this->transaction !== null) {
			$this->transaction->delete($this->mysqli);
			$this->transaction = null;
		}
		// disconnect from mySQL
		// if($this->mysqli !== null) {
		// $this->mysqli->close();
		// }
	}

	// test creating a new Transaction and inserting it to mySQL
	public function testInsertNewTransaction() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a transaction to post to mySQL
		$this->transaction = new Transaction(null, $this->PROFILE->getProfleId(), $this->AMOUNT, $this->DATE_APPROVED, $this->CARD_TOKEN, $this->STRIPE_TOKEN);

		//third, insert the profile to mySQL
		$this->transaction->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->transaction->getTransactionId());
		$this->assertTrue($this->transaction->getTransactionId() > 0);
		$this->assertIdentical($this->transaction->getProfileId(), 	$this->PROFILE->getProfileId());
		$this->assertIdentical($this->transaction->getAmount(),  		$this->AMOUNT);
		$this->assertIdentical($this->transaction->getDateApproved(), $this->DATE_APPROVED);
		$this->assertIdentical($this->transaction->getCardToken(), 	$this->CARD_TOKEN);
		$this->assertIdentical($this->transaction->getStripeToken(), 	$this->STRIPE_TOKEN);
	}

	// test updating a Transaction
	public function testUpdateTransaction() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a transaction to post to mySQL
		$this->transaction = new Transaction(null, $this->PROFILE->getProfleId(), $this->AMOUNT, $this->DATE_APPROVED, $this->CARD_TOKEN, $this->STRIPE_TOKEN);

		//third, insert the profile to mySQL
		$this->transaction->insert($this->mysqli);

		// fourth, update the transaction and post the changes to mySQL
		$newAmount = "200.00";
		$this->transaction->setAmount($newAmount);
		$this->transaction->update($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->transaction->getTransactionId());
		$this->assertTrue($this->transaction->getTransactionId() > 0);
		$this->assertIdentical($this->transaction->getProfileId(), 	$this->PROFILE->getProfileId());
		$this->assertIdentical($this->transaction->getAmount(),  		$newAmount);
		$this->assertIdentical($this->transaction->getDateApproved(), $this->DATE_APPROVED);
		$this->assertIdentical($this->transaction->getCardToken(), 	$this->CARD_TOKEN);
		$this->assertIdentical($this->transaction->getStripeToken(), 	$this->STRIPE_TOKEN);
	}

	// test deleting a Transaction
	public function testDeleteTransaction() {
		// first, verify my SQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a transaction to post to mySQL
		$this->transaction = new Transaction(null, $this->PROFILE->getProfleId(), $this->AMOUNT, $this->DATE_APPROVED, $this->CARD_TOKEN, $this->STRIPE_TOKEN);

		//third, insert the profile to mySQL
		$this->transaction->insert($this->mysqli);

		// fourth, verify the Transaction was inserted
		$this->assertNotNull($this->transaction->getTicketId());
		$this->assertTrue($this->transaction->getTicketId() > 0);
		$transactionId = $this->transaction->getTicketId();
		// fifth, delete the ticket
		$this->transaction->delete($this->mysqli);
		$this->transaction = null;

		// finally, try to get the transaction and assert we didn't get a thing
		$hopefulTransaction = Transaction::getTransactionByTransactionId($this->mysqli, $this->$transactionId);
		$this->assertNull($hopefulTransaction);


	}

	// test grabbing a Transaction from mySQL
	public function testGetTransactionByTransactionId() {
		// first verify mySQL connected Ok
		$this->assertNotNull($this->mysqli);

		// second create a new transaction to post to mySQL
		$this->transaction = new Transaction(null, $this->PROFILE->getProfileId(), $this->AMOUNT, $this->DATE_APPROVED, $this->CARD_TOKEN, $this->STRIPE_TOKEN);

		// third, insert the transaction to mySQL
		$this->transaction->insert($this->mysqli);

		// fourth, get the transaction using the static method
		$staticTransaction = Transaction::getTransactionByTransactionId($this->mysqli, $this->transaction->getTransactionId()					);

		// finally, compare the fields
		$this->assertNotNull($staticTransaction->getTransactionId());
		$this->assertTrue($staticTransaction->getTransactionId() > 0);
		$this->assertIdentical($staticTransaction->getTransactionId(), $this->transaction->getTransactionId());
		$this->assertIdentical($staticTransaction->getProfileId(), 		$this->PROFILE->getProfileId);
		$this->assertIdentical($staticTransaction->getAmount(), 			$this->AMOUNT);
		$this->assertIdentical($staticTransaction->getDateApproved(), 	$this->DATE_APPROVED);
		$this->assertIdentical($staticTransaction->getCardToke(), 		$this->CARD_TOKEN);
		$this->assertIdentical($staticTransaction->getStripeToken(), 	$this->STRIPE_TOKEN);
	}

}