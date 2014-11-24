<?php
/**
 * test to verify the intersection TicketFlight
 *
 * @author Paul Morbitzer <pmorbitzer@gmail.com>
 */

// first require the SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");

//then require the class under scrutiny
require_once("../php/ticketFlight.php");

// require the mysqli
require_once("/etc/apache2/capstone-mysql/przm.php");

// require the classes for foreign keys
require_once("../php/ticket.php");
require_once("../php/flight.php");
require_once("../php/profile.php");
require_once("../php/user.php");
require_once("../php/transaction.php");
require_once("../php/traveler.php");
// the TicketFlightTest is a container for all our tests
class TicketFlightTest extends UnitTestCase {
	// variable to hold the mySQL connection
	private $mysqli = null;
	// variable to hold the test database row
	private $ticketFlight = null;

	// a few "global" variables for creating test data
	private $flight = null;
	private $ticket = null;
	private $transaction = null;
	private $traveler = null;
	private $profile = null;
	private $user = null;

	// setUp () is a method that is run before each test
	// here, we use it to connect to my SQL
	public function setUp() {
		$this->mysqli = MysqliConfiguration::getMysqli();

		$i = rand(1,1000);
		$testEmail       = "useremailsetup".$i."@test.com";
		$testSalt        = bin2hex(openssl_random_pseudo_bytes(32));
		$testAuthToken   = bin2hex(openssl_random_pseudo_bytes(16));
		$testHash        = hash_pbkdf2("sha512", "tEsTpASs", $testSalt, 2048, 128);

		$this->user = new User(null, $testEmail, $testHash, $testSalt, $testAuthToken);
		$this->user->insert($this->mysqli);

		$this->profile = new Profile(null, $this->user->getUserId(), "Jameson", "Harold", "Jenkins",
			"1956-12-01 00:00:00", "customer_000000000000000", $this->user);
		$this->profile->insert($this->mysqli);

		$this->traveler = new Traveler(null, $this->profile->__get("profileId"),
												 $this->profile->__get("userFirstName"),
												 $this->profile->__get("userMiddleName"),
												 $this->profile->__get("userLastName"),
			 									 $this->profile->__get("dateOfBirth"), $this->profile);
		$this->traveler->insert($this->mysqli);

		$testAmount = 111.11;
		$testDateApproved = DateTime::createFromFormat("Y-m-d H:i:s", "2014-11-20 07:08:09");
		$testCardToken = "card1238y823409u1234324yu7897";
		$testStripeToken = "stripe2139084jf0fa94fdghsrt78";

		$this->transaction = new Transaction(null, $this->profile->__get("profileId"),
															$testAmount, $testDateApproved,
															$testCardToken, $testStripeToken);

		$this->transaction->insert($this->mysqli);

		$this->flight = new Flight(null, "ABQ", "DFW", "01:42:00", "2014-12-30 08:00:00", "2014-12-30 09:42:00", "1234",
			100.00, 25);
		$this->flight->insert($this->mysqli);

		$testConfirmationNumber = bin2hex(openssl_random_pseudo_bytes(5));
		$this->ticket = new Ticket(null, $testConfirmationNumber, 100.00, "Booked",
												$this->profile->__get("profileId"),
												$this->traveler->__get("travelerId"),
												$this->transaction->getTransactionId());
		$this->ticket->insert($this->mysqli);

	}

	// tearDown () is a method that is run after each test
	// here, we use it to delete the test record and disconnect from mySQL
	public function tearDown() {
		// delete the object if we can
		if($this->ticketFlight !== null) {
			$this->ticketFlight->delete($this->mysqli);
			$this->ticketFlight = null;
		}

		if($this->ticket !== null) {
			$this->ticket->delete($this->mysqli);
			$this->ticket = null;
		}

		if($this->flight !== null) {
			$this->flight->delete($this->mysqli);
			$this->flight = null;
		}

		if($this->transaction !== null) {
			$this->transaction->delete($this->mysqli);
			$this->transaction = null;
		}

		if($this->traveler !== null) {
			$this->traveler->delete($this->mysqli);
			$this->traveler = null;
		}

		if($this->profile !== null) {
			$this->profile->delete($this->mysqli);
			$this->profile = null;
		}

		if($this->user !== null) {
			$this->user->delete($this->mysqli);
			$this->user = null;
		}
		echo "<p>All Objects Successfully Deleted -> tearDown 130</p>";



		// disconnect from mySQL
		// if($this->mysqli !== null) {
		// 	$this->mysqli->close();
		// }
	}

	// test creating a new TicketFlight and inserting it to mySQL
	public function testInsertNewTicketFlight() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a ticketFlight to post to mySQL

		$this->ticketFlight = new TicketFlight($this->flight->getFlightId(), $this->ticket->getTicketId());

		//third, insert the ticketFlight to mySQL
		$this->ticketFlight->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->ticketFlight->getFlightId());
		$this->assertTrue($this->ticketFlight->getFlightId() > 0);
		$this->assertNotNull($this->ticketFlight->getTicketId());
		$this->assertTrue($this->ticketFlight->getTicketId() > 0);
		$this->assertIdentical($this->ticketFlight->getFlightId(), 	$this->flight->getFlightId());
		$this->assertIdentical($this->ticketFlight->getTicketId(),  $this->ticket->getTicketId());

	}

	// test updating a ticketFlight
