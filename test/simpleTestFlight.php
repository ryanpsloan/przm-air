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
require_once("../php/results.php");
require_once("../php/flight.php");

// the FlightTest is a container for all our tests
class FlightTest extends UnitTestCase {
	// variable to hold the mySQL connection
	private $mysqli = null;
	// variable to hold the test database row object
	private $flight = null;

	// a few "global" variables for creating test data
	private $ORIGIN = "SEA";
	private $DESTINATION = "JFK";
	private $DURATION = "06:09:00";
	private $DEPARTUREDATETIME = "2014-12-25 12:00:00";
	private $ARRIVALDATETIME = "2014-12-25 18:09:00";
	private $FLIGHTNUMBER = "90";
	private $PRICE = 640.63;
	private $TOTALSEATSONPLANE = 20;

	// setUp() is a method that is run before each test
	// here, we use it to connect to mySQL
	public function setUp()
	{
		// connect to mySQL
		$this->mysqli = MysqliConfiguration::getMysqli();
	}

	// tearDown() is a method that is run after each test
	// here, we use it to delete the test record and disconnect from mySQL
	public function tearDown()
	{
		// delete the flight if we can
		if($this->flight !== null) {
			$this->flight->delete($this->mysqli);
			$this->flight = null;
		}

		//echo "<p>line 51 of testFlight in tear down flight object should be deleted</p>";

		//var_dump($this->flight);
		// disconnect from mySQL

		/*fixme:if($this->mysqli !== null) {
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
		$explode = explode(":", $this->DURATION);
		$DURATION = DateInterval::createFromDateString("$explode[0] hour + $explode[1] minutes + 0 seconds");
		$DEPARTUREDATETIME = DateTime::createFromFormat("Y-m-d H:i:s", $this->DEPARTUREDATETIME);
		$ARRIVALDATETIME = DateTime::createFromFormat("Y-m-d H:i:s", $this->ARRIVALDATETIME);

		// finally, compare the fields
		$this->assertNotNull($this->flight->getFlightId());
		$this->assertTrue($this->flight->getFlightId() > 0);
		$this->assertIdentical($this->flight->getOrigin(), $this->ORIGIN);
		$this->assertIdentical($this->flight->getDestination(), $this->DESTINATION);
		$this->assertIdentical($this->flight->getDuration(), $DURATION);
		$this->assertIdentical($this->flight->getDepartureDateTime(), $DEPARTUREDATETIME);
		$this->assertIdentical($this->flight->getArrivalDateTime(), $ARRIVALDATETIME);
		$this->assertIdentical($this->flight->getFlightNumber(), $this->FLIGHTNUMBER);
		$this->assertIdentical($this->flight->getPrice(), $this->PRICE);
		$this->assertIdentical($this->flight->getTotalSeatsOnPlane(), $this->TOTALSEATSONPLANE);
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
		$newDuration = "01:50:00";
		$newDEPARTUREDATETIME = "2014-12-31 12:00:00";
		$newARRIVALDATETIME = "2014-12-31 18:09:00";
		$newFLIGHTNUMBER = "100";
		$newPRICE = 1040.63;
		$newTOTALSEATSONPLANE = 19;

		$this->ORIGIN = $newOrigin;
		$this->DESTINATION = $newDestination;
		$this->DURATION = $newDuration;
		$this->DEPARTUREDATETIME = $newDEPARTUREDATETIME;
		$this->ARRIVALDATETIME = $newARRIVALDATETIME;
		$this->FLIGHTNUMBER = $newFLIGHTNUMBER;
		$this->PRICE = $newPRICE;
		$this->TOTALSEATSONPLANE = $newTOTALSEATSONPLANE;

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
		$explode = explode(":", $this->DURATION);
		$DURATION = DateInterval::createFromDateString("$explode[0] hour + $explode[1] minutes + 0 seconds");
		$DEPARTUREDATETIME = DateTime::createFromFormat("Y-m-d H:i:s", $this->DEPARTUREDATETIME);
		$ARRIVALDATETIME = DateTime::createFromFormat("Y-m-d H:i:s", $this->ARRIVALDATETIME);


		// finally, compare the fields
		$this->assertNotNull($this->flight->getFlightId());
		$this->assertTrue($this->flight->getFlightId() > 0);
		$this->assertIdentical($this->flight->getOrigin(), $this->ORIGIN);
		$this->assertIdentical($this->flight->getDestination(), $this->DESTINATION);
		$this->assertIdentical($this->flight->getDuration(), $DURATION);
		$this->assertIdentical($this->flight->getDepartureDateTime(), $DEPARTUREDATETIME);
		$this->assertIdentical($this->flight->getArrivalDateTime(), $ARRIVALDATETIME);
		$this->assertIdentical($this->flight->getFlightNumber(), $this->FLIGHTNUMBER);
		$this->assertIdentical($this->flight->getPrice(), $this->PRICE);
		$this->assertIdentical($this->flight->getTotalSeatsOnPlane(), $this->TOTALSEATSONPLANE);
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
		$hopefulFlight = Flight::getFlightByFlightId($this->mysqli, $localFlightID);
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
		$explode = explode(":", $this->DURATION);
		$DURATION = DateInterval::createFromDateString("$explode[0] hour + $explode[1] minutes + 0 seconds");
		$DEPARTUREDATETIME = DateTime::createFromFormat("Y-m-d H:i:s", $this->DEPARTUREDATETIME);
		$ARRIVALDATETIME = DateTime::createFromFormat("Y-m-d H:i:s", $this->ARRIVALDATETIME);

		//echo "<p>line 210 of testFlight var dump of duration</p>";
		//var_dump($DURATION);

		// fourth, get the flight using the static method
		$staticFlight = Flight::getFlightByFlightId($this->mysqli, $this->flight->getFlightId());

		// finally, compare the fields
		$this->assertNotNull($staticFlight->getFlightId());
		$this->assertTrue($staticFlight->getFlightId() > 0);
		$this->assertIdentical($staticFlight->getFlightId(), $this->flight->getFlightId());
		$this->assertIdentical($staticFlight->getOrigin(), $this->ORIGIN);
		$this->assertIdentical($staticFlight->getDestination(), $this->DESTINATION);
		$this->assertIdentical($staticFlight->getDuration(), $DURATION);
		$this->assertIdentical($staticFlight->getDepartureDateTime(), $DEPARTUREDATETIME);
		$this->assertIdentical($staticFlight->getArrivalDateTime(), $ARRIVALDATETIME);
		$this->assertIdentical($staticFlight->getFlightNumber(), $this->FLIGHTNUMBER);
		$this->assertIdentical($staticFlight->getPrice(), $this->PRICE);
		$this->assertIdentical($staticFlight->getTotalSeatsOnPlane(), $this->TOTALSEATSONPLANE);
	}


	// creates and inserts a fake flight, changes its seat number in various ways,
	// then checks the results of the static function against the expected
	public function testChangeNumberOfSeats() {

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
		$this->assertNotNull($staticChangeSeats3->getFlightId());
		$this->assertTrue($staticChangeSeats3->getFlightId() > 0);
		$this->assertIdentical($staticChangeSeats3->getFlightId(), $this->flight->getFlightId());
		$this->assertIdentical($staticChangeSeats3->getOrigin(), $this->ORIGIN);
		$this->assertIdentical($staticChangeSeats3->getDestination(), $this->DESTINATION);
		$this->assertIdentical($staticChangeSeats3->getDuration(), $this->DURATION);
		$this->assertIdentical($staticChangeSeats3->getDepartureDateTime(), $this->DEPARTUREDATETIME);
		$this->assertIdentical($staticChangeSeats3->getArrivalDateTime(), $this->ARRIVALDATETIME);
		$this->assertIdentical($staticChangeSeats3->getFlightNumber(), $this->FLIGHTNUMBER);
		$this->assertIdentical($staticChangeSeats3->getPrice(), $this->PRICE);
		$this->assertIdentical($staticChangeSeats3->getTotalSeatsOnPlane(), $this->TOTALSEATSONPLANE + $decreaseByJustRight);

		// then increase back and compare to the expected
		$staticChangeSeats4 = Flight::changeNumberOfSeats($this->mysqli, $this->flight->getFlightId(), $increaseByJustRight);
		// compare the fields for first change
		$this->assertNotNull($staticChangeSeats4->getFlightId());
		$this->assertTrue($staticChangeSeats4->getFlightId() > 0);
		$this->assertIdentical($staticChangeSeats4->getFlightId(), $this->flight->getFlightId());
		$this->assertIdentical($staticChangeSeats4->getOrigin(), $this->ORIGIN);
		$this->assertIdentical($staticChangeSeats4->getDestination(), $this->DESTINATION);
		$this->assertIdentical($staticChangeSeats4->getDuration(), $this->DURATION);
		$this->assertIdentical($staticChangeSeats4->getDepartureDateTime(), $this->DEPARTUREDATETIME);
		$this->assertIdentical($staticChangeSeats4->getArrivalDateTime(), $this->ARRIVALDATETIME);
		$this->assertIdentical($staticChangeSeats4->getFlightNumber(), $this->FLIGHTNUMBER);
		$this->assertIdentical($staticChangeSeats4->getPrice(), $this->PRICE);
		$this->assertIdentical($staticChangeSeats4->getTotalSeatsOnPlane(), $this->TOTALSEATSONPLANE + $increaseByJustRight);

	}





	/*
		The below test function would verify that results for a given date are valid across all Origin/destination pairs and that flight data
		matches the data in the database associated with each returned flightId.  But it DOESN't verify whether search
		returned ALL POSSIBLE results.  Just that all returned results are valid.  In other words, a search that returned
		no results, for valid or invalid reasons, would pass regardless.

		To offset this and make a null result FAIL when results should exist, I assert simply that results not be null where possible.

		Note that outer array and midlevel array are NOT associative so that we can loop through them.
	*/

	public function testGetRoutesByUserInput() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);


