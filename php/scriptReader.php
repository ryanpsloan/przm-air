<?php
function readFile(&$mysqli,$fileName)
{
	$output = fgetcsv($fileName);

	$query = "INSERT INTO template (templateId,flightNumber,originAirport,destinationAirport,departureTime,
	arrivalTime,duration) VALUES(?,?,?,?,?,?,?)";
	$statement = $mysqli->prepare($query);
	if($statement === false) {
		throw(new mysqli_sql_exception("Unable to prepare statement"));
	}
	$wasClean = $statement->bind_param("isssssi",$output[0],$output[1],$output[2],$output[3],$output[],$output[4],$output[5],$output[6]);
	if($wasClean === false) {
		throw(new mysqli_sql_exception("Unable to bind parameters"));
	}

// execute the statement
	if($statement->execute() === false) {
		throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
	}
}
?>
public function insert(&$mysqli) {


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