/*	public function testUpdateTicketFlight()
	{
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a ticketFlight to post to mySQL
		$this->ticketFlight = new TicketFlight(null, $this->flight->getFlightId(), $this->ticket->getTicketId());

		//third, insert the profile to mySQL
		$this->ticketFlight->insert($this->mysqli);
		$newFlightId = rand(1, 10000);
		$newTicketId = rand(1, 10000);
		$this->ticketFlight->setFlightId($newFlightId);
		$this->ticketFlight->setTicketId($newTicketId);
		$this->ticketFlight->update($this->mysqli);
		// finally, compare the fields
		$this->assertNotNull($this->ticketFlight->getFlightId());
		$this->assertTrue($this->ticketFlight->getFlightId() > 0);
		$this->assertNotNull($this->ticketFlight->getTicketId());
		$this->assertTrue($this->ticketFlight->getTicketId() > 0);
		$this->assertIdentical($this->ticketFlight->getFlightId(), $newFlightId);
		$this->assertIdentical($this->ticketFlight->getTicketId(), $newTicketId);
	}*/

	// test deleting a ticketFlight
	public function testDeleteTicketFlight() {
		// first, verify my SQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a ticketFlight to post to mySQL
		$this->ticketFlight = new TicketFlight($this->flight->getFlightId(),
															$this->ticket->getTicketId());

		//third, insert the ticketFlight to mySQL
		$this->ticketFlight->insert($this->mysqli);
		$localFlightId = $this->ticketFlight->getFlightId();
		// fourth, verify the ticketFlight was inserted
		$this->assertNotNull($this->ticketFlight->getFlightId());
		$this->assertTrue($this->ticketFlight->getFlightId() > 0);
		$this->assertNotNull($this->ticketFlight->getTicketId());
		$this->assertTrue($this->ticketFlight->getTicketId() > 0);
		$flightId = $this->ticketFlight->getFlightId();
		$ticketId = $this->ticketFlight->getTicketId();

		// fifth, delete the ticket
		$this->ticketFlight->delete($this->mysqli);
		$this->ticketFlight = null;

		// finally, try to get the ticketFlight and assert we didn't get a thing
		$hopefulTicketFlight = TicketFlight::getTicketFlightByFlightId($this->mysqli, $localFlightId);
		$this->assertNull($hopefulTicketFlight);

		$hopefulTicketFlight = TicketFlight::getTicketFlightByTicketId($this->mysqli,
																							$this->ticket->getTicketId());
		$this->assertNull($hopefulTicketFlight);


	}

	// test grabbing a ticketFlight from mySQL
	public function testGetTicketFlightByTicketId() {
		// first verify mySQL connected Ok
		$this->assertNotNull($this->mysqli);

		// second create a new ticketFlight to post to mySQL
		$this->ticketFlight = new TicketFlight($this->flight->getFlightId(),
															$this->ticket->getTicketId());

		// third, insert the ticketFlight to mySQL
		$this->ticketFlight->insert($this->mysqli);

		// fourth, get the ticketFlight using the static method
		$staticTicketFlight = TicketFlight::getTicketFlightByTicketId($this->mysqli,
			$this->ticketFlight->getTicketId());

		// finally, compare the fields
		$this->assertNotNull($staticTicketFlight->getFlightId());
		$this->assertTrue($staticTicketFlight->getFlightId() > 0);
		$this->assertNotNull($staticTicketFlight->getTicketId());
		$this->assertTrue($staticTicketFlight->getTicketId() > 0);
		$this->assertIdentical($staticTicketFlight->getFlightId(), $this->ticketFlight->getFlightId());
		$this->assertIdentical($staticTicketFlight->getTicketId(), $this->ticketFlight->getTicketId());

	}

	// test grabbing a ticketFlight from mySQL
	public function testGetTicketFlightByFlightId() {
		// first verify mySQL connected Ok
		$this->assertNotNull($this->mysqli);

		// second create a new ticketFlight to post to mySQL
		$this->ticketFlight = new TicketFlight($this->flight->getFlightId(),
															$this->ticket->getTicketId());

		// third, insert the ticketFlight to mySQL
		$this->ticketFlight->insert($this->mysqli);

		// fourth, get the ticketFlight using the static method
		$staticTicketFlight = TicketFlight::getTicketFlightByFlightId($this->mysqli,
			$this->ticketFlight->getFlightId());

		// finally, compare the fields
		$this->assertNotNull($staticTicketFlight->getFlightId());
		$this->assertTrue($staticTicketFlight->getFlightId() > 0);
		$this->assertNotNull($staticTicketFlight->getTicketId());
		$this->assertTrue($staticTicketFlight->getTicketId() > 0);
		$this->assertIdentical($staticTicketFlight->getFlightId(), $this->ticketFlight->getFlightId());
		$this->assertIdentical($staticTicketFlight->getTicketId(), $this->ticketFlight->getTicketId());

	}
}

