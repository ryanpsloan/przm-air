<?php
session_start();
include("../lib/csrf.php");
?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>Sign Up</title>
</head>
<body>
<form action="signUpProcessor.php" method="POST">
	<?php echo generateInputTags();?>
	<fieldset>
		<legend>Create a Profile</legend>
	<p><label>First Name</label>
	<input type="text" id="first" name="first" required="true"></p>
	<p><label>Middle Name</label>
	<input type="text" id="middle" name="middle"><br>
	<p><label>First Name</label>
	<input type="text" id="last" name="last" required="true"></p>
	<p><label>Date Of Birth</label>
	<input type="datetime" id="dob" name="dob" required="true"></p>
	<p><label>Email</label>
	<input type="email" id="email" name="email" required="true"></p>
	<p><label>Password</label>
	<input type="password" id="password" name="password" required="true"></p>
	<p><label>Confirm Password</label>
	<input type="password" id="confPassword" name="confPassword" required="true"></p>
	<button type="submit">Register</button>
	</fieldset>
</form>
</body>
</html>