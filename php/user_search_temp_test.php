<?php
/**
 * Created by PhpStorm.
 * User: zwg2
 * Date: 12/1/14
 * Time: 11:06 AM
 */


// require the class under scrutiny
require_once("/etc/apache2/capstone-mysql/przm.php");
require_once("results.php");
require_once("flight.php");

$mysqli = MysqliConfiguration::getMysqli();
$testSearch = Flight::getRoutesByUserInput($mysqli,'ABQ','DFW','2014-12-02 00:00:00','2014-12-03 00:00:00',1,15);

echo "<p>line 12 of user search temp test var dump of results from calling function</p>";
var_dump($testSearch);