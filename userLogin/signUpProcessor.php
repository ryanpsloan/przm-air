<?php
require("/etc/apache2/capstone-mysql/przm.php");
require("../php/user.php");
require("../php/profile.php");
include("../lib/csrf.php");
$mysqli = MysqliConfiguration::getMysqli();


// verify the form was submitted OK
try {
	session_start();
	if(@isset($_POST["first"]) === false || @isset($_POST["middle"]) === false
			|| @isset($_POST["last"]) === false || @isset($_POST["dob"]) === false
					|| @isset($_POST["email"]) === false || @isset($_POST["password"]) === false
							|| @isset($_POST["confPassword"]) === false
	) {
		echo "<p>Form variables incomplete or missing. Please fill out the form</p>";
	}

// verify the CSRF tokens
	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		echo "<p>CSRF tokens incorrect or missing. Make sure cookies are enabled</p>";
	}
//filter and process input
	$firstNm = filter_input(INPUT_POST, "first", FILTER_SANITIZE_STRING);
	if($middleNm = filter_input(INPUT_POST, "middle", FILTER_SANITIZE_STRING) === ""){
		$middleNm = " ";
	}
	$lastNm = filter_input(INPUT_POST, "last", FILTER_SANITIZE_STRING);
	$DOB = filter_input(INPUT_POST, "dob", FILTER_SANITIZE_STRING);
	$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
	$confPassword = filter_input(INPUT_POST, "confPassword", FILTER_SANITIZE_STRING);
	if($password !== $confPassword) {
		echo "<p>Passwords do not match</p>";
	}
	$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING);
	if(User::getUserByEmail($mysqli, $email) !== null || User::getUserByEmail($mysqli, $email) !== null) {
		echo <<<EOF
		<p>That email is already in use. Sign-in or use a different email</p>
		<form action="signIn.php" method="POST">
		<button type="submit">Sign In</button>
		</form>
EOF;

	} else {

		$firstNm = strtolower($firstNm);
		$middleNm = strtolower($middleNm);
		$lastNm = strtolower($lastNm);

		preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $DOB, $matches);
		$year = intval($matches[1]);
		$month = intval($matches[2]);
		$day = intval($matches[3]);
		if(checkdate($month, $day, $year) === false) {
			echo "<p>Date entered is not a Gregorian date</p>";
		}

		$fullName = $firstNm . " " . $middleNm . " " . $lastNm;

		$customer = Stripe_Customer::create(array('description' => $fullName . " | " . $email));
		$custToken = $customer->id;

		$salt = bin2hex(openssl_random_pseudo_bytes(32));
		$authToken = bin2hex(openssl_random_pseudo_bytes(16));
		$hash = hash_pbkdf2("sha512", $confPassword, $salt, 2048, 128);

		$newUser = new User(null, $email, $hash, $salt, $authToken);
		$newUser->insert($mysqli);
		$_SESSION['userObj'] = $newUser;
		echo "<p>User Created</p>";
		var_dump($newUser);

		try{
			var_dump($newUser->getUserId());
			var_dump($firstNm);
			var_dump($middleNm);
			var_dump($lastNm);
			var_dump($DOB);
			var_dump($custToken);
		$dateTimeObj = DateTime::createFromFormat("Y-m-d", $DOB);
		$newProfile = new Profile(null, $newUser->getUserId(), $firstNm, $middleNm, $lastNm,
			$dateTimeObj->format("Y-m-d H:i:s"), $custToken, $newUser);
		var_dump($newProfile);
		$newProfile->insert($mysqli);
		$_SESSION['profileObj'] = $newProfile;
		echo "<p>Profile Created</p>";
		var_dump($newProfile);
		echo "<p>User and Profile Created and Inserted</p>";
		//back to index.html

		}catch(Exception $e){
			$e->getMessage();
		}
	}
}catch(RuntimeException $exception){
	$exception->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>Sign Up</title>
</head>
<body>
<form action="signUpProcessor.php" method="POST">
	<?php echo generateInputTags();?>
	<fieldset>
		<legend>Create a Profile</legend>
		<p><label>First Name</label>
			<input type="text" id="first" name="first" required="true" value="<?php echo $firstNm ?>"></p>
		<p><label>Middle Name</label>
			<input type="text" id="middle" name="middle" value="<?php echo $middleNm ?>"><br>
		<p><label>Last Name</label>
			<input type="text" id="last" name="last" required="true" value="<?php echo $lastNm ?>"></p>
		<p><label>Date Of Birth</label>
			<input type="date" id="dob" name="dob" required="true" value="<?php echo $DOB ?>"
					 pattern="/^(\d{2})/(\d{2})/(\d{4})$/"></p>
		<p><label>Email</label>
			<input type="email" id="email" name="email" required="true"></p>
		<p><label>Password</label>
			<input type="password" id="password" name="password" required="true"></p>
		<p><label>Confirm Password</label>
			<input type="password" id="confPassword" name="confPassword" required="true"></p>
		<button type="submit">Register</button>
	</fieldset>
</form>
</body>
</html>