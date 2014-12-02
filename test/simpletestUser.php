<?php
// first require the SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");
require_once("/etc/apache2/capstone-mysql/przm.php");

// then require the class under scrutiny
require_once("../php/user.php");

// the UserTest is a container for all our tests`
class UserTest extends UnitTestCase {
	// variable to hold the mySQL connection
	private $mysqli = null;
	// variable to hold the test database row
	private $user   = null;
	// a few "global" variables for creating test data
	private $EMAIL		  = "rp@rps.com";
	private $PASSWORD   = "RyanGeek158*";
	private $HASH       = null;
	private $SALT       = null;
	private $AUTH_TOKEN = null;


	// setUp() is a method that is run before each test
	// here, we use it to connect to mySQL and to calculate the salt, hash, and authenticationToken
	public function setUp() {
		// connect to mySQL
		$this->mysqli = MysqliConfiguration::getMysqli();

		// randomize the salt, hash, and authentication token
		$this->SALT       = bin2hex(openssl_random_pseudo_bytes(32));
		$this->AUTH_TOKEN = bin2hex(openssl_random_pseudo_bytes(16));
		$this->HASH       = hash_pbkdf2("sha512", $this->PASSWORD, $this->SALT, 2048, 128);
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
		/*if($this->mysqli !== null) {
			$this->mysqli->close();
		}*/
	}

