 <?php
require_once("/etc/apache2/capstone-mysql/przm.php");
include("../php/user.php");
include("../php/profile.php");
include('../lib/csrf.php');
try {
	session_start();
	$mysqli = MysqliConfiguration::getMysqli();

	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		echo "<div class='alert alert-danger' role='alert'><a href='#' class='alert-link'>
		CSRF tokens incorrect or missing. Make sure cookies are enabled</a></div>";
	}
	else {
		//filter inputs
		$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
		$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

		if(isset($_SESSION['userId'])) {

			echo "<div class='alert alert-danger' role='alert'><a href='#' class='alert-link'>You are already signed in</a>
				</div>
					<p><a href='../index.php'>Home</a></p>
					 <script>
									$(document).ready(function() {
										$(':input').attr('disabled', true);
										$('#signUpLink').hide();
										$('#forgotPass').hide();
									});
							  </script>";
		}
		else {
			//grab user by email
			$user = User::getUserByEmail($mysqli, $email);
			if($user === null) {
				echo "<div class='alert alert-warning' role='alert'><a href='#' class='alert-link'>
			We couldn't access your account. Check that email and password are correct and that you are signed up</a></div>";
			} else {
				$userPass = hash_pbkdf2("sha512", $password, $user->getSalt(), 2048, 128);

				if(!($userPass === $user->getPassword())) {
					echo "<div class='alert alert-warning' role='alert'><a href='#' class='alert-link'>
							Passwords do not match</a></div>";
				} else {

					$_SESSION['userId'] = $user->getUserId();
					$profile = Profile::getProfileByUserId($mysqli, $user->getUserId());

					$profile->setUserObject($user);
					$_SESSION["profileObj"] = $profile;
					$output = "<p>Successful Sign In</p>
						     <p><a href='../index.php'>Home</a></p>
						     <script>
									$(document).ready(function() {
										$(':input').attr('disabled', true);
										$('#signUpLink').hide();
										$('#forgotPass').hide();
									});
							  </script>";

					echo $output;
				}
			}
		}
	}
}catch(Exception $e){
	echo "<div class='alert alert-danger' role='alert'><a href='#' class='alert-link'>".$e->getMessage()."</a></div>";
}
?>





















