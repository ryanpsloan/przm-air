<?php
/**
 * Created by PhpStorm.
 * User: zwg2
 * Date: 11/17/14
 * Time: 10:53 AM
 *
 * tests all functions of the flight class
 */

// first require the SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");
require_once("/etc/apache2/capstone-mysql/przm.php");

// then require the class under scrutiny
require_once("../php/flight.php");

// the FlightTest is a container for all our tests
class FlightTest extends UnitTestCase {
	// variable to hold the mySQL connection
	private $mysqli = null;
	// variable to hold the test database row
	private $flight = null;

	// a few "global" variables for creating test data
	private $ORIGIN      		= "LAX";
	private $DESTINATION   		= "JFK";
	private $DURATION       	= "06:09";
	private $DEPARTUREDATETIME = "2014-12-25 12:00:00";
	private $ARRIVALDATETIME   = "2014-12-25 18:09:00";
	private $FLIGHTNUMBER      = "90";
	private $PRICE 				= "640.63";
	private $TOTALSEATSONPLANE = 20;

	// setUp() is a method that is run before each test
	// here, we use it to connect to mySQL
	public function setUp() {
		// connect to mySQL
		$this->mysqli = MysqliConfiguration::getMysqli();

		}

	// tearDown() is a method that is run after each test
	// here, we use it to delete the test record and disconnect from mySQL
	public function tearDown() {
		// delete the flight if we can
		if($this->flight !== null) {
			$this->flight->delete($this->mysqli);
			$this->flight = null;
		}

		// disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
		}
	}

	// test creating a new Flight and inserting it to mySQL
	public function testInsertNewFlight() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a user to post to mySQL
		$this->flight = new Flight (null, $this->ORIGIN, $this->DESTINATION, $this->DURATION, $this->DEPARTUREDATETIME,
											$this->ARRIVALDATETIME, $this->FLIGHTNUMBER, $this->PRICE, $this->TOTALSEATSONPLANE);

		//fixme continue from here
		// third, insert the user to mySQL
		$this->flight->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull		($this->flight->getFlightId());
		$this->assertTrue			($this->flight->getFlightId() > 0);
		$this->assertIdentical	($this->flight->getOrigin(),               	$this->ORIGIN);
		$this->assertIdentical	($this->flight->getDestination(),          	$this->DESTINATION);
		$this->assertIdentical	($this->flight->getDuration(),             	$this->DURATION);
		$this->assertIdentical	($this->flight->getDepartureDateTime(), 		$this->DEPARTUREDATETIME);
		$this->assertIdentical	($this->flight->getArrivalDateTime(),      	$this->ARRIVALDATETIME);
		$this->assertIdentical	($this->flight->getFlightNumber(),         	$this->FLIGHTNUMBER);
		$this->assertIdentical	($this->flight->getPrice(),                	$this->PRICE);
		$this->assertIdentical	($this->flight->getTotalSeatsOnPlane(), 		$this->TOTALSEATSONPLANE);
	}


	// test updating a Flight in mySQL
	public function testUpdateFlight() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a flight to post to mySQL
		$this->flight = new Flight (null, $this->ORIGIN, $this->DESTINATION, $this->DURATION, $this->DEPARTUREDATETIME,
											$this->ARRIVALDATETIME, $this->FLIGHTNUMBER, $this->PRICE, $this->TOTALSEATSONPLANE);

		// third, insert the flight to mySQL
		$this->flight->insert($this->mysqli);

		// fourth, update the flight and post the changes to mySQL
		$newOrigin = "ABQ";
		$newDestination = "LAX";
		$newDuration = "01:50";

		$this->flight->setOrigin($newOrigin);
		$this->flight->setDestination($newDestination);
		$this->flight->setDuration($newDuration);

		$this->flight->update($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull		($this->flight->getFlightId());
		$this->assertTrue			($this->flight->getFlightId() > 0);
		$this->assertIdentical	($this->flight->getOrigin(),               	$this->ORIGIN);
		$this->assertIdentical	($this->flight->getDestination(),          	$this->DESTINATION);
		$this->assertIdentical	($this->flight->getDuration(),             	$this->DURATION);
		$this->assertIdentical	($this->flight->getDepartureDateTime(), 		$this->DEPARTUREDATETIME);
		$this->assertIdentical	($this->flight->getArrivalDateTime(),      	$this->ARRIVALDATETIME);
		$this->assertIdentical	($this->flight->getFlightNumber(),         	$this->FLIGHTNUMBER);
		$this->assertIdentical	($this->flight->getPrice(),                	$this->PRICE);
		$this->assertIdentical	($this->flight->getTotalSeatsOnPlane(), 		$this->TOTALSEATSONPLANE);}

	// test deleting a flight
	public function testDeleteFlight() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a flight to post to mySQL
		$this->flight = new Flight (null, $this->ORIGIN, $this->DESTINATION, $this->DURATION, $this->DEPARTUREDATETIME,
											$this->ARRIVALDATETIME, $this->FLIGHTNUMBER, $this->PRICE, $this->TOTALSEATSONPLANE);

	// third, insert the flight to mySQL
		$this->flight->insert($this->mysqli);

		// fourth, verify the Flight was inserted
		$this->assertNotNull($this->flight->getUserId());
		$this->assertTrue($this->flight->getUserId() > 0);

		// fifth, delete the flight
		$this->flight->delete($this->mysqli);
		$this->flight = null;

		// fixme: because flightId is primary key, do we have to get it back from mysql above before we delete it and verify below
		// finally, try to get the flight and assert we didn't get a thing
		$hopefulFlight = Flight::getFlightByFlightId ($this->mysqli, $this->flightId);
		$this->assertNull($hopefulFlight);
	}

	// test grabbing a Flight from mySQL
	public function testGetFlightByFlightId() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a flight to post to mySQL
		$this->flight = new Flight (null, $this->ORIGIN, $this->DESTINATION, $this->DURATION, $this->DEPARTUREDATETIME,
											$this->ARRIVALDATETIME, $this->FLIGHTNUMBER, $this->PRICE, $this->TOTALSEATSONPLANE);

		// third, insert the flight to mySQL
		$this->flight->insert($this->mysqli);

		// fourth, get the flight using the static method
		$staticFlight = Flight::getFlightByFlightId($this->mysqli, $this->FLIGHTID);

		// finally, compare the fields
		$this->assertNotNull		($staticFlight->getFlightId());
		$this->assertTrue			($staticFlight->getFlightId() > 0);
		$this->assertIdentical	($staticFlight->getFlightId(),              	$this->flight->getFlightId());
		$this->assertIdentical	($staticFlight->getOrigin(),               	$this->ORIGIN);
		$this->assertIdentical	($staticFlight->getDestination(),          	$this->DESTINATION);
		$this->assertIdentical	($staticFlight->getDuration(),             	$this->DURATION);
		$this->assertIdentical	($staticFlight->getDepartureDateTime(), 		$this->DEPARTUREDATETIME);
		$this->assertIdentical	($staticFlight->getArrivalDateTime(),      	$this->ARRIVALDATETIME);
		$this->assertIdentical	($staticFlight->getFlightNumber(),         	$this->FLIGHTNUMBER);
		$this->assertIdentical	($staticFlight->getPrice(),                	$this->PRICE);
		$this->assertIdentical	($staticFlight->getTotalSeatsOnPlane(), 		$this->TOTALSEATSONPLANE);
	}

	//fixme: test for 2 custom functions of change seats and search by user inputs

}
?>