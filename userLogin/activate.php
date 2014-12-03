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
	$newProfile = Profile::getProfileByUserId($mysqli, $newUser->getUserId());
	$newProfile->setUserObject($newUser);

	$_SESSION['userId'] = $newUser->getUserId();
	$_SESSION['profileObj'] = $newProfile;


	echo "<div class='alert alert-success' role='alert'> <a href='#' class='alert-link'>Your account has been authenticated. You are now signed in</a>
			</div><p><a href='../index.php'>Home</a></p>";

}catch(Exception $e){
	echo "<div class='alert alert-danger' role='alert'><a href='#' class='alert-link'>".$e->getMessage()."</a>
	</div>";
}
?>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>