<?php
// first require the SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");

// then require the class under scrutiny
require_once("../php/profile.php");

// the ProfileTest is a container for all our tests
class ProfileTest extends UnitTestCase {
	// variable to hold the mySQL connection
	private $mysqli = null;
	// variable to hold the test Profile object
	private $profile = null;
	// variable to hold the test User object
	private $user = null;
	// a few "global" variables for creating test data
	private $USERFIRSTNAME	= "May";
	private $USERMIDDLENAME = "Lordes";
	private $USERLASTNAME   = "White";
	private $DATEOFBIRTH = null;
	private $CUSTTOKEN  = null;

	// setUp() is a method that is run before each test
	// here, we use it to connect to mySQL and to calculate the salt, hash, and authenticationToken
	/*
	 * create test User and test Profile
	 * */
	public function setUp() {
		// connect to mySQL
		mysqli_report(MYSQLI_REPORT_STRICT);
		$this->mysqli = new mysqli("localhost", "store_dylan", "deepdive", "store_dylan");

		// randomize the salt, hash, and authentication token for the profile
		$testSalt        = bin2hex(openssl_random_pseudo_bytes(32));
		$testAuthToken   = bin2hex(openssl_random_pseudo_bytes(16));
		$testHash        = hash_pbkdf2("sha512", "tEsTpASs", $testSalt, 2048, 128);
		try {
			$testUser = new User(null, "testUserEmailSetUp@test.com", $testHash, $testSalt, $testAuthToken);
			$this->user = $testUser;
			$this->user->insert($mysqli);
		} catch (Exception $exception) {
			$exception->getMessage();
		}
		$this->$DATEOFBIRTH = DateTime::createFromFormat(Y-m-d,"2010/10/10");
		$this->CUSTTOKEN    = bin2hex(openssl_random_pseudo_bytes(32));
	}

