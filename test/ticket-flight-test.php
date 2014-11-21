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
	private $FLIGHT = null;
	private $TICKET = null;
	private $TRANSACTION = null;
	private $TRAVELER = null;
	private $PROFILE = null;
	private $USER = null;

	// setUp () is a method that is run before each test
	// here, we use it to connect to my SQL
	public function setUp() {
		$this->mysqli = MysqliConfiguration::getMysqli();

		$i = rand(1,1000);
		$testEmail       = "useremailsetup".$i."@test.com";
		$testSalt        = bin2hex(openssl_random_pseudo_bytes(32));
		$testAuthToken   = bin2hex(openssl_random_pseudo_bytes(16));
		$testHash        = hash_pbkdf2("sha512", "tEsTpASs", $testSalt, 2048, 128);

		$this->USER = new User(null, $testEmail, $testHash, $testSalt, $testAuthToken);
		$this->USER->insert($this->mysqli);
		echo "<p>USER created -> setUp 45</p>";
		var_dump($this->USER);

		$this->PROFILE = new Profile(null, $this->USER->getUserId(), "Jameson", "Harold", "Jenkins",
			"1956-12-01 00:00:00", "customer_000000000000000", $this->USER);
		$this->PROFILE->insert($this->mysqli);
		echo "<p>PROFILE created -> setUp 50</p>";
		var_dump($this->PROFILE);

		$this->TRAVELER = new Traveler(null, $this->PROFILE->__get("profileId"),
												 $this->PROFILE->__get("userFirstName"),
												 $this->PROFILE->__get("userMiddleName"),
												 $this->PROFILE->__get("userLastName"),
			 									 $this->PROFILE->__get("dateOfBirth"), $this->PROFILE);
		$this->TRAVELER->insert($this->mysqli);
		echo "<p>TRAVELER created -> setUp 60</p>";
		var_dump($this->TRAVELER);

		$testAmount = 111.11;
		$testDateApproved = DateTime::createFromFormat("Y-m-d H:i:s", "2014-11-20 07:08:09");
		$testCardToken = "card1238y823409u1234324yu7897";
		$testStripeToken = "stripe2139084jf0fa94fdghsrt78";

		$this->TRANSACTION = new Transaction(null, $this->PROFILE->__get("profileId"),
															$testAmount, $testDateApproved,
															$testCardToken, $testStripeToken);

		$this->TRANSACTION->insert($this->mysqli);
		echo "<p>TRANSACTION created -> setUp 65</p>";
		var_dump($this->TRANSACTION);


		$this->FLIGHT = new Flight(null, "ABQ", "DFW", "01:42", "2014-12-30 08:00:00", "2014-12-30 09:42:00", "1234",
			100.00, 25);
		$this->FLIGHT->insert($this->mysqli);
		echo "<p>FLIGHT created -> setUp 81</p>";
		var_dump($this->FLIGHT);

		try {
			$this->TICKET = new Ticket(null, "CA57TW1234", 100.00, "Booked",
												$this->PROFILE->__get("profileId"),
												$this->TRAVELER->__get("travelerId"),
												$this->TRANSACTION->getTransactionId());
			$this->TICKET->insert($this->mysqli);
		} catch (Exception $exception){
			var_dump($exception);
		}
		echo "<p>TICKET created -> setUp 89</p>";
		var_dump($this->TICKET);
	}

	// tearDown () is a method that is run after each test
	// here, we use it to delete the test record and disconnect from mySQL
	public function tearDown() {
		// delete the object if we can
		if($this->TICKET !== null) {
			$this->TICKET->delete($this->mysqli);
			$this->TICKET = null;
		}

		if($this->FLIGHT !== null) {
			$this->FLIGHT->delete($this->mysqli);
			$this->FLIGHT = null;
		}

		if($this->TRANSACTION !== null) {
			$this->TRANSACTION->delete($this->mysqli);
			$this->TRANSACTION = null;
		}

		if($this->TRAVELER !== null) {
			$this->TRAVELER->delete($this->mysqli);
			$this->TRAVELER = null;
		}

		if($this->PROFILE !== null) {
			$this->PROFILE->delete($this->mysqli);
			$this->PROFILE = null;
		}

		if($this->USER !== null) {
			$this->USER->delete($this->mysqli);
			$this->USER = null;
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

		// second, create a ticketflight to post to mySQL

		$this->ticketFlight = new TicketFlight(null, $this->FLIGHT->getFlightId(), $this->TICKET->getTicketId());

		//third, insert the ticketflight to mySQL
		$this->ticketFlight->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->ticketFlight->getFlightId());
		$this->assertTrue($this->ticketFlight->getFlightId() > 0);
		$this->assertNotNull($this->ticketFlight->getTicketId());
		$this->assertTrue($this->ticketFlight->getTicketId() > 0);
		$this->assertIdentical($this->ticketFlight->getFlightId(), 	$this->FLIGHT->getFlightId());
		$this->assertIdentical($this->ticketFlight->getTicketId(),  $this->TICKET->getTicketId());

	}

	// test updating a ticketFlight
/*	public function testUpdateTicketFlight()
	{
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a ticketFlight to post to mySQL
		$this->ticketFlight = new TicketFlight(null, $this->FLIGHT->getFlightId(), $this->TICKET->getTicketId());

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
		$this->ticketFlight = new TicketFlight(null, $this->FLIGHT->getFlightId(),
																	$this->TICKET->getTicketId());

		//third, insert the profile to mySQL
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
																							$this->TICKET->getTicketId());
		$this->assertNull($hopefulTicketFlight);


	}

	// test grabbing a ticketFlight from mySQL
	public function testGetTicketFlightByTicketId() {
		// first verify mySQL connected Ok
		$this->assertNotNull($this->mysqli);

		// second create a new ticketFlight to post to mySQL
		$this->ticketFlight = new TicketFlight(null, $this->FLIGHT->getFlightId(),
																	$this->TICKET->getTicketId());

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
		$this->ticketFlight = new TicketFlight(null, $this->FLIGHT->getFlightId(),
																	$this->TICKET->getTicketId());

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