	// test creating a new User and inserting it to mySQL
	public function testInsertNewUser() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);
		try {

			// second, create a user to post to mySQL
			$this->user = new User(null, $this->EMAIL, $this->HASH, $this->SALT, $this->AUTH_TOKEN);

			// third, insert the user to mySQL
			$this->user->insert($this->mysqli);

		} catch(ErrorException $exception) {
			$exception->getMessage();
		}
		// finally, compare the fields
		$this->assertNotNull($this->user->getUserId());
		$this->assertTrue($this->user->getUserId() > 0);/*false*/
		$this->assertIdentical($this->user->getEmail(),           		$this->EMAIL);
		$this->assertIdentical($this->user->getPassword(),        		$this->HASH);
		$this->assertIdentical($this->user->getSalt(),            		$this->SALT);
		$this->assertIdentical($this->user->getAuthenticationToken(),	$this->AUTH_TOKEN);
		//------------------------------------------------------------------------------------------------------
		$row = $this->selectRow();
		// finally, compare the fields against the row data pulled from the database
		$this->assertIdentical($this->user->getUserId(),  $row['userId']);
		$this->assertIdentical($this->user->getEmail(),							        $row['email']);
		$this->assertIdentical($this->user->getPassword(),                        $row['password']);
		$this->assertIdentical($this->user->getSalt(),                            $row['salt']);
		$this->assertIdentical($this->user->getAuthenticationToken(),             $row['authToken']);
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
		$newEmail = "updateTestEmail@sloan.org";
		$this->user->setEmail($newEmail);
		$this->user->update($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->user->getUserId());
		$this->assertTrue($this->user->getUserId() > 0); /*false*/
		$this->assertIdentical($this->user->getEmail(),					  $newEmail);
		$this->assertIdentical($this->user->getPassword(),			     $this->HASH);
		$this->assertIdentical($this->user->getSalt(),			        $this->SALT);
		$this->assertIdentical($this->user->getAuthenticationToken(), $this->AUTH_TOKEN);
		//------------------------------------------------------------------------------------------------------
		$row = $this->selectRow();
		// finally, compare the fields against the row data pulled from the database
		$this->assertIdentical($this->user->getUserId(),						  	     $row['userId']);
		$this->assertIdentical($this->user->getEmail(),							        $row['email']);
		$this->assertIdentical($this->user->getPassword(),                        $row['password']);
		$this->assertIdentical($this->user->getSalt(),                            $row['salt']);
		$this->assertIdentical($this->user->getAuthenticationToken(),             $row['authToken']);
	}

	// test deleting a User
	public function testDeleteUser() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a user to post to mySQL
		$this->user = new User(null, $this->EMAIL, $this->HASH,
			$this->SALT, $this->AUTH_TOKEN);

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
		$this->assertIdentical($staticUser->getUserId(),         $this->user->getUserId());
		$this->assertIdentical($staticUser->getEmail(),          $this->EMAIL);
		$this->assertIdentical($staticUser->getPassword(),       $this->HASH);
		$this->assertIdentical($staticUser->getSalt(),             $this->SALT);
		$this->assertIdentical($staticUser->getAuthenticationToken(),$this->AUTH_TOKEN);
		//------------------------------------------------------------------------------------------------------
		$row = $this->selectRow();
		// finally, compare the fields against the row data pulled from the database
		$this->assertIdentical($staticUser->getUserId(),						  	     $row['userId']);
		$this->assertIdentical($staticUser->getEmail(),							        $row['email']);
		$this->assertIdentical($staticUser->getPassword(),                        $row['password']);
		$this->assertIdentical($staticUser->getSalt(),                            $row['salt']);
		$this->assertIdentical($staticUser->getAuthenticationToken(),             $row['authToken']);
	}

	// test grabbing a User from mySQL
	public function testGetUserByUserId() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a user to post to mySQL
		$this->user = new User(null, $this->EMAIL, $this->HASH, $this->SALT, $this->AUTH_TOKEN);

		// third, insert the user to mySQL
		$this->user->insert($this->mysqli);

		// fourth, get the user using the static method
		$staticUser = User::getUserByUserId($this->mysqli, $this->user->getUserId());

		// finally, compare the fields
		$this->assertNotNull($staticUser->getUserId());
		$this->assertTrue($staticUser->getUserId() > 0);
		$this->assertIdentical($staticUser->getUserId(),              $this->user->getUserId());
		$this->assertIdentical($staticUser->getEmail(),               $this->EMAIL);
		$this->assertIdentical($staticUser->getPassword(),            $this->HASH);
		$this->assertIdentical($staticUser->getSalt(),                $this->SALT);
		$this->assertIdentical($staticUser->getAuthenticationToken(), $this->AUTH_TOKEN);
		//-----------------------------------------------------------------------------------------
		$row = $this->selectRow();
		// finally, compare the fields against the row data pulled from the database
		$this->assertIdentical($staticUser->getUserId(),						  	     $row['userId']);
		$this->assertIdentical($staticUser->getEmail(),							        $row['email']);
		$this->assertIdentical($staticUser->getPassword(),                        $row['password']);
		$this->assertIdentical($staticUser->getSalt(),                            $row['salt']);
		$this->assertIdentical($staticUser->getAuthenticationToken(),             $row['authToken']);

	}

	// test grabbing a User from mySQL
	public function testGetUserByAuthToken() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a user to post to mySQL
		$this->user = new User(null, $this->EMAIL, $this->HASH, $this->SALT, $this->AUTH_TOKEN);

		// third, insert the user to mySQL
		$this->user->insert($this->mysqli);

		// fourth, get the user using the static method
		$staticUser = User::getUserByAuthToken($this->mysqli, $this->user->getAuthenticationToken());

		// finally, compare the fields
		$this->assertNotNull($staticUser->getUserId());
		$this->assertTrue($staticUser->getUserId() > 0);
		$this->assertIdentical($staticUser->getUserId(),              $this->user->getUserId());
		$this->assertIdentical($staticUser->getEmail(),               $this->EMAIL);
		$this->assertIdentical($staticUser->getPassword(),            $this->HASH);
		$this->assertIdentical($staticUser->getSalt(),                $this->SALT);
		$this->assertIdentical($staticUser->getAuthenticationToken(), $this->AUTH_TOKEN);
		//-----------------------------------------------------------------------------------------
		$row = $this->selectRow();
		// finally, compare the fields against the row data pulled from the database
		$this->assertIdentical($staticUser->getUserId(),						  	     $row['userId']);
		$this->assertIdentical($staticUser->getEmail(),							        $row['email']);
		$this->assertIdentical($staticUser->getPassword(),                        $row['password']);
		$this->assertIdentical($staticUser->getSalt(),                            $row['salt']);
		$this->assertIdentical($staticUser->getAuthenticationToken(),             $row['authToken']);

	}

	private function selectRow(){
	//pull the data from the update to compare against it
		try {
			$query = "SELECT userId, email, password, salt, authToken
						 FROM user WHERE userId = ?";
			$statement = $this->mysqli->prepare($query);
			$statement->bind_param("i", $this->user->getUserId());
			$statement->execute();
			$result = $statement->get_result();
			$row = $result->fetch_assoc();
		}catch(mysqli_sql_exception $sqlException){
			$sqlException->getMessage();
		}
		return $row;
	}
}
/*Coding is the most beautiful form of creation in the world better than film or music*/
?>
