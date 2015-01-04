<?php
include('../class/user.php');
include('../../lib/csrf.php');
require_once("/home/gaster15/przm.php");
try {
	session_start();
	$mysqli = MysqliConfiguration::getMysqli();

	$savedName  = $_POST["csrfName"];
	$savedToken = $_POST["csrfToken"];

	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("Make sure cookies are enabled."));
	}

	$user = User::getUserByUserId($mysqli, $_SESSION['userId']);
	if($user === null) {
		throw(new UnexpectedValueException("That email is not registered"));
	}
	$oldPass = trim(filter_input(INPUT_POST, "oldPassword", FILTER_SANITIZE_STRING));
	$newPass = trim(filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING));
	$newConf = trim(filter_input(INPUT_POST, "confPassword", FILTER_SANITIZE_STRING));

	$oldPass = hash_pbkdf2("sha512", $oldPass, $user->getSalt(), 2048, 128);

	if($oldPass !== $user->getPassword()){
		throw(new UnexpectedValueException("Your old password is incorrect"));
	}

	$newPass = hash_pbkdf2("sha512", $newPass, $user->getSalt(), 2048, 128);

	if($newPass === $oldPass) {
		throw(new UnexpectedValueException("That is not a new password"));
	}
	$newConf = hash_pbkdf2("sha512", $newConf, $user->getSalt(), 2048, 128);

	if($newPass === $newConf){

		$user->setPassword($newPass);
		$user->setSalt($user->getSalt());
		$user->update($mysqli);
		echo "<div class='alert alert-success' role='alert'>Password Updated</div>
					 <script>
			      	 $(document).ready(function() {
					   	    $(':input').attr('disabled', true);
						 });
				 	</script>";

	} else {
		throw(new UnexpectedValueException("Passwords entered do not match"));
	}
}catch(Exception $e){
	$_SESSION[$savedName] = $savedToken;
	echo "<div class='alert alert-danger' role='alert'>
  		  ".$e->getMessage()."</div>";
}
?>
