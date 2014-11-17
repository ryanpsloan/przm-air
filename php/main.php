<?php
require_once("/etc/apache2/capstone-mysql/przm.php");
$mysqli = MysqliConfiguration::getMysqli();

require_once("tools.php");
/*$baseDate = "2014-12-01";
$fileName = "weekDay01.csv";
readCSV($mysqli, $fileName,$baseDate,25,5);
echo"<p> weekDay seed data set to flight </p><br>";*/


$baseDate = "2014-12-06";
$fileName = "weekEnd01.csv";
readCSV($mysqli, $fileName,$baseDate,25,2);
echo"<p> weekEnd seed data set to flight </p><br>";

/*$i = rand(1,100);
require_once("user.php");
$salt = bin2hex(openssl_random_pseudo_bytes(32));
$hash = hash_pbkdf2("sha512", "newPassword", $salt, 2048, 128);
$token = bin2hex(openssl_random_pseudo_bytes(16));
$user = new User (null, "tjp".++$i."@gmail.com", $hash, $salt, $token);
echo "Before Insert: ".$user;
$user->insert($mysqli);
echo "After Insert: ".$user;
echo "user processed";
echo $user;


require_once("profile.php");
$profile = new Profile(null, $user->getUserId(), "Thomas" ,"James","Parker", $dateObj = DateTime::createFromFormat
("Y-m-d H:i:s", "2014-01-31 12:12:12"), "cus_57oz2ZjbCod7H5", $user);
echo "Before Insert: ".$profile;
$profile->insert($mysqli);
echo "After Insert: ".$profile;
echo "profile processed";
echo $profile;

require_once("traveler.php");
$traveler = new Traveler(null, $profile->__get("profileId"), "Thomas" ,"James","Parker","2014-01-31 12:12:12", $profile);
echo "Before Insert: ".$traveler;
$traveler->insert($mysqli);
echo "After Insert: ".$traveler;
echo "profile processed";
echo $traveler;*/
?>