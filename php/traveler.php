<?php
/**
 *	mySQL enabled traveler class to gather input retrieve and store traveler data
 * @author Ryan Sloan <ryansdeal@hotmail.com
 */
class Traveler{
	private $profileObj;
	/**
	 * int primary key
	 */
	private $travelerId;
	/**
	 * int foreign key
	 */
	private $profileId;
	/**
	 * string
	 */
	private $travelerFirstName;
	/**
	 *  string
	 */
	private $travelerMiddleName;
	/**
	 * string
	 */
	private $travelerLastName;
	/**
	 * date
	 */
	private $travelerDateOfBirth;

	/**
	 * @param int $newTravelerId
	 * @param int $newProfileId
	 * @param string $newFirstName
	 * @param string $newMiddleName
	 * @param string $newLastName
	 * @param date $newDateOfBirth
	 * @throws UnexpectedValueException
	 * @throws RangeValueException
	 */

	public function __construct($newTravelerId, $newProfileId, $newFirstName, $newMiddleName, $newLastName,
										 $newDateOfBirth){
										try{
												$this->setTravelerId($newTravelerId);
												$this->setProfileId($newProfileId);
												$this->setFirstName($newFirstName);
												$this->setMiddleName($newMiddleName);
												$this->setLastName($newLastName);
											   $this->setDateOfBirth($newDateOfBirth);
										} catch(UnexpectedValueException $unexpectedValue) {
											// rethrow to the caller
											throw(new UnexpectedValueException("Unable to construct Profile Object. Check input formats.", 0,
											$unexpectedValue));
										} catch(RangeException $range) {
											// rethrow to the caller
											throw(new RangeException("Unable to construct Profile Object. Check input formats.", 0, $range));
										}

	}
	/**
	 * get function will return the value of inserted key from assoc array filled with
	 * current values of class properties
	 * @param string $name
	 * @throws ErrorException if $name is not a key value
	 */
	public function _get($name){
		//updates with the current value of the classes properties and returns the selected key
		$data = array('profileObj'             => $this->profileObj,
			           'travelerId' 				=> $this->travelerId,
		              'profileId'  				=> $this->profileId,
			           'travelerFirstName' 		=> $this->travelerFirstName,
						  'travelerMiddleName' 		=> $this->travelerMiddleName,
			           'travelerLastName' 		=> $this->travelerLastName,
			           'travelerDateOfBirth'    => $this->travelerDateOfBirth
		);
		if (array_key_exists($name, $data)) {
				return $data[$name];
		}
		else {
				throw(new ErrorException("Unable to get $name. Check that the key exists."));
		}
	}
	/**
	 * sets a profile object into the traveler class
	 * @param Profile Obj $newProfileObj
	 * @throw UnexpectedValueException if object is null or not a profile object
	 */
	public function setProfileObj($newProfileObj)
	{
		if(gettype($newProfileObj) != 'object' || get_class($newProfileObj) != 'Profile') {
			throw(new UnexpectedValueException("Argument is not a Profile Object"));
		}

		if($newProfileObj === null){
			throw(new UnexpectedValueException("Unable to set a profile that is null"));
		}

		$this->profileObj = $newProfileObj;
	}
	/**
	 * Method argument is tested to be positive an integer and not null if is null null is set into the
	 * class property if passes quarantine the int value is set into the class property $travelerId
	 * @param int $newTravelerId
	 * @throw UnexpectedValueException if not integer
	 * @throw RangeException if is not positive
	 */
	public function setTravelerId($newTravelerId){
		if($newTravelerId === null){
			$this->travelerId = null;
			return;
		}

		if(filter_var($newTravelerId, FILTER_VALIDATE_INT) === false){
			throw(new UnexpectedValueException("Argument $newTravelerId must be an integer"));
		}

		$newTravelerId = intval($newTravelerId);
		if($newTravelerId <= 0){
			throw(new RangeException("Argument $newTravelerId is not positive"));
		}

		$this->travelerId = $newTravelerId;
	}

	/**
	 * sets argument value into class property $profileId after evaluating if is null,positive, and an integer
	 * @param int $newProfileId
	 * @throw UnexpectedValueException if is not an integer
	 * @throw RangeException if is not positive
	 */
	public function setProfileId($newProfileId){
		//if is null set class property to null
		if($newProfileId === null){
			$this->profileId = null;
			return;
		}
		//test is an integer
		if(filter_var($newProfileId,FILTER_VALIDATE_INT) === false){
			throw(new UnexpectedValueException("Argument $newProfileId is not an integer"));
		}
		//test if is positive
		if($newProfileId <= 0){
			throw(new RangeException("Argument $newProfileId is not positive"));
		}
		//if passes previous tests set into class property $profileId
		$this->profileId = $newProfileId;

	}

	/**
	 * sets $userFirstName with a string representing the first name after trimming setting to lowercase and REGEX
	 * validation
	 * @param string $newFirstName
	 * @throw InvalidArgumentException if string contains any character other than letters
	 */

