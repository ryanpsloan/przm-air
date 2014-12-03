<?php
include('../php/user.php');
include('../lib/csrf.php');
require_once("/etc/apache2/capstone-mysql/przm.php");
$mysqli = MysqliConfiguration::getMysqli();

session_start();

if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
	throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
}

$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
$user = User::getUserByEmail($mysqli,$email);
if($user === null){
	echo "<p>That email is not registered</p>
			<p><a href='signUp.php'>Sign Up</a></p>
			<p><a href='../index.php'>Home</a></p>";

}

$newPass = trim(filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING));
$newConf = trim(filter_input(INPUT_POST, "confPassword", FILTER_SANITIZE_STRING));

if($newPass === $newConf) {
		$hash = hash_pbkdf2("sha512", $newConf, $user->getSalt(), 2048, 128);
		if($hash !== $user->getPassword()) {
			$user->setPassword($hash);
			$user->setSalt($user->getSalt());
			$user->update($mysqli);
			echo "<p>Password Updated</p>
					<p><a href='signIn.php'>Sign In</a></p>
					<p><a href='../index.php'>Home</a></p>
					 <script>
			      	 $(document).ready(function() {
					   	    $(':input').attr('disabled', true);
						 });
				 	</script>";
		}
		else{
			echo "<p>Please enter a new password</p>";
		}
} else {
		echo "<p>Passwords entered do not match</p>";
}

?>
