<?php
session_start();
require_once("/etc/apache2/capstone-mysql/przm.php");
require_once("../class/user.php");
require_once("../class/profile.php");
require_once("../class/traveler.php");
require_once("../class/flight.php");
require_once("../class/transaction.php");
require_once("../class/ticket.php");
require_once("../class/ticketFlight.php");

$mysqli = MysqliConfiguration::getMysqli();


$_SESSION['priceWithOutboundPath'] = "300.00,3,91";
$_SESSION['priceWithReturnPath'] = "300.00,3,864";

$testEmail       = "przmair@gmail.com";
$testSalt        = bin2hex(openssl_random_pseudo_bytes(32));
$testPassword    = "1Qazxcvbn";
$testAuthToken   = bin2hex(openssl_random_pseudo_bytes(16));
$testHash        = hash_pbkdf2("sha512", $testPassword, $testSalt, 2048, 128);
$user = new User(null, $testEmail, $testHash, $testSalt, $testAuthToken);
$user->insert($mysqli);
$userId = $user->getUserId();
//echo "userId after insert->";
 //var_dump($userId);
//var_dump($user);
$_SESSION['userId'] = $userId;

$testFirstName = "PRZM";
$testMiddleName = "";
$testLastName = "AIR";
$testDateOfBirth = DateTime::createFromFormat("Y-m-d H:i:s" ,"2010-11-12 12:11:10");
$profile = new Profile(null, $userId, $testFirstName, $testMiddleName, $testLastName, $testDateOfBirth, null);
$profile->insert($mysqli);
$profileId = $profile->__get("profileId");
echo "profile Id after insert->";
//var_dump($profileId);
//var_dump($profile);

$traveler1 = new Traveler(null, $profileId, $testFirstName, $testMiddleName, $testLastName, $testDateOfBirth);
$traveler1->insert($mysqli);
//echo "traveler1 after insert->";
//var_dump($traveler1);

$traveler2 = new Traveler(null, $profileId, "Zach", "", "Grant", $testDateOfBirth);
$traveler2->insert($mysqli);
//echo "traveler2 after insert->";
//var_dump($traveler2);

$traveler3 = new Traveler(null, $profileId, "Ryan", "" , "Sloan", $testDateOfBirth);
$traveler3->insert($mysqli);
//echo "traveler3 after insert->";
//var_dump($traveler3);

$traveler4 = new Traveler(null, $profileId, "Paul", "", "Morbitzer", $testDateOfBirth);
$traveler4->insert($mysqli);
//echo "traveler4 after insert->";
//var_dump($traveler4);

$traveler5 = new Traveler(null, $profileId, "Marc", "", "Hayes", $testDateOfBirth);
$traveler5->insert($mysqli);

$traveler6 = new Traveler(null, $profileId, "Dylan", "", "McDonald", $testDateOfBirth);
$traveler6->insert($mysqli);



$travelerArray = array($traveler1,$traveler2,$traveler3,$traveler4,$traveler5,$traveler6);



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