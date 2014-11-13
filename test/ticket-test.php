<?php
/**
 * Uses Centralized mySQL configuration class
 *
 * This class is an implementation of the Singleton design pattern.
 * It is meant to be in a centralized directory under lock down.
 * The centralization of the configuration enhances security and reduces bugs due to human error.
 * To use it:
 * 1. require_once("/etc/apache2/capstone-mysql/group-name.php");
 * 2. $mysqli = MysqliConfiguration::getMysqli();
 *
 * @praise Dylan McDonald <dmcdonald21@cnm.edu>
 * @see http://php.net/manual/en/class.mysqli.php
 * @see http://en.wikipedia.org/wiki/Singleton_pattern
 **/

// first require the SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");

//then require the class under scrutiny
require_once("../php/ticket.php");

// require the mysqli
require_once("/etc/apache2/capstone-mysql/przm.php");

// require the classes for foreign keys
require_once("../php/user.php");
require_once("../php/profile.php");
require_once("../php/traveler.php");
require_once("../php/transaction.php");

// the TicketTest is a container for all our tests
class TicketTest extends UnitTestCase {
	// variable to hold the mySQL connection
	private $mysqli  = null;
	// variable to hold the test database row
	private $ticket = null;

	// a few "global" variables for creating test data
	private $CONFIRMATION_NUMBER  = "ABC123";
	private $PRICE					   = "100.00";
	private $STATUS	 			   = "Booked";
	private $USER						= null;
	private $PROFILE				   = null;
	private $TRAVELER		 		   = null;
	private $TRANSACTION 	   	= null;

	// setUp () is a method that is run before each test
	// here, we use it to connect to my SQL
	public function setUp() {
		$mysqli = MysqliConfiguration::getMysqli();

		$this->salt       = bin2hex(openssl_random_pseudo_bytes(32));
		$this->authToken = bin2hex(openssl_random_pseudo_bytes(16));
		$this->hash       = hash_pbkdf2("sha512", $this->password, $this->salt, 2048, 128);

		$this->USER = new User(null, "a@b.net", "Homer", "J", "Simpson", "1956-03-15", "Token");
		$this->USER->insert($mysqli);

		$this->PROFILE = new Profile(null, $this->USER->getUserId(), "Homer", "J", "Simpson", "1956-03-15", "Token");
		$this->PROFILE->insert($mysqli);

		$this->TRAVELER = new Traveler(null, "Marge", "J", "Simpson", "1956-10-01", $this->PROFILE->getProfileId());
		$this->TRAVELER->insert($mysqli);

		$this->TRANSACTION = new Transaction(null, $this->PROFILE->getProfileId(), "100.00", "2014-11-12", "Token", "Token");
		$this->TRANSACTION->insert($mysqli);


	}

	// tearDown () is a method that is run after each test
	// here, we use it to delete the test record and disconnect from mySQL
	public function tearDown() {
		// delete the profile if we can

		if($this->TRANSACTION !== null) {
			$this->TRANSACTION->delete($this-mysqli);
			$this->TRANSACTION = null;
		}

		if($this->TRAVELER !== null) {
			$this->TRAVELER->delete($this-mysqli);
			$this->TRAVELER = null;
		}

		if($this->PROFILE !== null) {
			$this->PROFILE->delete($this-mysqli);
			$this->PROFILE = null;
		}

		if($this->USER !== null) {
			$this->USER->delete($this-mysqli);
			$this->USER = null;
		}

		if($this->ticket !== null) {
			$this->ticket->delete($this->mysqli);
			$this->ticket = null;
		}

		// disconnect from mySQL
		// if($this->mysqli !== null) {
		// 	$this->mysqli->close();
		// }
	}

	// test creating a new Ticket and inserting it to mySQL
	public function testInsertNewTicket() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a ticket to post to mySQL
		$this->ticket = new Ticket(null, $this->CONFIRMATION_NUMBER, $this->PRICE, $this->STATUS, $this->PROFILE, $this->TRAVELER, $this->TRANSACTION);