		// SETUP:
		// 1. build array of origins and count size
		// create query and put results into an array
		$query1 = "SELECT DISTINCT origin FROM flight";
		$statement1 = $this->mysqli->prepare($query1);
		if($statement1 === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// execute the statement
		if($statement1->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		// get result from the SELECT query *pounds fists*
		$result1 = $statement1->get_result();
		if($result1 === false) {
			throw(new mysqli_sql_exception("Unable to get result set"));
		}

		// turn result into a associative array and count size for use in loops
		$allOriginsArray = array();
		$counterO = 0;

		while (($rowOrigin = $result1->fetch_assoc()) !== null) {
			try {
				$allOriginsArray [$counterO] = $rowOrigin["origin"];
				$counterO++;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception("Unable to convert row to origin", 0, $exception));
			}
		}

		$sizeAllOrigins = count($allOriginsArray);

//		echo "<p>line 345 dump of allOriginsArray array and size of array</p>";
//		var_dump($allOriginsArray);
//		var_dump($sizeAllOrigins);

		// 2. build array of destinations and count size
		// create query and put results into an array
		$query2 = "SELECT DISTINCT destination FROM flight";
		$statement2 = $this->mysqli->prepare($query2);
		if($statement2 === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// execute the statement
		if($statement2->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		// get result from the SELECT query *pounds fists*
		$result2 = $statement2->get_result();
		if($result2 === false) {
			throw(new mysqli_sql_exception("Unable to get result set"));
		}

		// turn result into a associative array and count size for use in loops
		$allDestinationsArray = array();
		$counterD = 0;

		while (($rowDestination = $result2->fetch_assoc()) !== null) {
			try {
				$destination = $rowDestination["destination"];
				$allDestinationsArray[$counterD] = $destination;
				$counterD++;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception("Unable to convert row to destination", 0, $exception));
			}
		}

		$sizeAllDestinations = count($allDestinationsArray);

//		echo "<p>line 372 dump of destinations array and size of array</p>";
//		var_dump($allDestinationsArray);
//		var_dump($sizeAllDestinations);

		// declare date variables for search (put these in a loop if want to test multiple days.
		$userFlyDateStart = "2014-12-09 00:00:00";
		$userFlyDateEnd = "2014-12-10 00:00:00";


		// declare min layover variable (put in loop to test multiple)
		$minLayover = 15;


		// declare starting number of passengers variable
		//$numberOfPassengersRequested = 5; //fixme if need different number or if don't declare here but in loop3 instead


		// declare range variable between fly start time and end time in fractions of days.
		$maxDurationRange = 1;


		// NESTED LOOPS:
		// LOOP 1: for number of origins, assign each in the array to $userOrigin variable
		for($a = 0; $a < $sizeAllOrigins; $a++) {
			$userOrigin = $allOriginsArray[$a];

			// Loop 2: for number of destinations, assign each in the array to $userDestination variable
			for($b = 0; $b < $sizeAllDestinations; $b++) {

				// skip cases of identical origin and destination
				if($allDestinationsArray[$b] = $userOrigin) {
					$b = $b + 1;
				}

				$userDestination = $allDestinationsArray[$b];

				// Loop 3: check different numbers of passengers
				for($numberOfPassengersRequested = 5; $numberOfPassengersRequested < 30; $numberOfPassengersRequested = $numberOfPassengersRequested + 10) {

					//		call static user search method to get result in form of 2D array of objects
					try {
						$thisArrayOfPaths = Flight::getRoutesByUserInput($mysqli, $userOrigin, $userDestination,
																						$userFlyDateStart, $userFlyDateEnd,
																						$numberOfPassengersRequested, $minLayover);
					} catch(Exception $exception) {
						throw (new mysqli_sql_exception("Unable to create flight."));
						return;
					}

					// if there should be results of some sort returned for the given amount of passengers, then assert that it was so.
					if($numberOfPassengersRequested < 20) {
						$this->assertNotNull($thisArrayOfPaths);
					}


					// Loop 4: for loop to iterate through dimension 1 result array $thisArrayOfPaths[]
					for($d = 0; $thisArrayOfPaths[$d] !== null; $d++) {

						//count size of 2nd dimension array $sizeOfEachPath
						$sizeOfEachPath = count($thisArrayOfPaths[$d]);


						$this->assertIdentical($thisArrayOfPaths[$d][0]->getOrigin(), $userOrigin);
						$this->assertIdentical($thisArrayOfPaths[$d][$sizeOfEachPath]->getDestination(), $userDestination);


						// To assert that duration of flights is within specified range, first convert to DateTime Object.
						$arrival = DateTime::createFromFormat("Y:m:d H:i:s", $thisArrayOfPaths[$d][$sizeOfEachPath]->getArrivalDateTime());
						$departure = DateTime::createFromFormat("Y:m:d H:i:s", $thisArrayOfPaths[$d][0]->getDepartureDateTime());
						$totalDuration = $departure->diff($arrival);

						$this->assertTrue($totalDuration <= $maxDurationRange);


						// Loop 5A: for loop to compare arrival/departure times in results and verify no overlaps
						for($e = 0; $thisArrayOfPaths[$d][$e + 1] !== null; $e++) {
							$checkEachFlightDeparture = DateTime::createFromFormat("Y:m:d H:i:s", $thisArrayOfPaths[$d][$e + 1]->getDepartureDateTime());
							$checkEachFlightArrival = DateTime::createFromFormat("Y:m:d H:i:s", $thisArrayOfPaths[$d][$e]->getArrivalDateTime());
							$layover = $checkEachFlightDeparture->diff($checkEachFlightArrival);


							$this->assertTrue($layover >= ($minLayover / (60 * 24)));
						}


						// Loop 5B: (sibling not child of 5A) Assert identical each flightId's info with a select from the database
						for($f = 0; $thisArrayOfPaths[$d][$f] !== null; $f++) {

							$flightObject = Flight::getFlightByFlightId($mysqli, $thisArrayOfPaths[$d][$f]->getFlightId());

							// run through each field of each flight and assert Identical to results
							$this->assertNotNull($thisArrayOfPaths[$d][$f]->getFlightId());
							$this->assertTrue($thisArrayOfPaths[$d][$f]->getFlightId() > 0);
							$this->assertIdentical($thisArrayOfPaths[$d][$f]->getFlightId(), $flightObject->getFlightId());
							$this->assertIdentical($thisArrayOfPaths[$d][$f]->getOrigin(), $flightObject->getOrigin());
							$this->assertIdentical($thisArrayOfPaths[$d][$f]->getDestination(), $flightObject->getDestination());
							$this->assertIdentical($thisArrayOfPaths[$d][$f]->getDuration(), $flightObject->getDuration());
							$this->assertIdentical($thisArrayOfPaths[$d][$f]->getDepartureDateTime(), $flightObject->getDepartureDateTime());
							$this->assertIdentical($thisArrayOfPaths[$d][$f]->getArrivalDateTime(), $flightObject->getArrivalDateTime());
							$this->assertIdentical($thisArrayOfPaths[$d][$f]->getFlightNumber(), $flightObject->getFlightNumber());
							$this->assertIdentical($thisArrayOfPaths[$d][$f]->getPrice(), $flightObject->getPrice());
							$this->assertIdentical($thisArrayOfPaths[$d][$f]->getTotalSeatsOnPlane(), $flightObject->getTotalSeatsOnPlane());


						}
					}
				}
			}
		}
	}
}
?>