	public function setFirstName($newFirstName){
		//first trim the incoming string argument
		$newFirstName = trim($newFirstName);
		//force all characters to lower case
		$newFirstName = strtolower($newFirstName);
		//validate the string using REGEX
		$filterOptions = array('options' => array("regexp" => "/^[a-zA-Z]{1,50}$/"));
		if(filter_var($newFirstName,FILTER_VALIDATE_REGEXP, $filterOptions) === false){
			throw(new InvalidArgumentException("Argument $newFirstName must be [a-zA-Z] no special characters or spaces"));
		}
		//set into class variable
		$this->travelerFirstName = $newFirstName;
	}

	/**
	 * sets the argument string into class property $userFirstName after validation trimming and set to lowercase
	 * @param string $newMiddleName
	 * @throw InvalidArgumentException when argument has characters other than letters
	 */
	public function setMiddleName($newMiddleName){
		//first trim the incoming string argument
		$newMiddleName = trim($newMiddleName);
		//force all characters to lower case
		$newMiddleName = strtolower($newMiddleName);
		//validate the string using REGEX
		$filterOptions = array('options' => array("regexp" => "/^[a-zA-Z]{1,50}$/"));
		if(filter_var($newMiddleName,FILTER_VALIDATE_REGEXP, $filterOptions) === false){
			throw(new InvalidArgumentException("Argument $newMiddleName must be [a-zA-Z] no special characters or spaces"));
		}
		//set into class variable
		$this->travelerMiddleName = $newMiddleName;
	}

	/**
	 * sets $userLastName with a string argument after validating trimming and setting to all lowercase
	 * @param string $newLastName
	 * @throw InvalidArgumentException when argument is composed of any other characters other than letters
	 */
	public function setLastName($newLastName){
		//first trim the incoming string argument
		$newLastName = trim($newLastName);
		//force all characters to lower case
		$newLastName = strtolower($newLastName);
		//validate the string using REGEX
		$filterOptions = array('options' => array("regexp" => "/^[a-zA-Z]{1,50}$/"));
		if(filter_var($newLastName,FILTER_VALIDATE_REGEXP, $filterOptions) === false){
			throw(new InvalidArgumentException("Argument $newLastName must be [a-zA-Z] no special characters or spaces"));
		}
		//set into class variable
		$this->travelerLastName = $newLastName;
	}

	public function setDateOfBirth($newDateOfBirth){
		//polish the string
		$newDateOfBirth = trim($newDateOfBirth);
		//test if is in date format mm/dd/yyyy
		$filterOptions = array("options" => array("regexp" => "/^(0[1-9]|1[0-2])\/([0-2][1-9]|3[0-1])\/(19|20)\d{2}$/"));
		if(filter_var($newDateOfBirth, FILTER_VALIDATE_REGEXP, $filterOptions)){
			throw(new RangeException("Date entered doesn't match mm/dd/yyyy format or is out of range"));
		}
		//tokenize the date string into mm dd and yyyy
		$dateArray = array('month' => strtok($newDateOfBirth , "\\"), 'day' => strtok("\\"), 'year' => strtok("\\"));
		//reformat the date for the database
		$reformattedDate = $dateArray['year']."\\".$dateArray['month']."\\".$dateArray['day'];
		$format = 'Y-m-d';
		$this->travelerDateOfBirth = DateTime::createFromFormat($format, $reformattedDate);

	}

	/**
	 * mySQL enabled class which inserts current class property values into the database
	 *
	 * @param $mysqli
	 * @throws my_mysqli_exception when travelerId is not null or mysqli is not a mysqli object
	 */
	public function insert(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce that travelerId is null (i.e., don't insert a profile that already exists)
		if($this->travelerId !== null) {
			throw(new mysqli_sql_exception("not a new traveler"));
		}

		// create query template
		$query     = "INSERT INTO traveler (travelerId, profileId, travelerFirstName, travelerMiddleName, travelerLastName,
							travelerDateOfBirth)VALUES(?, ?, ?, ?, ?, ?)";
		$statement = $mysqli->prepare($query);

		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("iissss", $this->travelerId, $this->profileId, $this->travelerFirstName,
			$this->travelerMiddleName, $this->travelerLastName, $this->travelerDateOfBirth);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		// update the null profileId with what mySQL just gave us
		$this->travelerId = $mysqli->insert_id;
	}

	/**
	 * deletes this Traveler from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the travelerId is not null (i.e., don't delete a traveler that hasn't been inserted)
		if($this->travelerId === null) {
			throw(new mysqli_sql_exception("Unable to delete a traveler that does not exist"));
		}

		// create query template
		$query     = "DELETE FROM traveler WHERE travelerId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("i", $this->travelerId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}

	/**
	 * updates this Traveler in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the travelerId is not null (i.e., don't update a traveler that hasn't been inserted)
		if($this->travelerId === null) {
			throw(new mysqli_sql_exception("Unable to update a traveler that does not exist"));
		}

		// create query template
		$query     = "UPDATE traveler SET travelerFirstName = ?, travelerMiddleName = ?, travelerLastName = ?,
							travelerDateOfBirth = ? WHERE travelerId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ssssi", $this->travelerFirstName, $this->travelerMiddleName,
			$this->travelerLastName, $this->travelerDateOfBirth, $this->travelerId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}

}
?>