		//third, insert the profile to mySQL
		$this->ticket->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->ticket->getTicketId());
		$this->assertTrue($this->ticket->getTicketId() > 0);
		$this->assertIdentical($this->ticket->getConfirmationNumber(), 	  $this->CONFIRMATION_NUMBER);
		$this->assertIdentical($this->ticket->getPrice(),  					  $this->PRICE);
		$this->assertIdentical($this->ticket->getStatus(), 					  $this->STATUS);
		$this->assertIdentical($this->ticket->getProfileId(), 				  $this->PROFILE->getProfileId());
		$this->assertIdentical($this->ticket->getTravelerId(), 				  $this->TRAVELER->getTravelerId());
		$this->assertIdentical($this->ticket->getTransactionId(), 			  $this->TRANSACTION->getTransactionId());
	}

	// test updating a Ticket
	public function testUpdateTicket() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a ticket to post to mySQL
		$this->ticket= new Ticket(null, $this->CONFIRMATION_NUMBER, $this->PRICE, $this->STATUS, $this->PROFILE, $this->TRAVELER, $this->TRANSACTION);

		// third, insert the ticket to mySQL
		$this->ticket->insert($this->mysqli);

		// fourth, update the ticket and post the changes to mySQL
		$newStatus = "Confirmed";
		$this->ticket->setStatus($newStatus);
		$this->ticket->update($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->ticket->getTicketId());
		$this->assertTrue($this->ticket->getTicketId() > 0);
		$this->assertIdentical($this->ticket->getConfirmationNumber(), $this->CONFIRMATION_NUMBER);
		$this->asertIdentical($this->ticket->getPrice(), 					$this->PRICE);
		$this->assertIdentical($this->ticket->getStatus(),				   $newStatus);
		$this->assertIdentical($this->ticket->getProfileId(),			   $this->PROFILE->getProfileId());
		$this->assertIdentical($this->ticket->getTravelerId(), 			$this->TRAVELER->getTravelerId());
		$this->assertIdentical($this->ticket->getTransactionId(),	   $this->TRANSACTION->getTransactionId());
	}

	// test deleting a Ticket
	public function testDeleteTicket() {
		// first, verify my SQL connected OK
		$this->assertNotNull($this->mysqli);


		// second, create a ticket to post to mySQL
		$this->ticket = new Ticket(null, $this->CONFIRMATION_NUMBER, $this->PRICE, $this->STATUS, $this->PROFILE, $this->TRAVELER, $this->TRANSACTION);

		// third, insert the ticket to mySQL
		$this->ticket->insert($this->mysqli);

		// fourth, verify the Ticket was inserted
		$this->assertNotNull($this->ticket->getTicketId());
		$this->assertTrue($this->ticket->getTicketId() > 0);
		$ticketId = $this->ticket->getTicketId();
		// fifth, delete the ticket
		$this->ticket->delete($this->mysqli);
		$this->ticket = null;

		// finally, try to get the ticket and assert we didn't get a thing
		$hopefulTicket = Ticket::getTicketByTicketId($this->mysqli, $this->$ticketId);
		$this->assertNull($hopefulTicket);
	}

	// test grabbing a Ticket from mySQL by ticket id
	public function testGetTicketByTicketId() {
		// first verify mySQL connected Ok
		$this->assertNotNull($this->mysqli);

		// second create a new ticket to post to mySQL
		$this->ticket = new Ticket(null, $this->CONFIRMATION_NUMBER, $this->PRICE, $this->STATUS, $this->PROFILE, $this->TRAVELER, $this->TRANSACTION);

		// third, insert the ticket to mySQL
		$this->ticket->insert($this->mysqli);

		// fourth, get the ticket using the static method
		$staticTicket = Ticket::getTicketByTicketId($this->mysqli, $this->ticket);

		// finally, compare the fields
		$this->assertNotNull($staticTicket->getTicketId());
		$this->assertTrue($staticTicket->getTicketId() > 0);
		$this->assertIdentical($staticTicket->getTicketId(), 				$this->ticket->getTicketId());
		$this->assertIdentical($staticTicket->getConfirmationNumber(), $this->CONFIRMATION_NUMBER);
		$this->assertIdentical($staticTicket->getPrice(), 					$this->PRICE);
		$this->assertIdentical($staticTicket->getStatus(),				   $this->STATUS);
		$this->assertIdentical($staticTicket->getProfileId(), 			$this->PROFILE->getProfileId());
		$this->assertIdentical($staticTicket->getTravelerId(), 			$this->TRAVELER->getTravelerId());
		$this->assertIdentical($staticTicket->getTransactionId(), 		$this->TRANSACTION->getTransactionId());
	}

	// test grabbing a Ticket from mySQL by confirmation number
	public function testGetTicketByConfirmationNumber() {
		// first verify mySQL connected Ok
		$this->assertNotNull($this->mysqli);

		// second create a new ticket to post to mySQL
		$this->ticket = new Ticket(null, $this->CONFIRMATION_NUMBER, $this->PRICE, $this->STATUS, $this->PROFILE->getProfileId(), $this->TRAVELER->getTravelerId(), $this->TRANSACTION->getTransactionId());

		// third, insert the ticket to mySQL
		$this->ticket->insert($this->mysqli);

		// fourth, get the ticket using the static method
		$staticTicket = Ticket::getTicketByConfirmationNumber($this->mysqli, $this->ticket);

		// finally, compare the fields
		$this->assertNotNull($staticTicket->getTicketId());
		$this->assertTrue($staticTicket->getTicketId() > 0);
		$this->assertIdentical($staticTicket->getTicketId(), 				$this->ticket->getTicketId());
		$this->assertIdentical($staticTicket->getConfirmationNumber(), $this->CONFIRMATION_NUMBER);
		$this->assertIdentical($staticTicket->getPrice(), 					$this->PRICE);
		$this->assertIdentical($staticTicket->getStatus(),				   $this->STATUS);
		$this->assertIdentical($staticTicket->getProfileId(), 			$this->PROFILE->getProfileId());
		$this->assertIdentical($staticTicket->getTravelerId(), 			$this->TRAVELER->getTravelerId());
		$this->assertIdentical($staticTicket->getTransactionId(), 		$this->TRANSACTION->getTransactionId());
	}



	// test grabbing a Ticket from mySQL by Profile Id
	public function testGetTicketByProfileId() {
		// first verify mySQL connected Ok
		$this->assertNotNull($this->mysqli);

		// second create a new ticket to post to mySQL
		$this->ticket = new Ticket(null, $this->CONFIRMATION_NUMBER, $this->PRICE, $this->STATUS, $this->PROFILE->getProfileId(), $this->TRAVELER->getTravelerId(), $this->TRANSACTION->getTransactionId());

		// third, insert the ticket to mySQL
		$this->ticket->insert($this->mysqli);

		// fourth, get the ticket using the static method
		$staticTicket = Ticket::getTicketByProfileId($this->mysqli, $this->ticket);

		// finally, compare the fields
		$this->assertNotNull($staticTicket->getTicketId());
		$this->assertTrue($staticTicket->getTicketId() > 0);
		$this->assertIdentical($staticTicket->getTicketId(), 				$this->ticket->getTicketId());
		$this->assertIdentical($staticTicket->getConfirmationNumber(), $this->CONFIRMATION_NUMBER);
		$this->assertIdentical($staticTicket->getPrice(), 					$this->PRICE);
		$this->assertIdentical($staticTicket->getStatus(),				   $this->STATUS);
		$this->assertIdentical($staticTicket->getProfileId(), 			$this->PROFILE->getProfileId());
		$this->assertIdentical($staticTicket->getTravelerId(), 			$this->TRAVELER->getTravelerId());
		$this->assertIdentical($staticTicket->getTransactionId(), 		$this->TRANSACTION->getTransactionId());
	}





	// test grabbing a Ticket from mySQL by Traveler Id
	public function testGetTicketByTravelerId() {
		// first verify mySQL connected Ok
		$this->assertNotNull($this->mysqli);

		// second create a new ticket to post to mySQL
		$this->ticket = new Ticket(null, $this->CONFIRMATION_NUMBER, $this->PRICE, $this->STATUS, $this->PROFILE->getProfileId, $this->TRAVELER->getTravelerId(), $this->TRANSACTION->getTransactionId());

		// third, insert the ticket to mySQL
		$this->ticket->insert($this->mysqli);

		// fourth, get the ticket using the static method
		$staticTicket = Ticket::getTicketByTravelerId($this->mysqli, $this->ticket);

		// finally, compare the fields
		$this->assertNotNull($staticTicket->getTicketId());
		$this->assertTrue($staticTicket->getTicketId() > 0);
		$this->assertIdentical($staticTicket->getTicketId(), 				$this->ticket->getTicketId());
		$this->assertIdentical($staticTicket->getConfirmationNumber(), $this->CONFIRMATION_NUMBER);
		$this->assertIdentical($staticTicket->getPrice(), 					$this->PRICE);
		$this->assertIdentical($staticTicket->getStatus(),				   $this->STATUS);
		$this->assertIdentical($staticTicket->getProfileId(), 			$this->PROFILE->getProfileId());
		$this->assertIdentical($staticTicket->getTravelerId(), 			$this->TRAVELER->getTravelerId());
		$this->assertIdentical($staticTicket->getTransactionId(), 		$this->TRANSACTION->getTransactionId());
	}

	// @todo test grabbing a Ticket from mySQL by Transaction Id




}