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


}

