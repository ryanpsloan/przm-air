<?php
/*require_once("user.php");

	$mysqli = new mysqli("localhost", "przm", "trillpontlureactscala", "przm");
	$salt = bin2hex(openssl_random_pseudo_bytes(32));
	$hash = hash_pbkdf2("sha512", "newPassword", $salt, 2048, 128);
   $token = bin2hex(openssl_random_pseudo_bytes(16));
	echo "USER";
	$user = new User (null, "thomasjamesparker13@gmail.com", $hash, $salt, $token);
	echo "Before Insert: ".$user;
	$user->insert($mysqli);
	echo "After Insert: ".$user;
	echo "user processed";
	echo $user;
	echo "--------------------------------------------------------------------------";

require_once("profile.php");
echo "PROFILE";
$profile = new Profile(null, $user->getUserId(), "Thomas" ,"James","Parker","2014-01-31 12:12:12", "cus_57oz2ZjbCod7H5", $user);
echo "Before Insert: ".$profile;
$profile->insert($mysqli);
echo "After Insert: ".$profile;
echo "profile processed";
echo $profile;
echo "------------------------------------------------------------------------------";
require_once("traveler.php");
echo "TRAVELER";
$traveler = new Traveler(null, $profile->__get("profileId"), "Gulliver", "L", "Travels", "2010-10-10 10:10:10",
	$profile );
echo "Before Insert: ".$traveler;
$traveler->insert($mysqli);
echo "After Insert: ".$traveler;
echo "traveler->profileObj = ".$traveler->__get("profileObj");
echo "traveler processed";
echo $traveler;
echo "---------------------------------------------------------------------------------";*/

/*require_once('../lib/stripe-php-1.17.3/lib/Stripe.php');
Stripe::setApiKey("sk_test_BQokikJOvBiI2HlWgH4olfQ2");

$customerObj = Stripe_Customer::create(array(
	"description" => "customerEmail@hotzzz.com"
));
echo $customerObj->id;*/
?>