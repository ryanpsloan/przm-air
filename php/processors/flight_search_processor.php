<?php
/**
 * Created by PhpStorm.
 * User: zwg2
 * Date: 12/3/14
 * Time: 10:12 AM
 */
// fixme get correct format from datepicker


require("/etc/apache2/capstone-mysql/przm.php");
require("../class/flight.php");
require("../../lib/csrf.php");


echo <<< EOF
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>PRZM AIR</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</head>
EOF;


/**
 * sets up all other needed variables that are same for outbound and return searches, then calls the method with all inputs
 * @param 	resource $mysqli pointer to temp mySQL connection, by reference
 * @param 	string $userOrigin with 3 letter origin city
 * @param 	string $userDestination with 3 letter destination city
 * @param 	string $userFlyDateStart of 7AM on user's chosen fly date
 * @return 	mixed $outputTable html table of search results
 **/
function completeSearch (&$mysqli, $userOrigin, $userDestination,
								 $userFlyDateStart)
{

	// can make this a user input in future to pre-filter results to a user-given duration amount in hours.
	$userFlyDateRange = 24;

	// can make this a user input in future to pre-filter results to a user-given number of records.  If all records are needed, can use empty($thisArrayOfPaths[$i]) === false; in the for loop below instead.
	$numberToShow = 15;

	$numberOfPassengersRequested = filter_input(INPUT_POST, "numberOfPassengers", FILTER_SANITIZE_NUMBER_INT);
	$minLayover = filter_input(INPUT_POST, "minLayover", FILTER_SANITIZE_NUMBER_INT);

//	echo "<p>121 inputs to method call </p>";
//	var_dump($userOrigin);
//	var_dump($userDestination);
//	var_dump($userFlyDateStart);
//	var_dump($userFlyDateRange);
//	var_dump($numberOfPassengersRequested);
//	var_dump($minLayover);


	// call method
	$thisArrayOfPaths = Flight::getRoutesByUserInput($mysqli, $userOrigin, $userDestination,
		$userFlyDateStart, $userFlyDateRange,
		$numberOfPassengersRequested, $minLayover);

//	echo "<p>47 results COUNT PATHS </p>";
//	var_dump(count($thisArrayOfPaths));

	// set up head of table of search results
	$outputTableHead = "<thead2><tr>
											<th>Depart</th>
											<th>Arrive</th>
											<th>Flight #</th>
											<th>Stops</th>
											<th>Travel Time</th>
											<th>Layover</th>
											<th>Price</th>
											<th>SELECT</th>
									</tr></thead2>\n";

	// set up variable for rows then fill in with results by looping through array of paths
	$outputTableRows = "";
	for($i = 0; $i<$numberToShow; $i++) {

//		echo "<p>65 PATH #: ". $i ." </p>";
//		var_dump($thisArrayOfPaths[$i]);


		//get index for last flight
		$indexOfLastFlightInPath = count($thisArrayOfPaths[$i]) - 3;

//		echo "<p>65 PATH indexOfLastFlightInPath: ". $i ." </p>";
//		var_dump($indexOfLastFlightInPath);

//		echo "<p>ORIGIN TIME FOR PATH before timezone: ". $i ." </p>";
//		var_dump($thisArrayOfPaths[$i][0]->getDepartureDateTime());

		// origin timezone conversions here
		if($userOrigin = "ABQ" || $userOrigin = "DEN") {
			$originTimeZoneString = "PT";
			$departureFlight1 = $thisArrayOfPaths[$i][0]->getDepartureDateTime()->setTimezone(new DateTimeZone("America/Denver"))->format("H:i");
		} else if($userOrigin = "SEA" || $userOrigin = "LAX") {
			$originTimeZoneString = "MT";
			$departureFlight1 = $thisArrayOfPaths[$i][0]->getDepartureDateTime()->setTimezone(new DateTimeZone("America/Los_Angeles"))->format("H:i");
		} else if($userOrigin = "DFW" || $userOrigin = "ORD" || $userOrigin = "MDW") {
			$originTimeZoneString = "CT";
			$departureFlight1 = $thisArrayOfPaths[$i][0]->getDepartureDateTime()->setTimezone(new DateTimeZone("America/Chicago"))->format("H:i");
		} // else origin is ET
		else {
			$originTimeZoneString = "ET";
			$departureFlight1 = $thisArrayOfPaths[$i][0]->getDepartureDateTime()->setTimezone(new DateTimeZone("America/New_York"))->format("H:i");
		}

//		echo "<p>ORIGIN TIME FOR PATH after timezone: ". $i ." </p>";
//		var_dump($thisArrayOfPaths[$i][0]->getDepartureDateTime());

//		echo "<p>Destination TIME FOR PATH before timezone: ". $i ." </p>";
//		var_dump($thisArrayOfPaths[$i][$indexOfLastFlightInPath]->getArrivalDateTime());


		// destination timezone conversions here
		if($userDestination = "SEA" || $userDestination = "LAX") {
			$destinationTimeZoneString = "PT";
			$arrivalFlightLast = $thisArrayOfPaths[$i][$indexOfLastFlightInPath]->getArrivalDateTime()->setTimezone(new DateTimeZone("America/Los_Angeles"))->format("H:i");
		} else if($userDestination = "ABQ" || $userDestination = "DEN") {
			$destinationTimeZoneString = "MT";
			$arrivalFlightLast = $thisArrayOfPaths[$i][$indexOfLastFlightInPath]->getArrivalDateTime()->setTimezone(new DateTimeZone("America/Denver"))->format("H:i");
		} else if($userDestination = "DFW" || $userDestination = "ORD" || $userDestination = "MDW") {
			$destinationTimeZoneString = "CT";
			$arrivalFlightLast = $thisArrayOfPaths[$i][$indexOfLastFlightInPath]->getArrivalDateTime()->setTimezone(new DateTimeZone("America/Chicago"))->format("H:i");
		} // else destination is ET
		else {
			$arrivalFlightLast = "ET";
			$arrivalFlightLast = $thisArrayOfPaths[$i][$indexOfLastFlightInPath]->getArrivalDateTime()->setTimezone(new DateTimeZone("America/New_York"))->format("H:i");
		}

//		echo "<p>Destination TIME FOR PATH after timezone: ". $i ." </p>";
//		var_dump($thisArrayOfPaths[$i][$indexOfLastFlightInPath]->getArrivalDateTime());


		// set up array for flight number then loop through flights
		$flightNumberArray = array();
		$j = 0;

		do {
			$flightNumberArray [$j]= $thisArrayOfPaths[$i][$j]->getFlightNumber();

//			echo "110 path " . $i . " and flight " . $j . " flight object and flight number and Array of flight numbers";
////				var_dump($thisArrayOfPaths[$i][$j]);
//			var_dump($thisArrayOfPaths[$i][$j]->getFlightNumber());
//			var_dump($flightNumberArray);
			$j++;
		} while(empty($thisArrayOfPaths[$i][$j + 2]) === false);

		$flightNumber = implode(", ", $flightNumberArray);
//		echo "120 final flightNumber string";
//		var_dump($flightNumber);


//		fixme old code delete:
//		} else {
//			$flightNumber = $thisArrayOfPaths[$i][0]->getFlightNumber();
//		}
//		echo "120 flight#Array";
//		var_dump(count($flightNumberArray));

//		if(count($flightNumberArray) === 1) {
//			$flightNumber = $flightNumberArray[0];
//		} else if(count($flightNumberArray) > 1) {
			// turn array to string
//		} else throw (new UnexpectedValueException ("Could not find a flight number"));


		// index of last flight also = number of stops to show user
		if($indexOfLastFlightInPath === 0) {
			$numberOfStops = "Nonstop";
		} else {
			$numberOfStops = $indexOfLastFlightInPath;
		}

		// get total duration from results array and change it to a string
		$totalDurationInterval = $thisArrayOfPaths[$i][$indexOfLastFlightInPath + 1];
//		echo "<p>121 PATH index then DURATION </p>";
//		var_dump($indexOfLastFlightInPath + 1);
//		var_dump($totalDurationInterval);

		$travelTime = $totalDurationInterval->format("%H:%I");

		// set up array for layover then loop through results to calc
		$layoverArray = array();
		for($k = 0; empty($thisArrayOfPaths[$i][$k + 3]) === false; $k++) {
			$layoverInterval = $thisArrayOfPaths[$i][$k]->getArrivalDateTime()->
										diff($thisArrayOfPaths[$i][$k + 1]->getDepartureDateTime());
//
//			echo "<p>161 PATH LAYOVER </p>";
//			var_dump($layoverInterval);

//			$minutes = $layoverInterval->days * 24 * 60;
//			$minutes += $layoverInterval->h * 60;
//			$minutes += $layoverInterval->i;

			$layoverArray[$k] = $layoverInterval->format("%H:%I");

//				intval($minutes);
		}

		// turn layover to string of all layovers in route
		if($indexOfLastFlightInPath === 0) {
			$layoverString = "-";
		} else {
			$layoverString = implode(", ", $layoverArray);
		}

		// get total price from results
		$totalPrice = "$" . money_format("%n",$thisArrayOfPaths[$i][$indexOfLastFlightInPath+2]);


		// build outputs into table rows
		$outputTableRows = $outputTableRows . "<tr>" .
			"<td>" . $departureFlight1 . "</td>" .
			"<td>" . $arrivalFlightLast . "</td>" .
			"<td>" . $flightNumber . "</td>" .
			"<td text-align: center>" . $numberOfStops . "</td>" .
			"<td>" . $travelTime . "</td>" .
			"<td>" . $layoverString . "</td>" .
			"<td>" . $totalPrice . "</td>" .
			"<td>
					<div class='btn-group' data-toggle='buttons'>
						<label class='btn btn-primary active'>
							<input type='radio' name='selectFlight' id='selectFlight' autocomplete='off' value='1'>
						</label>
					</div>
			</td>" .
			"</tr>\n";

	}
	$outputTable = $outputTableHead . "<tbody>" . $outputTableRows . "</tbody>\n";
	return $outputTable;
}



