<?php
require_once("/etc/apache2/capstone-mysql/przm.php");
$mysqli = MysqliConfiguration::getMysqli();
include("../php/user.php");
include("../php/profile.php");

	// email and password as input
	session_start();

	//filter inputs
	$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
	$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

	//grab user by email
	if(User::getUserByEmail($mysqli, $email) === null || User::getUserByEmail($mysqli, $email) === false) {
		echo "<p>We couldn't access your account. Check that email and password are correct</p>";
	}
	else {
		$user = User::getUserByEmail($mysqli, $email);
		if(isset($_SESSION['userId'])) {
			echo "<p>You are already signed in</p>
					<p><a href='../index.php'>Home</a></p>
					 <script>
									$(document).ready(function() {
										/*$(':input').attr('disabled', true);
										$('#signUpLink').removeAttr('href');
										$('#forgotPass').removeAttr('href');*/
										$('#signInForm').hide();
										$('#signUpLink').hide();
										$('#forgotPass').hide();
									});
							  </script>";

		}
		else {
			$userPass = hash_pbkdf2("sha512", $password, $user->getSalt(), 2048, 128);

			if(!($userPass === $user->getPassword())) {
				echo "<p>Passwords do not match</p>";


			}
			else {

				$_SESSION['userId'] = $user->getUserId();
				$_SESSION["authToken"] = $user->getAuthenticationToken();
				$profile = Profile::getProfileByUserId($mysqli, $user->getUserId());

				$profile->setUserObject($user);
				$_SESSION["profileObj"] = $profile;
				$output = "<p>Successful Sign In</p>
						     <p><a href='../index.php'>Home</a></p>
						     <script>
									$(document).ready(function() {
										/*$(':input').attr('disabled', true);
										$('#signUpLink').removeAttr('href');
										$('#forgotPass').removeAttr('href');*/
										$('#signInForm').hide();
										$('#signUpLink').hide();
										$('#forgotPass').hide();
									});
							  </script>";

				echo $output;
			}
		}
	}

?>





















