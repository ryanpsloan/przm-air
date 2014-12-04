<?php
require("/etc/apache2/capstone-mysql/przm.php");
include("../php/user.php");
include("../php/profile.php");
try {
	session_start();
	$mysqli = MysqliConfiguration::getMysqli();
	echo "<p>Authenticating your account</p>";
	$authToken = $_GET['authToken'];

	$newUser = User::getUserByAuthToken($mysqli, $authToken);

	$_SESSION['userId'] = $newUser->getUserId();



	echo "<div class='alert alert-success' role='alert'> Your account has been authenticated. You are now signed in
			</div><p><a href='../index.php'>Home</a></p>";

}catch(Exception $e){
	echo "<div class='alert alert-danger' role='alert'>".$e->getMessage()."</div>";
}
?>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>