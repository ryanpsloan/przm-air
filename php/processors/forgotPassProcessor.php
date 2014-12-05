<?php
include('../php/user.php');
include('../lib/csrf.php');
require_once("/etc/apache2/capstone-mysql/przm.php");
try {
	session_start();
	$savedName = $_POST["csrfName"];
	$savedToken =$_POST["csrfToken"];
	$mysqli = MysqliConfiguration::getMysqli();

	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
	}

	$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
	$user = User::getUserByEmail($mysqli, $email);
	if($user === null) {
		throw(new UnexpectedValueException("That email is not registered"));
	}

	$newPass = trim(filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING));
	$newConf = trim(filter_input(INPUT_POST, "confPassword", FILTER_SANITIZE_STRING));

	if($newPass === $newConf) {
		$hash = hash_pbkdf2("sha512", $newConf, $user->getSalt(), 2048, 128);
		if($hash !== $user->getPassword()) {
			$user->setPassword($hash);
			$user->setSalt($user->getSalt());
			$user->update($mysqli);
			echo "<div class='alert alert-success' role='alert'>Password Updated</div>
					 <script>
			      	 $(document).ready(function() {
					   	    $(':input').attr('disabled', true);
						 });
				 	</script>";
		} else {
			throw(new ErrorException("Enter a new password"));
		}
	} else {
		throw(new ErrorException("Passwords entered do not match"));
	}
}catch(Exception $e){
	$_SESSION[$savedName] = $savedToken;
	echo "<div class='alert alert-danger' role='alert'>".$e->getMessage()."</div>";
}
?>
