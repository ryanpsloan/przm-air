<?php

	/**
	 * mySQL Enabled User
	 *
	 * This is a mySQL enabled container for User authentication at a typical eCommcerce site. It can easily be extended to include more fields as necessary.
	 *
	 * @author Dylan McDonald <dmcdonald21@cnm.edu>
	 * @see Profile
	 **/
class User {
	/**
	 * user id for the User; this is the primary key
	 **/
	private $userId;
	/**
	 * email for the User; this is a unique field
	 **/
	private $email;
	/**
	 * SHA512 PBKDF2 hash of the password
	 **/
	private $password;
	/**
	 * salt used in the PBKDF2 hash
	 **/
	private $salt;
	/**
	 * authentication token used in new accounts and password resets
	 **/
	private $authenticationToken;

	/**
	 * constructor for User
	 *
	 * @param mixed $newUserId user id (or null if new object)
	 * @param string $newEmail email
	 * @param string $newPassword PBKDF2 hash of the password
	 * @param string $newSalt salt used in the PBKDF2 hash
	 * @param mixed $newAuthenticationToken authentication token used in new accounts and password resets (or null if active User)
	 * @throws UnexpectedValueException when a parameter is of the wrong type
	 * @throws RangeException when a parameter is invalid
	 **/
	public function __construct($newUserId, $newEmail, $newPassword, $newSalt, $newAuthenticationToken) {
		try {
			$this->setUserId($newUserId);
			$this->setEmail($newEmail);
			$this->setPassword($newPassword);
			$this->setSalt($newSalt);
			$this->setAuthenticationToken($newAuthenticationToken);
		} catch(UnexpectedValueException $unexpectedValue) {
			// rethrow to the caller
			throw(new UnexpectedValueException("Unable to construct User", 0, $unexpectedValue));
		} catch(RangeException $range) {
			// rethrow to the caller
			throw(new RangeException("Unable to construct User", 0, $range));
		}
	}

	/**
	 * gets the value of user id
	 *
	 * @return mixed user id (or null if new object)
	 **/
	public function getUserId() {
		return($this->userId);
	}

	/**
	 * sets the value of user id
	 *
	 * @param mixed $newUserId user id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if user id isn't positive
	 **/
	public function setUserId($newUserId) {
		// zeroth, set allow the user id to be null if a new object
		if($newUserId === null) {
			$this->userId = null;
			return;
		}

		// first, ensure the user id is an integer
		if(filter_var($newUserId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("user id $newUserId is not numeric"));
		}

		// second, convert the user id to an integer and enforce it's positive
		$newUserId = intval($newUserId);
		if($newUserId <= 0) {
			throw(new RangeException("user id $newUserId is not positive"));
		}

		// finally, take the user id out of quarantine and assign it
		$this->userId = $newUserId;
	}

	/**
	 * gets the value of email
	 *
	 * @return string value of email
	 **/
	public function getEmail() {
		return($this->email);
	}

	/**
	 * sets the value of email
	 *
	 * @param string $newEmail email
	 * @throws UnexpectedValueException if the input doesn't appear to be an Email
	 **/
	public function setEmail($newEmail) {
		// sanitize the Email as a likely Email
		$newEmail = trim($newEmail);
		if(($newEmail = filter_var($newEmail, FILTER_SANITIZE_EMAIL)) == false) {
			throw(new UnexpectedValueException("email $newEmail does not appear to be an email address"));
		}

		// then just take email out of quarantine
		$this->email = $newEmail;
	}

	/**
	 * gets the value of password
	 *
	 * @return string value of password
	 **/
	public function getPassword() {
		return($this->password);
	}

	/**
	 * sets the value of password
	 *
	 * @param string $newPassword SHA512 PBKDF2 hash of the password
	 * @throws RangeException when input isn't a valid SHA512 PBKDF2 hash
	 **/
	public function setPassword($newPassword) {
		// verify the password is 128 hex characters
		$newPassword   = trim($newPassword);
		$newPassword   = strtolower($newPassword);
		$filterOptions = array("options" => array("regexp" => "/^[\da-f]{128}$/"));
		if(filter_var($newPassword, FILTER_VALIDATE_REGEXP, $filterOptions) === false) {
			throw(new RangeException("password is not a valid SHA512 PBKDF2 hash"));
		}

		// finally, take the password out of quarantine
		$this->password = $newPassword;
	}

	/**
	 * gets the value of salt
	 *
	 * @return string value of salt
	 **/
	public function getSalt() {
		return($this->salt);
	}

	/**
	 * sets the value of salt
	 *
	 * @param string $newSalt salt (64 hexadecimal bytes)
	 * @throws RangeException when input isn't 64 hexadecimal bytes
	 **/
	public function setSalt($newSalt) {
		// verify the salt is 64 hex characters
		$newSalt   = trim($newSalt);
		$newSalt   = strtolower($newSalt);
		$filterOptions = array("options" => array("regexp" => "/^[\da-f]{64}$/"));
		if(filter_var($newSalt, FILTER_VALIDATE_REGEXP, $filterOptions) === false) {
			throw(new RangeException("salt is not 64 hexadecimal bytes"));
		}

		// finally, take the salt out of quarantine
		$this->salt = $newSalt;
	}

