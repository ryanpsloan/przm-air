<?php
require("/etc/apache2/capstone-mysql/przm.php");
require("../php/user.php");
require("../php/profile.php");
require("../lib/csrf.php");
require("Mail.php");
session_start();

try {
	$savedName  = $_POST["csrfName"];
	$savedToken = $_POST["csrfToken"];

	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("Make sure cookies are enabled"));
	}

	//filter and process input
	$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
	$confPassword = filter_input(INPUT_POST, "confPassword", FILTER_SANITIZE_STRING);
	$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING);
	$mysqli = MysqliConfiguration::getMysqli();

	if(User::getUserByEmail($mysqli, $email) !== null) {
		echo "<script>
					$(function() {
							$('#signInLink').removeClass('hidden');
							$(':input').attr('disabled', true);
						});
				</script>";
		throw(new RuntimeException("That email is already in use. Sign-in or use a different email"));
	}

	$firstNm = filter_input(INPUT_POST, "first", FILTER_SANITIZE_STRING);
	$middleNm = filter_input(INPUT_POST, "middle", FILTER_SANITIZE_STRING);
	$lastNm = filter_input(INPUT_POST, "last", FILTER_SANITIZE_STRING);
	$DOB = filter_input(INPUT_POST, "dob", FILTER_SANITIZE_STRING);
	$DOB = DateTime::createFromFormat("m/d/Y", $DOB);
	$DOB = $DOB->format("Y-m-d H:i:s");
	$fullName = $firstNm . " " . $middleNm . " " . $lastNm;
	$customer = Stripe_Customer::create(array('description' => $fullName . " | " . $email));
	$custToken = $customer->id;

	$salt = bin2hex(openssl_random_pseudo_bytes(32));
	$authToken = bin2hex(openssl_random_pseudo_bytes(16));
	$hash = hash_pbkdf2("sha512", $confPassword, $salt, 2048, 128);

	$newUser = new User(null, $email, $hash, $salt, null);
	$newUser->insert($mysqli);

	$newProfile = new Profile(null, $newUser->getUserId(), $firstNm, $middleNm, $lastNm,
		$DOB, $custToken, $newUser);
	$newProfile->insert($mysqli);

	// email the user with an activation message
	$to = $newUser->getEmail();
	$from = "noreply@przm-air.com";

	// build headers
	$headers = array();
	$headers["To"] = $to;
	$headers["From"] = $from;
	$headers["Reply-To"] = $from;
	$headers["Subject"] = $newProfile->__get('userFirstName') . " " . $newProfile->__get('userLastName') . ",
		Activate your PRAM Air Login";
	$headers["MIME-Version"] = "1.0";
	$headers["Content-Type"] = "text/html; charset=UTF-8";

	// build message
	$pageName = end(explode("/", $_SERVER["PHP_SELF"]));
	$url = "https://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"];
	$url = str_replace($pageName, "activate.php", $url);
	$url = "$url?authToken=$authToken&uId=".$newUser->getUserId();
	$message = <<< EOF
<html>
    <body>
        <h1>Welcome to PRZM Air, Your Access to the Skies</h1>
        <hr />
        <p>Thank you for signing up for PRZM-Air. Visit the following URL
         to complete your registration process: <a href="$url">$url</a>.</p>
    </body>
</html>
EOF;
	$output = "<script>
						$(function() {
							$(':input').attr('disabled', true);
						});
					</script>";
	// send the email
	error_reporting(E_ALL & ~E_STRICT);
	$mailer =& Mail::factory("sendmail");
	$status = $mailer->send($to, $headers, $message);
	if(PEAR::isError($status) === true) {
		echo $output;
		throw(new RuntimeException("Unable to send mail message:" .$status->getMessage()));
	} else {
		echo "<div class=\"alert alert-success\" role=\"alert\">Sign up successful!
						Please <strong>check your email</strong> to complete the signup process.</div>" . $output;
	}

}catch(Exception $e){
	$_SESSION[$savedName] = $savedToken;
	echo "<div class='alert alert-danger' role='alert'>".$e->getMessage()."</div>";
}
?>