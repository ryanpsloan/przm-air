<?php
/**
 * mySQL Enabled Flight
 *
 * This is a class that enables a user to create objects out of the ingredient data pulled from the database using
 * Lavu's API.  Functions in the class enables manipulation and sorting of these objects based on user-set criteria for
 * better management of in-stock inventory, purchasing, and history.
 *
 * @author Zach Grant <zgrant28@gmail.com> for the Deep Dive Team at the Lavu Hackathon, Dec 5-7 2014
 * Date: 12/5/14
 * Time: 10:42 PM
 */

class Inventory
{
	/**
	 * string
	 */
	private $title;
	/**
	 * string
	 */
	private $qty;
	/**
	 * string
	 */
	private $unit;
	/**
	 * string
	 */
	private $low;
	/**
	 * string
	 */
	private $high;
	/**
	 * string
	 */
	private $id;
	/**
	 * string
	 */
	private $category;
	/**
	 * string
	 */
	private $cost;
	/**
	 * string
	 */
	private $loc_id; //I don't think we need this field but it is part of the json data return
	/**
	 * string
	 */
	private $chain_reporting_group;   //Again, probably don't need this

	/*Here's what the data looked like after it was formatted into an associative array
	array (size=18)
	  0 =>
		 object(stdClass)[4]
			public 'title' => string 'Darigold Chocolate Milk' (length=23)
			public 'qty' => string '6' (length=1)
			public 'unit' => string 'Case' (length=4)
			public 'low' => string '' (length=0)
			public 'high' => string '' (length=0)
			public 'id' => string '1' (length=1)
			public 'category' => string '1' (length=1)
			public 'cost' => string '' (length=0)
			public 'loc_id' => string '1' (length=1)
			public 'chain_reporting_group' => string '' (length=0)
	  1 =>
		 object(stdClass)[9]
			public 'title' => string '2%' (length=2)
			public 'qty' => string '9' (length=1)
			public 'unit' => string 'Case' (length=4)
			public 'low' => string '' (length=0)
			public 'high' => string '' (length=0)
			public 'id' => string '2' (length=1)
			public 'category' => string '1' (length=1)
			public 'cost' => string '' (length=0)
			public 'loc_id' => string '1' (length=1)
			public 'chain_reporting_group' => string '' (length=0)
		2 =>
			.... run the query for full disclosure marc said don't run to many or it will lock us out for 12 min*/

	/**
	 * constructor for ingredients
	 * @param string $newTitle
	 * @param string $newQty
	 * @param string $newUnit
	 * @param string $newLow
	 * @param string $newHigh
	 * @param string $newId
	 * @param string $newCategory
	 * @param string $newCost
	 * @param string $newLoc_Id
	 * @param string $newChain_Reporting_Group
	 * @throws UnexpectedValueException when a parameter is of the wrong type
	 * @throws RangeException when a parameter is invalid
	 **/
	public function __construct($newTitle, $newQty, $newUnit, $newLow, $newHigh,
										 $newId, $newCategory, $newCost, $newLoc_Id, $newChain_Reporting_Group)
	{
		try {
			$this->setTitle($newTitle);
			$this->setQty($newQty);
			$this->setUnit($newUnit);
			$this->setDuration($newLow);
			$this->setHigh($newHigh);
			$this->setId($newId);
			$this->setCategory($newCategory);
			$this->setCost($newCost);
			$this->setLoc_Id($newLoc_Id);
			$this->setChain_Reporting_Group($newChain_Reporting_Group);

		} catch(UnexpectedValueException $unexpectedValue) {
			// rethrow to the caller
			throw(new UnexpectedValueException("Unable to construct Inventory", 0, $unexpectedValue));
		} catch(RangeException $range) {
			// rethrow to the caller
			//var_dump($range);
			throw(new RangeException("Unable to construct Inventory", 0, $range));
		}
	}

	/**
	 * gets the value of Title
	 *
	 * @return string Title (or null if new object)
	 **/
	public function getTitle()
	{
		return ($this->title);
	}

	/**
	 * sets the value of Title
	 *
	 * @param string $newTitle
	 * @throws UnexpectedValueException if not a string
	 **/
	public function setTitle($newTitle)
	{
		// verify the input is a string
		$newTitle = trim($newTitle);

		if(filter_var($newTitle, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("Title $newTitle does not appear to be a string"));
		}

		// finally, take the title out of quarantine
		$this->title = $newTitle;
	}

	/**
	 * gets the value of qty
	 *
	 * @return mixed qty
	 **/
	public function getQty() {
		return($this->qty);
	}

	/**
	 * sets the value of qty
	 *
	 * @param string $newQty quantity
	 * @throws UnexpectedValueException if not a string and if can't convert to integer or null
	 * @throws RangeException if quantity isn't positive
	 **/
	public function setQty($newQty)
	{
		// verify the input is a string
		$newQty = trim($newQty);

		if(filter_var($newQty, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("Quantity $newQty does not appear to be a string"));
		}

		// convert to integer
		$newQty = intval($newQty); // fixme convert here?
		// first, ensure the qty is an integer
		if(filter_var($newQty, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("Quantity $newQty is not numeric"));
		}

		// second, enforce it's positive
		if($newQty <= 0) {
			throw(new RangeException("Quantity  $newQty is not positive"));
		}

		// finally, take the input out of quarantine and assign it
		$this->qty = $newQty;
	}