	/**
	 * gets the value of authentication token
	 *
	 * @return mixed value of authentication token (or null if active User)
	 **/
	public function getAuthenticationToken() {
		return($this->authenticationToken);
	}

	/**
	 * sets the value of authentication token
	 *
	 * @param mixed $newAuthenticationToken authentication token (32 hexadecimal bytes) (or null if active User)
	 * @throws RangeException when input isn't 32 hexadecimal bytes
	 **/
	public function setAuthenticationToken($newAuthenticationToken) {
		// zeroth, set allow the authentication token to be null if an active object
		if($newAuthenticationToken === null) {
			$this->authenticationToken = null;
			return;
		}

		// verify the authentication token is 32 hex characters
		$newAuthenticationToken   = trim($newAuthenticationToken);
		$newAuthenticationToken   = strtolower($newAuthenticationToken);
		$filterOptions = array("options" => array("regexp" => "/^[\da-f]{32}$/"));
		if(filter_var($newAuthenticationToken, FILTER_VALIDATE_REGEXP, $filterOptions) === false) {
			throw(new RangeException("authentication token is not 32 hexadecimal bytes"));
		}

		// finally, take the authentication token out of quarantine
		$this->authenticationToken = $newAuthenticationToken;
	}

	/**
	 * inserts this User to mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the userId is null (i.e., don't insert a user that already exists)
		if($this->userId !== null) {
			throw(new mysqli_sql_exception("not a new user"));
		}

		// create query template
		$query     = "INSERT INTO user(email, password, salt, authenticationToken) VALUES(?, ?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ssss", $this->email, $this->password,
			$this->salt,  $this->authenticationToken);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		// update the null userId with what mySQL just gave us
		$this->userId = $mysqli->insert_id;
	}

	/**
	 * deletes this User from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the userId is not null (i.e., don't delete a user that hasn't been inserted)
		if($this->userId === null) {
			throw(new mysqli_sql_exception("Unable to delete a user that does not exist"));
		}

		// create query template
		$query     = "DELETE FROM user WHERE userId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("i", $this->userId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}

	/**
	 * updates this User in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the userId is not null (i.e., don't update a user that hasn't been inserted)
		if($this->userId === null) {
			throw(new mysqli_sql_exception("Unable to update a user that does not exist"));
		}

		// create query template
		$query     = "UPDATE user SET email = ?, password = ?, salt = ?, authenticationToken = ? WHERE userId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ssssi", $this->email, $this->password,
			$this->salt,  $this->authenticationToken,
			$this->userId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}

	/**
	 * gets the User by Email
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $email email to search for
	 * @return mixed User found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getUserByEmail(&$mysqli, $email) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the Email before searching
		$email = trim($email);
		$email = filter_var($email, FILTER_SANITIZE_EMAIL);

		// create query template
		$query     = "SELECT userId, email, password, salt, authenticationToken FROM user WHERE email = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the email to the place holder in the template
		$wasClean = $statement->bind_param("s", $email);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		// get result from the SELECT query *pounds fists*
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("Unable to get result set"));
		}

		// since this is a unique field, this will only return 0 or 1 results. So...
		// 1) if there's a result, we can make it into a User object normally
		// 2) if there's no result, we can just return null
		$row = $result->fetch_assoc(); // fetch_assoc() returns a row as an associative array

		// convert the associative array to a User
		if($row !== null) {
			try {
				$user = new User($row["userId"], $row["email"], $row["password"], $row["salt"], $row["authenticationToken"]);
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception("Unable to convert row to User", 0, $exception));
			}

			// if we got here, the User is good - return it
			return($user);
		} else {
			// 404 User not found - return null instead
			return(null);
		}
	}

	public static function getUserByUserId(&$mysqli, $userId)
	{
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("Input is not a mysqli object"));
		}

		// first, ensure the profileId is an integer
		if(filter_var($userId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("user id $userId is not numeric"));
		}

		// second, convert the user id to an integer and enforce it's positive
		$userId = intval($userId);
		if($userId <= 0) {
			throw(new RangeException("user id $userId is not positive"));
		}
		//CREATE QUERY TEMPLATE
		$query = "SELECT userId, email, password, salt, authenticationToken
					FROM user WHERE userId = ? ";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		//bind the profileId to the place holder in the template
		$wasClean = $statement->bind_param("i", $userId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		//execute statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		//get result from the SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("Unable to get result set"));
		}

		/* since this is a unique field, this will only return 0 or 1 results so
		 * 1) if there's a result, we can make it into a channel object normally
		 * 2) if there's no result, we can just return null
		 * */
		$row = $result->fetch_assoc(); //fetch_assoc() returns a row as an associative array

		//convert the associate array to user
		if($row !== null) {
			try {
				$profile = new Profile ($row['profileId'], $row['userId'],$row['userFirstName'],
					$row['userMiddleName'],$row['userLastName'], $row['dateOfBirth'], $row['customerToken']);
			} catch(Exception $exception) {
				//if row can't be converted rethrow
				throw(new mysqli_sql_exception("Unable to convert row to Profile Object", 0, $exception));
			}

			//if we got here, the Profile Object is good - return it
			return ($profile);
		} else {
			//404 profile not found
			return (null);
		}
	}


}
?>