<?php
/**
 * Created by PhpStorm.
 * User: zwg2
 * Date: 12/3/14
 * Time: 10:12 AM
 *
 * this processor takes search inputs from user, executes an outbound search and, if specified, a return trip search,
 * and displays results to the user in table form.
 */

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
 * @param 	string $returnOrNo of A or B for return trip or one-way.
 * @return 	mixed $outputTable html table of search results
 **/
function completeSearch (&$mysqli, $userOrigin, $userDestination,
								 $userFlyDateStart, $returnOrNo)
{

	// can make this a user input in future to pre-filter results to a user-given duration amount in hours.
	$userFlyDateRange = 24;

	// can make this a user input in future to pre-filter results to a user-given number of records.  If all records are needed, can use empty($thisArrayOfPaths[$i]) === false; in the for loop below instead.
//$numberToShow = 15;
//$i<$numberToShow

	$numberOfPassengersRequested = filter_input(INPUT_POST, "numberOfPassengers", FILTER_SANITIZE_NUMBER_INT);
	$minLayover = filter_input(INPUT_POST, "minLayover", FILTER_SANITIZE_NUMBER_INT);



	// call method
	$thisArrayOfPaths = Flight::getRoutesByUserInput($mysqli, $userOrigin, $userDestination,
		$userFlyDateStart, $userFlyDateRange,
		$numberOfPassengersRequested, $minLayover);

	// set up head of table of search results
	$outputTableHead = "<thead><tr>
											<th>AVAIL TIX</th>
											<th>Depart</th>
											<th>Arrive</th>
											<th>Flight #</th>
											<th>Stops</th>
											<th>Travel Time</th>
											<th>Layover</th>
											<th>Price</th>
											<th>SELECT</th>
									</tr></thead>\n";

	// set up variable for rows then fill in with results by looping through each path in the array of paths
	$outputTableRows = "";
	for($i = 0; empty($thisArrayOfPaths[$i]) === false; $i++) {

		//get index for last flight
		$indexOfLastFlightInPath = count($thisArrayOfPaths[$i]) - 3;

		// origin timezone conversions here
		if($userOrigin === "ABQ" || $userOrigin === "DEN") {
			$originTimeZoneString = "PT";
			$departureFlight1 = $thisArrayOfPaths[$i][0]->getDepartureDateTime()->setTimezone(new
																			DateTimeZone("America/Denver"))->format("H:i");
		} else if($userOrigin === "SEA" || $userOrigin === "LAX") {
			$originTimeZoneString = "MT";
			$departureFlight1 = $thisArrayOfPaths[$i][0]->getDepartureDateTime()->setTimezone(new
																			DateTimeZone("America/Los_Angeles"))->format("H:i");
		} else if($userOrigin === "DFW" || $userOrigin === "ORD" || $userOrigin === "MDW") {
			$originTimeZoneString = "CT";
			$departureFlight1 = $thisArrayOfPaths[$i][0]->getDepartureDateTime()->setTimezone(new DateTimeZone("America/Chicago"))->format("H:i");
		} // else origin is ET
		else {
			$originTimeZoneString = "ET";
			$departureFlight1 = $thisArrayOfPaths[$i][0]->getDepartureDateTime()->setTimezone(new DateTimeZone("America/New_York"))->format("H:i");
		}


		// destination timezone conversions here
		if($userDestination === "SEA" || $userDestination === "LAX") {
			$destinationTimeZoneString = "PT";
			$arrivalFlightLast = $thisArrayOfPaths[$i][$indexOfLastFlightInPath]->getArrivalDateTime()->setTimezone(new DateTimeZone("America/Los_Angeles"))->format("H:i");
		} else if($userDestination === "ABQ" || $userDestination === "DEN") {
			$destinationTimeZoneString = "MT";
			$arrivalFlightLast = $thisArrayOfPaths[$i][$indexOfLastFlightInPath]->getArrivalDateTime()->setTimezone(new DateTimeZone("America/Denver"))->format("H:i");
		} else if($userDestination === "DFW" || $userDestination === "ORD" || $userDestination === "MDW") {
			$destinationTimeZoneString = "CT";
			$arrivalFlightLast = $thisArrayOfPaths[$i][$indexOfLastFlightInPath]->getArrivalDateTime()->setTimezone(new DateTimeZone("America/Chicago"))->format("H:i");
		} // else destination is ET
		else {
			$arrivalFlightLast = "ET";
			$arrivalFlightLast = $thisArrayOfPaths[$i][$indexOfLastFlightInPath]->getArrivalDateTime()->setTimezone(new DateTimeZone("America/New_York"))->format("H:i");
		}

//		echo "<p>Destination TIME FOR PATH after timezone: ". $i ." </p>";
//		var_dump($thisArrayOfPaths[$i][$indexOfLastFlightInPath]->getArrivalDateTime());

		// get total price from results
		$totalPriceFloat = $thisArrayOfPaths[$i][$indexOfLastFlightInPath+2];
		$totalPrice = "$" . money_format("%n",$totalPriceFloat);

		// set up arrays for flight number and flightIDs then loop through results to build
		$flightNumberArray = array();
		$flightIdArray = array();

		// but first add price to beginning of flightID array for use later in the process of purchasing a ticket
		$flightIdArray[0] = $totalPriceFloat;

		// and second set up counter
		$j = 0;

		// and third set up placeholder for total tickets on each plane
		$totalTicketsLeft = 10000;

		do {
			$flightNumberArray [$j]= $thisArrayOfPaths[$i][$j]->getFlightNumber();
			$flightIdArray [$j+1]= $thisArrayOfPaths[$i][$j]->getFlightId();

			// use loop to also capture the lowest TotalSeatsOnPlane of all flights in the Path
			if($totalTicketsLeft > $thisArrayOfPaths[$i][$j]->getTotalSeatsOnPlane()) {
				$totalTicketsLeft = $thisArrayOfPaths[$i][$j]->getTotalSeatsOnPlane();
			}
			$j++;
		} while(empty($thisArrayOfPaths[$i][$j + 2]) === false);

		// turn arrays to string with commas
		$flightNumber = implode(", ", $flightNumberArray);
		$priceWithFlightIds = implode(", ", $flightIdArray);

//		echo "120 final flightNumber string";
//		var_dump($flightNumber);


//		todo old code delete:
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




		// build outputs into table rows.  Give each select a different value depending on a) outbound or inbound and b) within either, number for path in loop.
		$outputTableRows = $outputTableRows . "<tr>" .
			"<td>" . $totalTicketsLeft . "</td>" .
			"<td>" . $departureFlight1 . "</td>" .
			"<td>" . $arrivalFlightLast . "</td>" .
			"<td>" . $flightNumber . "</td>" .
			"<td text-align: center>" . $numberOfStops . "</td>" .
			"<td>" . $travelTime . "</td>" .
			"<td>" . $layoverString . "</td>" .
			"<td>" . $totalPrice . "</td>" .
			"<td>
					<div class='btn-group'>
						<label class='btn btn-primary active'>
							<input type='radio' name='" . $returnOrNo . "' id='selectFlight" . $returnOrNo . $i . "' autocomplete='off' value='" . $priceWithFlightIds . "'>
						</label>
					</div>
			</td>" .
			"</tr>\n";
	}
	$outputTable = $outputTableHead . "<tbody>" . $outputTableRows . "</tbody>\n";
	return $outputTable;
}



