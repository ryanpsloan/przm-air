<?php
/**
 * test to verify the intersection TicketFlight
 *
 * @author Paul Morbitzer <pmorbitzer@gmail.com>
 */

// first require the SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");

//then require the class under scrutiny
require_once("../php/ticket.php");

// require the mysqli
require_once("/etc/apache2/capstone-mysql/przm.php");

// require the classes for foreign keys
require_once("../php/flight.php");
require_once("../php/ticket.php");

// the TicketFlightTest is a container for all our tests
class TicketFlightTest extends UnitTestCase {
	// variable to hold the mySQL connection
	private $mysqli = null;
	// variable to hold the test database row
	private $ticketFlight = null;

	// a few "global" variables for creating test data
	private $FLIGHT = null;
	private $TICKET = null;

	// setUp () is a method that is run before each test
	// here, we use it to connect to my SQL
	public function setUp() {
		$mysqli = MysqliConfiguration::getMysqli();

		$this->FLIGHT = new Flight(null, "ABQ", "DFW", "01:42", "08:00", "09:42", "1234", "100.00", 5);
		$this->FLIGHT->insert($mysqli);
		// todo create objects for dependencies PROFILE, TRAVELER, TRANSACTION
		$this->TICKET = new Ticket(null, "12345ABCDE", "100.00", "Booked", 1, 1, 1);
		$this->TICKET->insert($mysqli);
	}

	// tearDown () is a method that is run after each test
	// here, we use it to delete the test record and disconnect from mySQL
	public function tearDown() {
		// delete the profile if we can
		if($this->TICKET !== null) {
			$this->TICKET->delete($this->mysqli);
			$this->TICKET = null;
		}

		if($this->FLIGHT !== null) {
			$this->FLIGHT->delete($this->mysqli);
			$this->FLIGHT = null;
		}

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

		$this->ticketFlight = new TicketFlight(null, $this->FLIGHT->getFlightId(), $this->TICKET->getTicketId);

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
	public function testUpdateTicketFlight()
	{
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a ticketFlight to post to mySQL
		$this->ticketFlight = new TicketFlight(null, $this->FLIGHT->getFlightId(), $this->TICKET->getTicketId);

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
	}

	// test deleting a ticketFlight
	public function testDeleteTicketFlight() {
		// first, verify my SQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a ticketFlight to post to mySQL
		$this->ticketFlight = new TicketFlight(null, $this->FLIGHT->getFlightId(), $this->TICKET->getTicketId);

		//third, insert the profile to mySQL
		$this->ticketFlight->insert($this->mysqli);

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
		$hopefulTicketFlight = TicketFlight::getTicketFlightByFlightId($this->mysqli, $this->$flightId);
		$this->assertNull($hopefulTicketFlight);
		$hopefulTicketFlight = TicketFlight::getTicketFlightByTicketId($this->mysqli, $this->$ticketId);
		$this->assertNull($hopefulTicketFlight);


	}

	// test grabbing a ticketFlight from mySQL
	public function testGetTicketFlightByTicketId() {
		// first verify mySQL connected Ok
		$this->assertNotNull($this->mysqli);

		// second create a new ticketFlight to post to mySQL
		$this->ticketFlight = new TicketFlight(null, $this->FLIGHT->getFlightId(), $this->TICKET->getTicketId);

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
		$this->ticketFlight = new TicketFlight(null, $this->FLIGHT->getFlightId(), $this->TICKET->getTicketId);

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

