<?php
require_once("user.php");

	$mysqli = new mysqli("localhost", "przm", "trillpontlureactscala", "przm");
	$salt = bin2hex(openssl_random_pseudo_bytes(32));
	$hash = hash_pbkdf2("sha512", "newPassword", $salt, 2048, 128);
   $token = bin2hex(openssl_random_pseudo_bytes(16));
	$user = new User (null, "thomasjamesparker3@gmail.com", $hash, $salt, $token);
	echo "Before Insert: ".$user;
	$user->insert($mysqli);
	echo "After Insert: ".$user;
	echo "user processed";
	echo $user;


require_once("profile.php");
$profile = new Profile(null, $user->getUserId(), "Thomas" ,"James","Parker","2014-01-31 12:12:12", "cus_57oz2ZjbCod7H5", $user);
echo "Before Insert: ".$profile;
$profile->insert($mysqli);
echo "After Insert: ".$profile;
echo "profile processed";
echo $profile;

?>