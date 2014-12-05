<?php
require_once("/etc/apache2/capstone-mysql/przm.php");
include("../php/user.php");
include("../php/profile.php");
include('../lib/csrf.php');
try {
	session_start();
	$mysqli = MysqliConfiguration::getMysqli();
	$savedName = $_POST["csrfName"];
	$savedToken =$_POST["csrfToken"];

	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("Make sure cookies are enabled"));
	}

	//filter inputs
	$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
	$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

	if(isset($_SESSION['userId'])) {

		echo "<script>
					$(document).ready(function() {
						$(':input').attr('disabled', true);
						$('#signUpLink').hide();
						$('#forgotPass').hide();
					});
			   </script>";
				throw(new ErrorException("You are already signed in"));
	} else {
		//grab user by email
		$user = User::getUserByEmail($mysqli, $email);
		if($user === null){
			throw(new ErrorException("User not found, please sign up or check email and password"));
		}

		if($user->getAuthenticationToken() === null){
			throw(new ErrorException("This account is not authenticated, check your email"));
		}
		$userPass = hash_pbkdf2("sha512", $password, $user->getSalt(), 2048, 128);
		if(!($userPass === $user->getPassword())) {
			throw(new UnexpectedValueException("Email or Password is not correct"));
		}
		else {

			$_SESSION['userId'] = $user->getUserId();
			$_SESSION['profileObj'] = Profile::getProfileByUserId($mysqli, $user->getUserId());
			echo "<div class='alert alert-success' role='alert'>Successful Sign In</div>
						      <script>
									$(document).ready(function() {
										$(':input').attr('disabled', true);
									 	$('#signUpLink').hide();
										$('#forgotPass').hide();
									});
							  </script>";
		}
	}

}catch(Exception $e){
	$_SESSION[$savedName] = $savedToken;
	echo "<div class='alert alert-danger' role='alert'>".$e->getMessage()."</div>";
}
?>





















