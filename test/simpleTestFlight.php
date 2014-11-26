<?php
/**
 * Created by PhpStorm.
 * User: zwg2
 * Date: 11/17/14
 * Time: 10:53 AM
 *
 * tests all functions of the flight class
 */

//fixme: fix duration to be a time only not a datetime
// first require the SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");
require_once("/etc/apache2/capstone-mysql/przm.php");

// then require the class under scrutiny
require_once("../php/flight.php");

// the FlightTest is a container for all our tests
class FlightTest extends UnitTestCase {
	// variable to hold the mySQL connection
	private $mysqli = null;
	// variable to hold the test database row object
	private $flight = null;

	// a few "global" variables for creating test data
	private $ORIGIN      		= "SEA";
	private $DESTINATION   		= "JFK";
	private $DURATION       	= "06:09:00";
	private $DEPARTUREDATETIME = "2014-12-25 12:00:00";
	private $ARRIVALDATETIME   = "2014-12-25 18:09:00";
	private $FLIGHTNUMBER      = "90";
	private $PRICE 				= 640.63;
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

		//echo "<p>line 51 of testFlight in tear down flight object should be deleted</p>";

		//var_dump($this->flight);
		// disconnect from mySQL
		/*if($this->mysqli !== null) {
			$this->mysqli->close();
		}*/
	}

	// test creating a new Flight and inserting it to mySQL
	public function testInsertNewFlight() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a flight to post to mySQL
		$this->flight = new Flight (null, $this->ORIGIN, $this->DESTINATION, $this->DURATION, $this->DEPARTUREDATETIME,
											$this->ARRIVALDATETIME, $this->FLIGHTNUMBER, $this->PRICE, $this->TOTALSEATSONPLANE);

		// third, insert the flight to mySQL
		$this->flight->insert($this->mysqli);

		//echo "<p>line 72 of testFlight var dump of flight object after insert in in insert function</p>";
		//var_dump($this->flight);
		// fixme: we need a way to have datetimes set in set functions as correct timezone and converted to UTC upon insertion

		//convert input strings to DateTimeObjects or Interval to compare against flight get methods
		$explode 				= explode(":", $this->DURATION);
		$DURATION       		= DateInterval::createFromDateString("$explode[0] hour + $explode[1] minutes + 0 seconds");
		$DEPARTUREDATETIME 	= DateTime::createFromFormat("Y-m-d H:i:s", $this->DEPARTUREDATETIME);
		$ARRIVALDATETIME   	= DateTime::createFromFormat("Y-m-d H:i:s", $this->ARRIVALDATETIME);

		// finally, compare the fields
		$this->assertNotNull		($this->flight->getFlightId());
		$this->assertTrue			($this->flight->getFlightId() > 0);
		$this->assertIdentical	($this->flight->getOrigin(),               	$this->ORIGIN);
		$this->assertIdentical	($this->flight->getDestination(),          	$this->DESTINATION);
		$this->assertIdentical	($this->flight->getDuration(),             	$DURATION);
		$this->assertIdentical	($this->flight->getDepartureDateTime(), 		$DEPARTUREDATETIME);
		$this->assertIdentical	($this->flight->getArrivalDateTime(),      	$ARRIVALDATETIME);
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
		$newOrigin 					= "ABQ";
		$newDestination 			= "LAX";
		$newDuration 				= "01:50:00";
		$newDEPARTUREDATETIME 	= "2014-12-31 12:00:00";
		$newARRIVALDATETIME   	= "2014-12-31 18:09:00";
		$newFLIGHTNUMBER      	= "100";
		$newPRICE 					= 1040.63;
		$newTOTALSEATSONPLANE 	= 19;

		$this->ORIGIN 					= $newOrigin;
		$this->DESTINATION 			= $newDestination;
		$this->DURATION 				= $newDuration;
		$this->DEPARTUREDATETIME 	= $newDEPARTUREDATETIME;
		$this->ARRIVALDATETIME 		= $newARRIVALDATETIME;
		$this->FLIGHTNUMBER 			= $newFLIGHTNUMBER;
		$this->PRICE 					= $newPRICE;
		$this->TOTALSEATSONPLANE 	= $newTOTALSEATSONPLANE;

		$this->flight->setOrigin($newOrigin);
		$this->flight->setDestination($newDestination);
		$this->flight->setDuration($newDuration);
		$this->flight->setDepartureDateTime($newDEPARTUREDATETIME);
		$this->flight->setArrivalDateTime($newARRIVALDATETIME);
		$this->flight->setFlightNumber($newFLIGHTNUMBER);
		$this->flight->setPrice($newPRICE);
		$this->flight->setTotalSeatsOnPlane($newTOTALSEATSONPLANE);
		$this->flight->update($this->mysqli);

		//convert date input strings to DateTimeObjects or Interval to compare against flight get methods
		$explode 				= explode(":", $this->DURATION);
		$DURATION       		= DateInterval::createFromDateString("$explode[0] hour + $explode[1] minutes + 0 seconds");
		$DEPARTUREDATETIME 	= DateTime::createFromFormat("Y-m-d H:i:s", $this->DEPARTUREDATETIME);
		$ARRIVALDATETIME   	= DateTime::createFromFormat("Y-m-d H:i:s", $this->ARRIVALDATETIME);


		// finally, compare the fields
		$this->assertNotNull		($this->flight->getFlightId());
		$this->assertTrue			($this->flight->getFlightId() > 0);
		$this->assertIdentical	($this->flight->getOrigin(),               	$this->ORIGIN);
		$this->assertIdentical	($this->flight->getDestination(),          	$this->DESTINATION);
		$this->assertIdentical	($this->flight->getDuration(),             	$DURATION);
		$this->assertIdentical	($this->flight->getDepartureDateTime(), 		$DEPARTUREDATETIME);
		$this->assertIdentical	($this->flight->getArrivalDateTime(),      	$ARRIVALDATETIME);
		$this->assertIdentical	($this->flight->getFlightNumber(),         	$this->FLIGHTNUMBER);
		$this->assertIdentical	($this->flight->getPrice(),                	$this->PRICE);
		$this->assertIdentical	($this->flight->getTotalSeatsOnPlane(), 		$this->TOTALSEATSONPLANE);
	}

	// test deleting a flight
	public function testDeleteFlight() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a flight to post to mySQL
		$this->flight = new Flight (null, $this->ORIGIN, $this->DESTINATION, $this->DURATION, $this->DEPARTUREDATETIME,
											$this->ARRIVALDATETIME, $this->FLIGHTNUMBER, $this->PRICE, $this->TOTALSEATSONPLANE);

		// third, insert the flight to mySQL
		$this->flight->insert($this->mysqli);

		$localFlightID = $this->flight->getFlightId();

		// fourth, verify the Flight was inserted
		$this->assertNotNull($this->flight->getFlightId());
		$this->assertTrue($this->flight->getFlightId() > 0);

		// fifth, delete the flight
		$this->flight->delete($this->mysqli);
		$this->flight = null;

		// finally, try to get the flight and assert we didn't get a thing
		$hopefulFlight = Flight::getFlightByFlightId ($this->mysqli, $localFlightID);
		$this->assertNull($hopefulFlight);
	}

	// test grabbing a Flight from mySQL
	public function testGetFlightByFlightId() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a flight to post to mySQL
		$this->flight = new Flight (null, $this->ORIGIN, $this->DESTINATION, $this->DURATION, $this->DEPARTUREDATETIME,
											$this->ARRIVALDATETIME, $this->FLIGHTNUMBER, $this->PRICE, $this->TOTALSEATSONPLANE);
		//echo "<p>line 182 of testFlight var dump of flight object before insert in testGetFlightbyID</p>";
		//var_dump($this->flight);


		// third, insert the flight to mySQL
		$this->flight->insert($this->mysqli);

		//echo "<p>line 189 of testFlight var dump of flight object after insert in testGetFlightbyID</p>";
		//var_dump($this->flight);
		//var_dump($this->flight->getFlightId());

		//convert date input strings to DateTimeObjects or Interval to compare against flight get methods
		$explode 				= explode(":", $this->DURATION);
		$DURATION       		= DateInterval::createFromDateString("$explode[0] hour + $explode[1] minutes + 0 seconds");
		$DEPARTUREDATETIME 	= DateTime::createFromFormat("Y-m-d H:i:s", $this->DEPARTUREDATETIME);
		$ARRIVALDATETIME   	= DateTime::createFromFormat("Y-m-d H:i:s", $this->ARRIVALDATETIME);

		//echo "<p>line 210 of testFlight var dump of duration</p>";
		//var_dump($DURATION);

		// fourth, get the flight using the static method
		$staticFlight = Flight::getFlightByFlightId($this->mysqli, $this->flight->getFlightId());

		// finally, compare the fields
		$this->assertNotNull		($staticFlight->getFlightId());
		$this->assertTrue			($staticFlight->getFlightId() > 0);
		$this->assertIdentical	($staticFlight->getFlightId(),              	$this->flight->getFlightId());
		$this->assertIdentical	($staticFlight->getOrigin(),               	$this->ORIGIN);
		$this->assertIdentical	($staticFlight->getDestination(),          	$this->DESTINATION);
		$this->assertIdentical	($staticFlight->getDuration(),             	$DURATION);
		$this->assertIdentical	($staticFlight->getDepartureDateTime(), 		$DEPARTUREDATETIME);
		$this->assertIdentical	($staticFlight->getArrivalDateTime(),      	$ARRIVALDATETIME);
		$this->assertIdentical	($staticFlight->getFlightNumber(),         	$this->FLIGHTNUMBER);
		$this->assertIdentical	($staticFlight->getPrice(),                	$this->PRICE);
		$this->assertIdentical	($staticFlight->getTotalSeatsOnPlane(), 		$this->TOTALSEATSONPLANE);
	}


	// creates and inserts a fake flight, changes its seat number in various ways,
	// then checks the results of the static function against the expected
	public function testChangeNumberOfSeats () {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a flight to post to mySQL
		$this->flight = new Flight (null, $this->ORIGIN, $this->DESTINATION, $this->DURATION, $this->DEPARTUREDATETIME,
											$this->ARRIVALDATETIME, $this->FLIGHTNUMBER, $this->PRICE, $this->TOTALSEATSONPLANE);

		// third, insert the flight to mySQL
		$this->flight->insert($this->mysqli);

		// fourth call the static method several times with different intputs, verify results each time.
		// inputs: set to +/- 21 and +/- 1 to test all actions
		$increaseByTooMuch = 1;
		//to be commented out and switched with next entry to check both scenarios
		$decreaseByTooMuch = -21;
		//to be commented out and switched with next entry to check both scenarios
		$decreaseByJustRight = -20;
		//to be commented out and switched with next entry to check both scenarios
		$increaseByJustRight = 20;

		// tell PHP to expect an exception (using SimpleTest function expect Exception), then increase beyond the limit
		$this->expectException("RangeException");
		Flight::changeNumberOfSeats($this->mysqli, $this->flight->getFlightId(), $increaseByTooMuch);


		// tell PHP to expect an exception (using SimpleTest function expect Exception), then decrease beyond the limit
		$this->expectException("RangeException");
		Flight::changeNumberOfSeats($this->mysqli, $this->flight->getFlightId(), $decreaseByTooMuch);


		// then decrease within the limit and compare to expected
		$staticChangeSeats3 = Flight::changeNumberOfSeats($this->mysqli, $this->flight->getFlightId(), $decreaseByJustRight);
		// compare the fields for first change
		$this->assertNotNull		($staticChangeSeats3->getFlightId());
		$this->assertTrue			($staticChangeSeats3->getFlightId() > 0);
		$this->assertIdentical	($staticChangeSeats3->getFlightId(),              	$this->flight->getFlightId());
		$this->assertIdentical	($staticChangeSeats3->getOrigin(),               	$this->ORIGIN);
		$this->assertIdentical	($staticChangeSeats3->getDestination(),          	$this->DESTINATION);
		$this->assertIdentical	($staticChangeSeats3->getDuration(),             	$this->DURATION);
		$this->assertIdentical	($staticChangeSeats3->getDepartureDateTime(), 		$this->DEPARTUREDATETIME);
		$this->assertIdentical	($staticChangeSeats3->getArrivalDateTime(),      	$this->ARRIVALDATETIME);
		$this->assertIdentical	($staticChangeSeats3->getFlightNumber(),         	$this->FLIGHTNUMBER);
		$this->assertIdentical	($staticChangeSeats3->getPrice(),                	$this->PRICE);
		$this->assertIdentical	($staticChangeSeats3->getTotalSeatsOnPlane(), 		$this->TOTALSEATSONPLANE + $decreaseByJustRight);

		// then increase back and compare to the expected
		$staticChangeSeats4 = Flight::changeNumberOfSeats($this->mysqli, $this->flight->getFlightId(), $increaseByJustRight);
		// compare the fields for first change
		$this->assertNotNull		($staticChangeSeats4->getFlightId());
		$this->assertTrue			($staticChangeSeats4->getFlightId() > 0);
		$this->assertIdentical	($staticChangeSeats4->getFlightId(),              	$this->flight->getFlightId());
		$this->assertIdentical	($staticChangeSeats4->getOrigin(),               	$this->ORIGIN);
		$this->assertIdentical	($staticChangeSeats4->getDestination(),          	$this->DESTINATION);
		$this->assertIdentical	($staticChangeSeats4->getDuration(),             	$this->DURATION);
		$this->assertIdentical	($staticChangeSeats4->getDepartureDateTime(), 		$this->DEPARTUREDATETIME);
		$this->assertIdentical	($staticChangeSeats4->getArrivalDateTime(),      	$this->ARRIVALDATETIME);
		$this->assertIdentical	($staticChangeSeats4->getFlightNumber(),         	$this->FLIGHTNUMBER);
		$this->assertIdentical	($staticChangeSeats4->getPrice(),                	$this->PRICE);
		$this->assertIdentical	($staticChangeSeats4->getTotalSeatsOnPlane(), 		$this->TOTALSEATSONPLANE + $increaseByJustRight);

	}