try {
	session_start();
	$mysqli = MysqliConfiguration::getMysqli();
//	$savedName  = $_POST["csrfName"//];
//	$savedToken = $_POST["csrfToken"];//
//
//
//	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false)// {
//		throw(new RuntimeException("Make sure cookies are enabled.")//);
//	}


	// clean inputs, adjust dates to needed format for outbound flight
	$userOrigin1 = filter_input(INPUT_POST,"origin", FILTER_SANITIZE_STRING);
	$userDestination1 = filter_input(INPUT_POST,"destination", FILTER_SANITIZE_STRING);


	$userFlyDateStartIncoming1 = filter_input(INPUT_POST,"departDate", FILTER_SANITIZE_STRING);
		$userFlyDateStartIncoming2 = $userFlyDateStartIncoming1 . " 07:00:00";
		$userFlyDateStartObj1 = DateTime::createFromFormat("m/d/Y H:i:s", $userFlyDateStartIncoming2, new DateTimeZone('UTC'));
		$userFlyDateStart1 = $userFlyDateStartObj1->format("Y-m-d H:i:s");

	// get outbound results
	$outputTableOutbound = completeSearch($mysqli, $userOrigin1, $userDestination1,
														$userFlyDateStart1, "priceWithOutboundPath");

	// set up modular string pieces for building output echo
	$tableStringStart = "<form class='navbar-form navbar-left' id='searchResults' action='search_results_processor.php' method='POST'>
									<table class='table table-striped table-responsive table-hover'>\n
										<thead>";
	$tableStringMid = "</table><table class='table table-striped table-responsive table-hover'>\n
								<thead>";
	$tableStringEnd = "</table>\n<button type='submit' class='btn btn-default'>Submit</button></form>";

	// if not return trip, build and echo output string with outbound only
	if ($_POST ["roundTripOrOneWay"] === 0) {
		echo $tableStringStart . "SELECT DEPARTURE FLIGHT</thead>" . $outputTableOutbound . $tableStringEnd;;
	} else {
		// otherwise, execute return search flight with same process: clean inputs, adjust dates to needed format for return trip
		$userOrigin2 = filter_input(INPUT_POST, "destination", FILTER_SANITIZE_STRING);
		$userDestination2 = filter_input(INPUT_POST, "origin", FILTER_SANITIZE_STRING);

		$userFlyDateStartIncoming3 = filter_input(INPUT_POST, "returnDate", FILTER_SANITIZE_STRING);
			$userFlyDateStartIncoming4 = $userFlyDateStartIncoming3 . " 07:00:00";
			$userFlyDateStartObj2 = DateTime::createFromFormat("m/d/Y H:i:s", $userFlyDateStartIncoming4, new DateTimeZone('UTC'));
			$userFlyDateStart2 = $userFlyDateStartObj2->format("Y-m-d H:i:s");

		// execute inbound flight search
		$outputTableInbound = completeSearch($mysqli, $userOrigin2, $userDestination2,
															$userFlyDateStart2, "priceWithReturnPath");

		// build and echo output string with return flight
		echo 	$tableStringStart . "SELECT DEPARTURE FLIGHT</thead>" . $outputTableOutbound . $tableStringMid .
				"SELECT RETURN FLIGHT</thead>" . $outputTableInbound . $tableStringEnd;
	}

}catch (Exception $e){
	// $_SESSION[$savedName] = $savedToken;
	echo "<div class='alert alert-danger' role='alert'>
".$e->getMessage()."</div>";
}
?>