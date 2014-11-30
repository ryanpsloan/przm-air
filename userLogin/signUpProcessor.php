<?php
require("/etc/apache2/capstone-mysql/przm.php");
require("../php/user.php");
require("../php/profile.php");
include("../lib/csrf.php");
$mysqli = MysqliConfiguration::getMysqli();
require('../lib/stripe-php-1.17.3/lib/Stripe.php');
Stripe::setApiKey("sk_test_BQokikJOvBiI2HlWgH4olfQ2");

// verify the form was submitted OK
try{
session_start();
if(@isset($_POST["first"]) === false || @isset($_POST["middle"]) === false
		|| @isset($_POST["last"]) === false || @isset($_POST["dob"]) === false
		|| @isset($_POST["email"]) === false || @isset($_POST["password"]) === false
		|| @isset($_POST["confPassword"]) === false)
{
	throw(new RuntimeException("Form variables incomplete or missing. Please fill out the form"));
}

// verify the CSRF tokens
if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
	throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
}

if($firstNm = filter_input(INPUT_POST,"first",FILTER_SANITIZE_STRING) === false){
	throw(new RuntimeException("Enter your first name"));
}
$firstNm = strtolower($firstNm);
if($middleNm = filter_input(INPUT_POST,"middle",FILTER_SANITIZE_STRING) === false){
	throw(new RuntimeException("Enter your middle name"));
}
$middleNm = strtolower($middleNm);
if($lastNm = filter_input(INPUT_POST,"last",FILTER_SANITIZE_STRING) === false){
	throw(new RuntimeException("Enter your last name"));
}
$lastNm = strtolower($lastNm);
if($DOB = filter_input(INPUT_POST,"dob",FILTER_SANITIZE_STRING) === false){
	throw(new RuntimeException("Enter your DOB yyyy-mm-dd"));
}
if((preg_match("/^(\d{4})-(\d{2})-(\d{2})$/",$DOB, $matches)) !== 1) {
	throw(new RangeException("Date of Birth is not a valid date"));
}

$year  = intval($matches[1]);
$month = intval($matches[2]);
$day   = intval($matches[3]);
if(checkdate($month, $day, $year) === false) {
	throw(new RangeException("Date entered is not a Gregorian date"));
}

$DOB = DateTime::createFromFormat("Y-m-d H:i:s", $DOB);
$fullName = $firstNm." ".$middleNm." ".$lastNm;
$customer = Stripe_Customer::create(array('description' => $fullName." | ".$email));
$custToken = $customer->id;

if($email = filter_input(INPUT_POST,"email",FILTER_SANITIZE_STRING) === false){
	throw(new RuntimeException("Invalid email"));
}
try {
	$query = "SELECT userId FROM user WHERE email = ?";
	$statement = $mysqli->prepare($query);
	$statement->bind_param("s", $email);
	$statement->execute();
	$result = $statement->get_result();
	if($result !== null){
		throw(new Exception("Email has already been registered"));
	}
} catch (mysqli_sql_exception $exception){
	$exception->getMessage();
}

if($password = filter_input(INPUT_POST,"password",FILTER_SANITIZE_STRING) === false){
	throw(new RuntimeException("Invalid password"));
}
if($confPassword = filter_input(INPUT_POST,"confPassword",FILTER_SANITIZE_STRING) === false){
	throw(new RuntimeException("Invalid password"));
}

if($password === $confPassword){
	$salt             = bin2hex(openssl_random_pseudo_bytes(32));
	$authToken        = bin2hex(openssl_random_pseudo_bytes(16));
	$hash 		      = hash_pbkdf2("sha512", $confPassword, $salt, 2048, 128);
}
else{
	throw(new RuntimeException("Passwords entered do not match"));
}
$newUser = new User(null, $email, $hash, $salt, $authToken);
$newUser->insert($mysqli);
$_SESSION['userObj'] = $newUser;
	echo "<p>User Created -> signUpProcessor __LINE__</p>";
	var_dump($newUser);
$newProfile = new Profile(null, $newUser->getUserId(), $firstNm, $middleNm, $lastNm, $DOB, $custToken, $newUser);
$newProfile->insert($mysqli);
$_SESSION['profileObj'] = $newProfile;
	echo "<p>Profile Created -> signUpProcessor __LINE__</p>";
}catch(RuntimeException $exception){
	$exception->getMessage();
}
?>