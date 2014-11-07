<?php
/**
 *	mySQL enabled traveler class to gather input retrieve and store traveler data
 * @author Ryan Sloan <ryansdeal@hotmail.com
 */
class Traveler{
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
	 */
	public function _get($name){
		//updates with the current value of the classes properties and returns the selected key
		$data = array('travelerId' 				=> $this->travelerId,
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

	}

}
?>