	// tearDown() is a method that is run after each test
	// here, we use it to delete the test record and disconnect from mySQL
	public function tearDown() {
		// delete the user if we can
		if($this->profile !== null) {
			$this->profile->delete($this->mysqli);
			$this->profile = null;
			if($this->user !== null) {
				$this->user->delete($this->mysqli);
				$this->user = null;
			}
		}

		// disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
		}
	}

	// test creating a new Profile and inserting it to mySQL
	public function testInsertNewProfile() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);
		try {
			// second, create a profile to post to mySQL
			$this->profile = new Profile(null, $this->user->getUserId(), $this->USERFIRSTNAME, $this->USERMIDDLENAME,
				$this->USERLASTNAME, $this->DATEOFBIRTH, $this->CUSTTOKEN);
			// third, insert the profile to mySQL
			$this->profile->insert($this->mysqli);
		} catch (Exception $exception){
			$exception->getMessage();
		}
				// compare the fields
		$this->assertNotNull($this->profile->getProfileId());
		$this->assertTrue($this->profile->getProfileId() > 0);
		$this->assertNotNull($this->profile->getUserId());
		$this->assertTrue($this->profile->getUserId() > 0);
		$this->assertIdentical($this->profile->__get("userFirstName"),   $this->USERFIRSTNAME);
		$this->assertIdentical($this->profile->__get("userMiddleName"),  $this->USERMIDDLENAME);
		$this->assertIdentical($this->profile->__get("userLastName"),    $this->USERLASTNAME);
		$this->assertIdentical($this->profile->__get("dateOfBirth"),     $this->DATEOFBIRTH);
		$this->assertIdentical($this->profile->__get("customerToken"),   $this->CUSTTOKEN);
		//-----------------------------------------------------------------------------------------------------------
		// compare the profile object data against the data in the database
		//pull the data from the update to compare against it
		$row = $this->selectRow();
		// finally, compare the fields against the row data pulled from the database
		$this->assertIdentical($this->profile->__get('profileId'),						  $row['profileId']);
		$this->assertIdentical($this->profile->__get('userId'),							  $row['userId']);
		$this->assertIdentical($this->profile->__get("userFirstName"),               $row['userFirstName']);
		$this->assertIdentical($this->profile->__get("userMiddleName"),              $row['userMiddleName']);
		$this->assertIdentical($this->profile->__get("userLastName"),                $row['userLastName']);
		$this->assertIdentical($this->profile->__get("dateOfBirth"),                 $row['dateOfBirth']);
		$this->assertIdentical($this->profile->__get("customerToken"),               $row['customerToken']);
	}

	// test updating a Profile in mySQL
	public function testUpdateUser()
	{
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);
		try{
		// second, create a profile to post to mySQL
		$this->profile = new Profile(null, $this->user->getUserId(), $this->USERFIRSTNAME, $this->USERMIDDLENAME,
			$this->USERLASTNAME, $this->DATEOFBIRTH, $this->CUSTTOKEN);
		// third, insert the profile to mySQL
		$this->profile->insert($this->mysqli);
		}catch(Exception $ex){
			$ex->getMessage();
		}
		// fourth, update the profile and post the changes to mySQL
		$newFirstName = "upDateTestName:";
		$newMiddleName = "Mandi";
		$newLastName = "Lusco";
		$newDateOfBirth = "11/11/2011";
		$newCustomerToken = "AXvty2139SAIpOTRVXC";
		$this->profile->setFirstName($newFirstName);
		$this->profile->setMiddleName($newMiddleName);
		$this->profile->setLastName($newLastName);
		$this->profile->setDateOfBirth($newDateOfBirth);
		$this->profile->setCustomerToken($newCustomerToken);
		$this->profile->update($this->mysqli);
		//compare testClass values against profile object values
		$this->assertNotNull($this->profile->getProfileId());
		$this->assertTrue($this->profile->getProfileId() > 0);
		$this->assertNotNull($this->profile->getUserId());
		$this->assertTrue($this->profile->getUserId() > 0);
		$this->assertIdentical($this->profile->__get("userFirstName"),   $this->USERFIRSTNAME);
		$this->assertIdentical($this->profile->__get("userMiddleName"),  $this->USERMIDDLENAME);
		$this->assertIdentical($this->profile->__get("userLastName"),    $this->USERLASTNAME);
		$this->assertIdentical($this->profile->__get("dateOfBirth"),     $this->DATEOFBIRTH);
		$this->assertIdentical($this->profile->__get("customerToken"),   $this->CUSTTOKEN);
		//---------------------------------------------------------------------------------------------------------
		$row = $this->selectRow();
		// finally, compare the fields against the row data pulled from the database
		$this->assertIdentical($this->profile->__get('profileId'),						  $row['profileId']);
		$this->assertIdentical($this->profile->__get('userId'),							  $row['userId']);
		$this->assertIdentical($this->profile->__get("userFirstName"),               $row['userFirstName']);
		$this->assertIdentical($this->profile->__get("userMiddleName"),              $row['userMiddleName']);
		$this->assertIdentical($this->profile->__get("userLastName"),                $row['userLastName']);
		$this->assertIdentical($this->profile->__get("dateOfBirth"),                 $row['dateOfBirth']);
		$this->assertIdentical($this->profile->__get("customerToken"),               $row['customerToken']);
	}

	// test deleting a User
	public function testDeleteUser() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a user to post to mySQL
		$this->profile = new Profile(null, $this->user->getUserId(), $this->USERFIRSTNAME, $this->USERMIDDLENAME,
			$this->USERLASTNAME, $this->DATEOFBIRTH, $this->CUSTTOKEN);

		// third, insert the user to mySQL
		$this->profile->insert($this->mysqli);

		// fourth, verify the User was inserted
		$this->assertNotNull($this->profile->getProfileId());
		$this->assertTrue($this->profile->getProfileId() > 0);

		// fifth, delete the user
		$this->profile->delete($this->mysqli);
		$this->profile = null;

		// finally, try to get the user and assert we didn't get a thing
		$hopefulProfile = User::getProfileByProfileId($this->mysqli, $this->profile->__get['profileId']);
		$this->assertNull($hopefulProfile);
	}

	// test grabbing a User from mySQL
	public function testGetProfileByUserId() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a user to post to mySQL
		$this->profile = new Profile(null, $this->user->getUserId(), $this->USERFIRSTNAME, $this->USERMIDDLENAME,
			$this->USERLASTNAME, $this->DATEOFBIRTH, $this->CUSTTOKEN);

		// third, insert the user to mySQL
		$this->user->insert($this->mysqli);

		// fourth, get the user using the static method
		$staticProfile = Profile::getProfileByUserId($this->mysqli, $this->user->getUserId());

		// finally, compare the fields
		$this->assertNotNull($staticProfile->__get('profileId'));
		$this->assertTrue($staticProfile->__get('profileId') > 0);
		$this->assertNotNull($staticProfile->__get('userId'));
		$this->assertTrue($staticProfile->__get('userId') > 0);
		$this->assertIdentical($staticProfile->__get('userFirstName'),   $this->USERFIRSTNAME);
		$this->assertIdentical($staticProfile->__get('userMiddleName'),  $this->USERMIDDLENAME);
		$this->assertIdentical($staticProfile->__get('userLastName'),    $this->USERLASTNAME);
		$this->assertIdentical($staticProfile->__get('dateOfBirth'),     $this->DATEOFBIRTH);
		$this->assertIdentical($staticProfile->__get('customerToken'),   $this->CUSTTOKEN);
		//-----------------------------------------------------------------------------------------
		$row = $this->selectRow();
		// finally, compare the fields against the row data pulled from the database
		$this->assertIdentical($this->profile->__get('profileId'),						  $row['profileId']);
		$this->assertIdentical($this->profile->__get('userId'),							  $row['userId']);
		$this->assertIdentical($this->profile->__get("userFirstName"),               $row['userFirstName']);
		$this->assertIdentical($this->profile->__get("userMiddleName"),              $row['userMiddleName']);
		$this->assertIdentical($this->profile->__get("userLastName"),                $row['userLastName']);
		$this->assertIdentical($this->profile->__get("dateOfBirth"),                 $row['dateOfBirth']);
		$this->assertIdentical($this->profile->__get("customerToken"),               $row['customerToken']);
	}

	private function selectRow(){

		//pull the data from the update to compare against it
		try {
			$query = "SELECT profileId, userId, userFirstName, userMiddleName, userLastName, dateOfBirth, customerToken
					 FROM profile WHERE profileId = ?";
			$statement = $this->mysqli->prepare($query);
			$statement->bind_param("i", $this->profileId);
			$statement->execute();
			$result = $statement->get_result();
			$row = $result->fetch_assoc();
		}catch(mysqli_sql_exception $sqlException){
			$sqlException->getMessage();
		}
		return $row;
	}

}





?>