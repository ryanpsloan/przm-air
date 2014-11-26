<?php
session_start();
require_once("/etc/apache2/capstone-mysql/prework.php");
require_once("../lib/csrf.php");
require_once("signup.php");
require_once("Mail.php");

try {
	// verify the form was submitted OK
	if (@isset($_POST["email"]) === false || @isset($_POST["password"]) === false) {
		throw(new RuntimeException("Form variables incomplete or missing"));
	}

	// verify the CSRF tokens
	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
	}

	// create a new object and insert it to mySQL
	$authToken = bin2hex(openssl_random_pseudo_bytes(16));
	$signup    = new Signup(null, $_POST["firstName"], $_POST["lastName"], $_POST["email"], $authToken, null, null);
	$mysqli    = MysqliConfiguration::getMysqli();
	$signup->insert($mysqli);

	// email the user with an activation message
	$to   = $signup->getEmail();
	$from = "dmcdonald21@cnm.edu";

	// build headers
	$headers                 = array();
	$headers["To"]           = $to;
	$headers["From"]         = $from;
	$headers["Repy-To"]      = $from;
	$headers["Subject"]      = $signup->getFirstName() . " " . $signup->getLastName() . ", Activate your CNM STEMulus Coding Bootcamp Login";
	$headers["MIME-Version"] = "1.0";
	$headers["Content-Type"] = "text/html; charset=UTF-8";

	// build message
	$pageName = end(explode("/", $_SERVER["PHP_SELF"]));
	$url      = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"];
	$url      = str_replace($pageName, "activate.php", $url);
	$url      = "$url?authToken=$authToken";
	$message = <<< EOF
<html>
    <body>
        <h1>Welcome to CNM STEMulus Coding Bootcamp</h1>
        <hr />
        <p>Thank you for signing up for a CNM STEMulus Coding Bootcamp Login. Visit the following URL to complete your registration process: <a href="$url">$url</a>.</p>
    </body>
</html>
EOF;

	// send the email
	error_reporting(E_ALL & ~E_STRICT);
	$mailer =& Mail::factory("sendmail");
	$status = $mailer->send($to, $headers, $message);
	if(PEAR::isError($status) === true)
	{
		echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to send mail message:" . $status->getMessage() . "</div>";
	}
	else
	{
		echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Sign up successful!</strong> Please check your Email to complete the signup process.</div>";
	}

} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to sign up: " . $exception->getMessage() . "</div>";
}
?>