/*
	// fixme remove slash star when ready to test user search
	// //var_dump results from executing the search function for a weekday and a weekend day.
	public function testGetRoutesByUserInput($ORIGIN, $DESTINATION) {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		/*pseudo code outline for testGetRoutesByUserInput:
		This would verify that results for a given date are valid across all Origin/destination pairs and that flight data
		matches the data in the database associated with each returned flightId.  But it DOESN't verify whether search
		returned ALL POSSIBLE results.  Just that all returned results are valid.  In other words, a search that returned
		no results, for valid or invalid reasons, would pass regardless.

		To offset this and make a null result FAIL when results should exists, I assert simply that results not be null in those cases.

		Note that outer array and midlevel array can NOT be associative so that we can loop through them, while inner ray might be associative if it wasn't for loop 5B.

		SET UP:
		build array of origins
		build array of destinations
		count size of both arrays
		declare date variables for search
		declare min layover variable
		declare starting number of passengers variable
		declare range variable between fly start time and end time.

		NESTED LOOPS:
		LOOP 1: for number of origins, assign each in the array to $USER_ORIGIN variable

		Loop 2: same for destinations
			but i think a do/while to skip first origin/origin overlap, then....
			after that, if  next.destination in loop is same as this.origin, skip destination
			(i.e. add two instead of 1 to array index counter)

		Loop 3: USER_NUMBER_PASSENGERS = 15, <30, +10 (verifies null results if
			call static user search method to get result in form of 3D array
			if USER_NUMBER_PASSENGERS < totalSeatsOnPlane of 20, verify results not null or throw exception
			else verify results ARE null for over 20 passengers and return;

		Loop 4: for loop to iterate through dimension 1 result array "allPaths[]"
			for (i=0, allPaths[i] !== null, i++) {
				count size of 2nd dimension array allPaths[i]
				assert allPaths[i][0]["origin"] =  this.origin of loop 1
				assert allPaths[i][size of allPaths[i]]["destination"] = this.destination of loop 2
				assert allPaths[i][size of allPaths[i]]["arrivalDateTime"] - allPaths[i][0]["departureDateTime"] <= range variable
			}

		Loop 5A: for loop to compare arrival/departure times in results and verify no overlaps
			for (a=0, allPaths[i][a+1] !== null, a++) {
				allPaths[i][a+1]["departureDateTime"] - allPaths[i][a]["arrivalDateTime] >= minLayover;
			}
		Loop 5B (sibling not child of 5A): Assert identical each flightId's info with a select from the database
			for (a=0, allPaths[i][a] !== null, a++) {
				SELECT FROM flight (all fields) WHERE flightId = allPaths[i][a];
				row = result-> fetch_assoc();

				for (b=0, allPaths[i][a][b] !== null, b++) {
					Assert allPaths[i][a][b] identical to row[b]
				}
			}

		//repeat whole thing for a different day, like a weekend instead of weekday

		/

		// declare necessary variables to send to function for weekday
		$USER_ORIGIN = $ORIGIN;
		$USER_DESTINATION = $DESTINATION;
		$USER_FLY_DATE_START = "2014-12-04 00:00:00";
		$USER_FLY_DATE_END = "2014-12-05 00:00:00";

		do {

			$USER_NUMBER_PASSENGERS = 1;

			//fixme concrete mysqli?
			// call the user search function and var dump the results for visual verification
			$staticPaths = Flight::getRoutesByUserInput($this->mysqli, $USER_ORIGIN, $USER_DESTINATION, $USER_FLY_DATE_START,
				$USER_FLY_DATE_END, $USER_NUMBER_PASSENGERS);
			//var_dump($staticPaths);

			$USER_NUMBER_PASSENGERS = $USER_NUMBER_PASSENGERS + 5;


		} while ($USER_NUMBER_PASSENGERS < 30);



		// declare necessary new variables to send to function for weekend return flight
		$USER_RETURN_DATE_START = "2014-12-07 00:00:00";
		$USER_RETURN_DATE_END = "2014-12-08 00:00:00";

		do {

			$USER_NUMBER_PASSENGERS2 = 1;

			//fixme concrete mysqli?
			// call the user search function with reversed origin/destination and var dump the results for visual verification
			$staticPaths = Flight::getRoutesByUserInput($this->mysqli, $USER_DESTINATION, $USER_ORIGIN, $USER_RETURN_DATE_START,
																		$USER_RETURN_DATE_END, $USER_NUMBER_PASSENGERS2);
			//var_dump($staticPaths);

			$USER_NUMBER_PASSENGERS2 = $USER_NUMBER_PASSENGERS2 + 5;


		} while ($USER_NUMBER_PASSENGERS2 < 30);

	}
*/
}




?>