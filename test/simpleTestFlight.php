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
	private $DURATION       	= "06:09:00";
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
		// delete the user if we can
		if($this->user !== null) {
			$this->user->delete($this->mysqli);
			$this->user = null;
		}

		// disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
		}
	}

	// test creating a new User and inserting it to mySQL
	public function testInsertNewUser() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a user to post to mySQL
		$this->user = new User(null, $this->EMAIL, $this->HASH, $this->SALT, $this->AUTH_TOKEN);

		// third, insert the user to mySQL
		$this->user->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->user->getUserId());
		$this->assertTrue($this->user->getUserId() > 0);
		$this->assertIdentical($this->user->getEmail(),               $this->EMAIL);
		$this->assertIdentical($this->user->getPassword(),            $this->HASH);
		$this->assertIdentical($this->user->getSalt(),                $this->SALT);
		$this->assertIdentical($this->user->getAuthenticationToken(), $this->AUTH_TOKEN);
	}

	// test updating a User in mySQL
	public function testUpdateUser() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a user to post to mySQL
		$this->user = new User(null, $this->EMAIL, $this->HASH, $this->SALT, $this->AUTH_TOKEN);

		// third, insert the user to mySQL
		$this->user->insert($this->mysqli);

		// fourth, update the user and post the changes to mySQL
		$newEmail = "jake@cortez.org.mx";
		$this->user->setEmail($newEmail);
		$this->user->update($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->user->getUserId());
		$this->assertTrue($this->user->getUserId() > 0);
		$this->assertIdentical($this->user->getEmail(),               $newEmail);
		$this->assertIdentical($this->user->getPassword(),            $this->HASH);
		$this->assertIdentical($this->user->getSalt(),                $this->SALT);
		$this->assertIdentical($this->user->getAuthenticationToken(), $this->AUTH_TOKEN);
	}

	// test deleting a User
	public function testDeleteUser() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a user to post to mySQL
		$this->user = new User(null, $this->EMAIL, $this->HASH, $this->SALT, $this->AUTH_TOKEN);

		// third, insert the user to mySQL
		$this->user->insert($this->mysqli);

		// fourth, verify the User was inserted
		$this->assertNotNull($this->user->getUserId());
		$this->assertTrue($this->user->getUserId() > 0);

		// fifth, delete the user
		$this->user->delete($this->mysqli);
		$this->user = null;

		// finally, try to get the user and assert we didn't get a thing
		$hopefulUser = User::getUserByEmail($this->mysqli, $this->EMAIL);
		$this->assertNull($hopefulUser);
	}

	// test grabbing a User from mySQL
	public function testGetUserByEmail() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a user to post to mySQL
		$this->user = new User(null, $this->EMAIL, $this->HASH, $this->SALT, $this->AUTH_TOKEN);

		// third, insert the user to mySQL
		$this->user->insert($this->mysqli);

		// fourth, get the user using the static method
		$staticUser = User::getUserByEmail($this->mysqli, $this->EMAIL);

		// finally, compare the fields
		$this->assertNotNull($staticUser->getUserId());
		$this->assertTrue($staticUser->getUserId() > 0);
		$this->assertIdentical($staticUser->getUserId(),              $this->user->getUserId());
		$this->assertIdentical($staticUser->getEmail(),               $this->EMAIL);
		$this->assertIdentical($staticUser->getPassword(),            $this->HASH);
		$this->assertIdentical($staticUser->getSalt(),                $this->SALT);
		$this->assertIdentical($staticUser->getAuthenticationToken(), $this->AUTH_TOKEN);
	}
}
?>