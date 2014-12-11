<?php
session_start();
require_once("/etc/apache2/capstone-mysql/przm.php");
require_once("user.php");
require_once("profile.php");
require_once("traveler.php");
require_once("flight.php");
require_once("transaction.php");
require_once("ticket.php");
require_once("ticketFlight.php");

$mysqli = MysqliConfiguration::getMysqli();

$paths = array();
$paths[] = Flight::getFlightByFlightId($mysqli, 91 );
$paths[] = Flight::getFlightByFlightId($mysqli, 864 );

$_SESSION['flightObjArray'] = $paths;
$testEmail       = "przmair@gmail.com";
$testSalt        = bin2hex(openssl_random_pseudo_bytes(32));
$testPassword    = "1Qazxcvbn";
$testAuthToken   = bin2hex(openssl_random_pseudo_bytes(16));
$testHash        = hash_pbkdf2("sha512", $testPassword, $testSalt, 2048, 128);
$user = new User(null,$testEmail, $testHash, $testSalt, $testAuthToken);
$user->insert($mysqli);
$userId = $user->getUserId();
$_SESSION['userId'] = $userId;

$testFirstName = "PRZM";
$testMiddleName = "";
$testLastName = "AIR";
$testDateOfBirth = DateTime::createFromFormat("Y-m-d H:i:s" ,"2010-11-12 12:11:10");
$profile = new Profile(null, $userId, $testFirstName, $testMiddleName, $testLastName, $testDateOfBirth, null);
$profileId = $profile->__get("profileId");
$profile->insert($mysqli);

$traveler = new Traveler(null, $profileId, $testFirstName, $testMiddleName, $testLastName, $testDateOfBirth);
$traveler->insert($mysqli);


header("Location: ../../forms/selectTravelers.php");
//require_once("tools.php");
/*$baseDate = "2014-12-01";
$fileName = "weekDay01.csv";
readCSV($mysqli, $fileName,$baseDate,25,5);
echo"<p> weekDay seed data set to flight </p><br>";*/


//$baseDate = "2014-12-06";
//$fileName = "weekEnd01.csv";
//readCSV($mysqli, $fileName,$baseDate,25,2);
//echo"<p> weekEnd seed data set to flight </p><br>";

/*$i = rand(1,100);
require_once("user.php");
$salt = bin2hex(openssl_random_pseudo_bytes(32));
$hash = hash_pbkdf2("sha512", "newPassword", $salt, 2048, 128);
$token = bin2hex(openssl_random_pseudo_bytes(16));
$user = new User (null, "rps@gmail.com", $hash, $salt, $token);
echo "Before Insert: ".$user;
$user->insert($mysqli);
echo "After Insert: ".$user;
echo "user processed";
var_dump($user);


require_once("profile.php");
$profile = new Profile(null, $user->getUserId(), "Ryan" ,"Pace","Sloan", $dateObj = DateTime::createFromFormat
("Y-m-d H:i:s", "1979-08-10 12:12:12"), "cus_57oz2ZjbCod7H5", $user);
echo "Before Insert: ".$profile;
$profile->insert($mysqli);
echo "After Insert: ".$profile;
echo "profile processed";
echo $profile;

/*require_once("traveler.php");
$traveler = new Traveler(null, $profile->__get("profileId"), "Thomas" ,"James","Parker","2014-01-31 12:12:12", $profile);
echo "Before Insert: ".$traveler;
$traveler->insert($mysqli);
echo "After Insert: ".$traveler;
echo "profile processed";
echo $traveler;*/
?>