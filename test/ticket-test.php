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
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @see http://php.net/manual/en/class.mysqli.php
 * @see http://en.wikipedia.org/wiki/Singleton_pattern
 **/

// first require the SimpleTest framework
require_once("/etc/apache2/capstone-mysql/przm.php");
$mysqli = MysqliConfiguration::getMysqli();

//then require the class under scrutiny
require_once("../php/ticket.php");

// the ProfileTest is a container for all our tests
class TicketTest extends UnitTestCase {
	// variable to hold the mySQL connection
	private $mysqli  = null;
	// variable to hold the test database row
	private $ticket = null;

	// a few "global" variables for creating test data
	private $TICKETID 	  		  = 1;
	private $CONFIRMATIONNUMBER  = "ABC123";
	private $PRICE					  = "100.00";
	private $STATUS	 			  = "Booked";
	private $PROFILEID 			  = 1;
	private $TRAVELERID 			  = 1;
	private $TRANSACTIONID 		  = 1;

	// setUp () is a method that is run before each test
	// here, we use it to connect to my SQL
	public function setUp() {
		mysqli_report(MYSQLI_REPORT_STRICT);
		$this->mysqli = new mysqli("localhost", "store_paul", "deepdive", "store_paul");
	}

	// tearDown () is a method that is run after each test
	// here, we use it to delete the test record and disconnect from mySQL
	public function tearDown() {
		// delete the profile if we can
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

		// second, create a user to post to mySQL
		$this->ticket = new Ticket(null, $this->CONFIRMATIONNUMBER, $this->PRICE, $this->STATUS, $this->PROFILEID, $this->TRAVELERID, $this->TRANSACTIONID);

		//third, insert the profile to mySQL
		$this->ticket->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->ticket->getTicketId());
		$this->assertTrue($this->ticket->getTicketId() > 0);
		$this->assertIdentical($this->ticket->getConfirmationNumber(), 	  $this->CONFIRMATIONNUMBER);
		$this->assertIdentical($this->ticket->getPrice(),  					  $this->PRICE);
		$this->assertIdentical($this->ticket->getStatus(), 					  $this->STATUS);
		$this->assertIdentical($this->ticket->getProfileId(), 				  $this->PROFILEID);
		$this->assertIdentical($this->ticket->getTravelerId(), 				  $this->TRAVELERID);
		$this->assertIdentical($this->ticket->getTransactionId(), 			  $this->TRANSACTIONID);
	}

	// test updating a Ticket
	public function testUpdateTicket() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a profile to post to mySQL
		$this->ticket= new Ticket(null, $this->CONFIRMATIONNUMBER, $this->PRICE, $this->STATUS, $this->PROFILEID, $this->TRAVELERID, $this->TRANSACTIONID);

		// third, insert the profile to mySQL
		$this->ticket->insert($this->mysqli);

		// fourth, update the profile and post the changes to mySQL
		$newStatus = "Confirmed";
		$this->ticket->setStatus($newStatus);
		$this->ticket->update($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->ticket->getTicketId());
		$this->assertTrue($this->ticket->getTicketId() > 0);
		$this->assertIdentical($this->ticket->getConfirmationNumber(), $this->CONFIRMATIONNUMBER);
		$this->asertIdentical($this->ticket->getPrice(), 					$this->PRICE);
		$this->assertIdentical($this->ticket->getStatus(),				   $newStatus);
		$this->assertIdentical($this->ticket->getProfileId(),			   $this->PROFILEID);
		$this->assertIdentical($this->ticket->getTravelerId(), 			$this->TRAVELERID);
		$this->assertIdentical($this->ticket->getTransactionId(),	   $this->TRANSACTIONID);
	}

	// test deleting a Ticket
	public function testDeleteTicket() {
		// first, verify my SQL connected OK
		$this->assertNotNull($this->mysqli);


		// second, create a ticket to post to mySQL
		$this->ticket = new Ticket(null, $this->CONFIRMATIONNUMBER, $this->PRICE, $this->STATUS, $this->PROFILEID, $this->TRAVELERID, $this->TRANSACTIONID);

		// third, insert the ticket to mySQL
		$this->ticket->insert($this->mysqli);

		// fourth, verify the Ticket was inserted
		$this->assertNotNull($this->ticket->getTicketId());
		$this->assertTrue($this->ticket->getTicketId() > 0);
		$profileId = $this->ticket->getTicketId();
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
		$this->ticket = new Ticket(null, $this->CONFIRMATIONNUMBER, $this->PRICE, $this->STATUS, $this->PROFILEID, $this->TRAVELERID, $this->TRANSACTIONID);

		// third, insert the ticket to mySQL
		$this->ticket->insert($this->mysqli);

		// fourth, get the ticket using the static method
		$staticTicket = Ticket::getTicketByTicketId($this->mysqli, $this->ticket);

		// finally, compare the fields
		$this->assertNotNull($staticTicket->getTicketId());
		$this->assertTrue($staticTicket->getTicketId() > 0);
		$this->assertIdentical($staticTicket->getTicketId(), 				$this->ticket->getTicketId());
		$this->assertIdentical($staticTicket->getConfirmationNumber(), $this->CONFIRMATIONNUMBER);
		$this->assertIdentical($staticTicket->getPrice(), 					$this->PRICE);
		$this->assertIdentical($staticTicket->getStatus(),				   $this->STATUS);
		$this->assertIdentical($staticTicket->getProfileId(), 			$this->PROFILEID);
		$this->assertIdentical($staticTicket->getTravelerId(), 			$this->TRAVELERID);
		$this->assertIdentical($staticTicket->getTransactionId(), 		$this->TRANSACTIONID);
	}

	// test grabbing a Ticket from mySQL by confirmation number
	// @todo finish this test
	public function testGetTicketByConfirmationNumber() {
		// first verify mySQL connected Ok
		$this->assertNotNull($this->mysqli);

		// second create a new ticket to post to mySQL
		$this->ticket = new Ticket(null, $this->CONFIRMATIONNUMBER, $this->PRICE, $this->STATUS, $this->PROFILEID, $this->TRAVELERID, $this->TRANSACTIONID);

		// third, insert the ticket to mySQL
		$this->ticket->insert($this->mysqli);

		// fourth, get the ticket using the static method
		$staticTicket = Ticket::getTicketByConfirmationNumber($this->mysqli, $this->ticket);

		// finally, compare the fields
		$this->assertNotNull($staticTicket->getTicketId());
		$this->assertTrue($staticTicket->getTicketId() > 0);
		$this->assertIdentical($staticTicket->getTicketId(), 				$this->ticket->getTicketId());
		$this->assertIdentical($staticTicket->getConfirmationNumber(), $this->CONFIRMATIONNUMBER);
		$this->assertIdentical($staticTicket->getPrice(), 					$this->PRICE);
		$this->assertIdentical($staticTicket->getStatus(),				   $this->STATUS);
		$this->assertIdentical($staticTicket->getProfileId(), 			$this->PROFILEID);
		$this->assertIdentical($staticTicket->getTravelerId(), 			$this->TRAVELERID);
		$this->assertIdentical($staticTicket->getTransactionId(), 		$this->TRANSACTIONID);
	}



	// test grabbing a Ticket from mySQL by Profile Id







	// test grabbing a Ticket from mySQL by Traveler Id






}