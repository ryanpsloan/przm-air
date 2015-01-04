<?php
include('../../php/class/user.php');
require_once("/home/gaster15/przm.php");
try {
	session_start();
	$savedName = $_POST["csrfName"];
	$savedToken =$_POST["csrfToken"];
	$mysqli = MysqliConfiguration::getMysqli();

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
