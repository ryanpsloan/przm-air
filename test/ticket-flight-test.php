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





}