try {
//	session_start();
//	$savedName  = $_POST["csrfName"];
//	$savedToken = $_POST["csrfToken"];
//
//
//	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
//		throw(new RuntimeException("Make sure cookies are enabled."));
//	}

	$mysqli = MysqliConfiguration::getMysqli();
//	$flightPaths = $_SESSION['flightPathsObj'];
	$flightPaths =

	// clean inputs, adjust dates to needed format for outbound flight
	$userOrigin = filter_input(INPUT_POST,"origin", FILTER_SANITIZE_STRING);
	$userDestination = filter_input(INPUT_POST,"destination", FILTER_SANITIZE_STRING);


	$userFlyDateStartIncoming1 = filter_input(INPUT_POST,"departDate", FILTER_SANITIZE_STRING);
		$userFlyDateStartIncoming2 = $userFlyDateStartIncoming1 . " 07:00:00";
		$userFlyDateStartObj = DateTime::createFromFormat("m/d/Y H:i:s", $userFlyDateStartIncoming2, new DateTimeZone('UTC'));
		$userFlyDateStart = $userFlyDateStartObj->format("Y-m-d H:i:s");
	// echo $userFlyDateStart;

	$outputTableOutbound = completeSearch($mysqli, $userOrigin, $userDestination,
														$userFlyDateStart);

	echo "<table class='table table-striped table-hover'>\n
			<thead>SELECT DEPARTURE FLIGHT</thead>" . $outputTableOutbound . "</table>\n";

	//check to see if return trip search needed and execute if so
	if ($_POST ["roundTripOrOneWay"] === 1) {
		// clean inputs, adjust dates to needed format
		$userOrigin = filter_input(INPUT_POST, "destination", FILTER_SANITIZE_STRING);
		$userDestination = filter_input(INPUT_POST, "origin", FILTER_SANITIZE_STRING);


		$userFlyDateStartIncoming1 = filter_input(INPUT_POST, "returnDate", FILTER_SANITIZE_STRING);
			$userFlyDateStartIncoming2 = $userFlyDateStartIncoming1 . " 07:00:00";
			$userFlyDateStartObj = DateTime::createFromFormat("d-m-Y H:i:s", $userFlyDateStartIncoming2, new DateTimeZone('UTC'));
			$userFlyDateStart = $userFlyDateStartObj->format("Y-m-d) H:i:s");
		// echo $userFlyDateStart;

		$outputTableInbound = completeSearch($mysqli, $userOrigin, $userDestination,
			$userFlyDateStart);

		echo "<table class='table table-striped table-responsive table-hover'>\n
			<thead>SELECT RETURN FLIGHT</thead>" . $outputTableInbound . "</table>\n";
	}




	// DateTime Math
}catch (Exception $e){
//	$_SESSION[$savedName] = $savedToken;
	echo "<div class='alert alert-danger' role='alert'>
  ".$e->getMessage()."</div>";
}
?>