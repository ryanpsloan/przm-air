<?php
require("/etc/apache2/capstone-mysql/przm.php");
$mysqli = MysqliConfiguration::getMysqli();
include("../php/user.php");
include("../php/profile.php");
session_start();
echo "<p>Authenticating your account</p>";
$authToken = $_GET['authToken'];

$newUser = User::getUserByAuthToken($mysqli,$authToken);
$newProfile = Profile::getProfileByUserId($mysqli, $newUser->getUserId());
$newProfile->setUserObject($newUser);

$_SESSION['userId'] = $newUser->getUserId();
$_SESSION["authToken"] = $newUser->getAuthenticationToken();
$_SESSION['profileObj'] = $newProfile;


echo "<p>Your account has been authenticated. You are now signed in.</p>
		<p><a href='../index.php'>Home</a></p>";
?>