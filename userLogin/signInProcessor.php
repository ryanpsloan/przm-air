<?php
require_once("/etc/apache2/capstone-mysql/przm.php");
$mysqli = MysqliConfiguration::getMysqli();
include("../lib/csrf.php");
include("../php/user.php");
include("../php/profile.php");
try {

	session_start();
	echo "<p>email: ".$_POST['email']. " password: ".$_POST['password']."</p>";

	// verify the CSRF tokens
	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		echo"<p>CSRF tokens incorrect or missing. Make sure cookies are enabled</p>";
	}
	//filter inputs
	$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
	$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

	//grab user by email
	if(User::getUserByEmail($mysqli, $email) === null || User::getUserByEmail($mysqli, $email) === false) {
		echo "<p>User not found. Check that email and password are correct</p>";
	}
	else {
		$user = User::getUserByEmail($mysqli, $email);
		echo "<p>User Found</p>";
		var_dump($user);

		$userPass = hash_pbkdf2("sha512", $password, $user->getSalt(), 2048, 128);
		var_dump($userPass);
		if(!($userPass === $user->getPassword())) {
			echo "<p>Passwords do not match</p>";
		}
		else {
			$_SESSION["userObj"] = $user;
			$_SESSION["authToken"] = $user->getAuthenticationToken();
			$profile = Profile::getProfileByUserId($mysqli,$user->getUserId());
			var_dump($profile);
			$_SESSION["profileObj"] = $profile;
			echo "Signed in OK";
			//go back to index.html
		}
	}
}catch(Exception $exception)
{
	$exception->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>Sign In</title>
</head>
<body>
<form id="signInForm" action="signInProcessor.php" method="POST">
<?php echo generateInputTags();?>
<label for="email">Email:</label>
<input type="email" id="email" name="email" value="<?php echo $email?>"><br>
<label for="password">Password:</label>
<input type="password" id="password" name="password"><br>
<button type="submit">Sign In</button>
</form>

</body>
</html>





