	/**
	* gets the value of unit
	*
	* @return string unit
	**/
	public function getUnit() {
		return($this->unit);
	}

	/**
	 * sets the value of unit
	 *
	 * @param string $newUnit
	 * @throws UnexpectedValueException if not a string
	 **/
	public function setUnit($newUnit) {
		// verify the input is a string
		$newUnit = trim($newUnit);

		if(filter_var($newUnit, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("Unit type $newUnit does not appear to be a string"));
		}

		// finally, take the title out of quarantine
		$this->unit = $newUnit;
	}

	/**
	 * gets the value of low
	 *
	 * @return mixed low
	 **/
	public function getLow() {
		return($this->low);
	}

	/**
	 * sets the value of low
	 *
	 * @param string $newLow low
	 * @throws UnexpectedValueException if not a string and if can't convert to integer or null
	 * @throws RangeException if low isn't positive
	 **/
	public function setLow($newLow)
	{
		// verify the input is a string
		$newLow = trim($newLow);

		if(filter_var($newLow, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("Value of low of $newLow does not appear to be a string"));
		}

		// convert to integer
		$newLow = intval($newLow); // fixme convert here?
		// first, ensure the low is an integer
		if(filter_var($newLow, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("Value for low of $newLow is not numeric"));
		}

		// second, enforce it's positive
		if($newLow <= 0) {
			throw(new RangeException("Minimum quantity $newLow is not positive"));
		}

		// finally, take the input out of quarantine and assign it
		$this->low = $newLow;
	}

	/**
	 * gets the value of high
	 *
	 * @return mixed high
	 **/
	public function getHigh() {
		return($this->high);
	}

	/**
	 * sets the value of high
	 *
	 * @param string $newHigh quantity
	 * @throws UnexpectedValueException if not a string and if can't convert to integer or null
	 * @throws RangeException if quantity isn't positive
	 **/
	public function setHigh($newHigh)
	{
		// verify the input is a string
		$newHigh = trim($newHigh);

		if(filter_var($newHigh, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("Value for high of  $newHigh does not appear to be a string"));
		}

		// convert to integer
		$newHigh = intval($newHigh); // fixme convert here?
		// first, ensure the qty is an integer
		if(filter_var($newHigh, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("Value for high of $newHigh is not numeric"));
		}

		// second, enforce it's positive
		if($newHigh <= 0) {
			throw(new RangeException("Value for high of $newHigh is not positive"));
		}

		// finally, take the input out of quarantine and assign it
		$this->high = $newHigh;
	}

	/**
	 * gets the value of id
	 *
	 * @return mixed id
	 **/
	public function getId() {
		return($this->id);
	}

	/**
	 * sets the value of id
	 *
	 * @param string $newId quantity
	 * @throws UnexpectedValueException if not a string and if can't convert to integer or null
	 * @throws RangeException if id isn't positive
	 **/
	public function setId($newId)
	{
		// verify the input is a string
		$newId = trim($newId);

		if(filter_var($newId, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("ID $newId does not appear to be a string"));
		}

		// convert to integer
		$newQty = intval($newQty); // fixme convert here?
		// first, ensure the qty is an integer
		if(filter_var($newQty, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("ID $newId is not numeric"));
		}

		// second, enforce it's positive
		if($newQty <= 0) {
			throw(new RangeException("ID $newId is not positive"));
		}

		// finally, take the input out of quarantine and assign it
		$this->id = $newId;
	}

	/**
	 * gets the value of category
	 *
	 * @return string category
	 **/
	public function getCategory() {
		return($this->category);
	}

	/**
	 * sets the value of category
	 *
	 * @param string $newCategory
	 * @throws UnexpectedValueException if not a string
	 **/
	public function setCategory($newCategory) {
		// verify the input is a string
		$newCategory = trim($newCategory);

		if(filter_var($newCategory, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("Category description $newCategory does not appear to be a string"));
		}

		// finally, take the input out of quarantine
		$this->category = $newCategory;
	}


	/**
	 * gets the value of loc_id
	 *
	 * @return string loc_id
	 **/
	public function getLoc_Id() {
		return($this->loc_id);
	}

	/**
	 * sets the value of loc_id
	 *
	 * @param string $newLoc_Id
	 * @throws UnexpectedValueException if not a string
	 **/
	public function setLoc_Id($newLoc_Id) {
		// verify the input is a string
		$newLoc_Id = trim($newLoc_Id);

		if(filter_var($newLoc_Id, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("Location ID $newLoc_Id does not appear to be a string"));
		}

		// finally, take the input out of quarantine
		$this->loc_id = $newLoc_Id;
	}

	/**
	 * gets the value of chain_reporting_group
	 *
	 * @return string chain_reporting_group
	 **/
	public function getChain_Reporting_Group() {
		return($this->chain_reporting_group);
	}

	/**
	 * sets the value of chain_reporting_group
	 *
	 * @param string $newChain_Reporting_Group
	 * @throws UnexpectedValueException if not a string
	 **/
	public function setChain_Reporting_Group($newChain_Reporting_Group) {
		// verify the input is a string
		$newChain_Reporting_Group = trim($newChain_Reporting_Group);

		if(filter_var($newChain_Reporting_Group, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("Chain reporting group $newChain_Reporting_Group does not appear to be a string"));
		}

		// finally, take the input out of quarantine
		$this->chain_reporting_group = $newChain_Reporting_Group;
	}


}



?>






