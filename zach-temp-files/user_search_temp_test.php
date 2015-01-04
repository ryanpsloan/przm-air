<?php
/**
 * Created by PhpStorm.
 * User: zwg2
 * Date: 12/1/14
 * Time: 11:06 AM
 */


// require the class under scrutiny
//require_once("/home/gaster15/przm.php");
//require_once("results.php");
//require_once("flight.php");
//
//$mysqli = MysqliConfiguration::getMysqli();
//$testSearch = array();
//$testSearch = Flight::getRoutesByUserInput($mysqli,'ABQ','DFW','2014-12-09 00:00:00','2014-12-10 00:00:00',1,15);
//
//echo "<p>line 12 of user search temp test var dump of results from calling function</p>";
//var_dump($testSearch);



//$testDate1 = DateTime::createFromFormat("Y-m-d H:i:s", "2014-12-09 07:15:00");
//var_dump($testDate1);
//
//$testDate2 = DateTime::createFromFormat("Y-m-d H:i:s", "2014-12-09 09:15:00");
//var_dump($testDate2);
//
//$difference = $testDate1->diff($testDate2);
//var_dump($difference);
//
//
//
//
//$testDate1->setTimezone(new DateTimeZone('Pacific/Nauru'));
//var_dump($testDate1);
//
//$difference = $testDate1->diff($testDate2);
//var_dump($difference);
//
//
//
//
//$testDate2->setTimezone(new DateTimeZone('Pacific/Nauru'));
//var_dump($testDate2);
//
//$difference = $testDate1->diff($testDate2);
//var_dump($difference);
//
//
//
//
//
//$departureFlight1 = $testDate1->setTimezone (new DateTimeZone("Pacific/Chatham"))->format("H:i");
//var_dump($departureFlight1);
//var_dump($testDate1);
//
//$arrivalFlight1 = $testDate2->setTimezone (new DateTimeZone("Pacific/Chatham"))->format("H:i");
//var_dump($arrivalFlight1);
//var_dump($testDate2);
//
//$travelTime = $difference->format("%H:%I");
//var_dump($travelTime);
//
//
//
//$difference = $testDate1->diff($testDate2);
//var_dump($difference);



$testArray = array(12, 14, 16);

$testString = implode(", ", $testArray);

echo $testString;
var_dump($testArray);

