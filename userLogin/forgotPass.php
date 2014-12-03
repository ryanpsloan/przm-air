<?php
include("../lib/csrf.php");
session_start();
?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>Password Reset</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
	<script type="text/javascript" src="forgotPass.js"></script>
</head>
<body>
<form id="forgotPass" action="forgotPass.php" method="POST">
	<fieldset>
		<legend>Reset Password</legend>
		<p><label>Email</label>
			<input type="email" id="email" name="email" autocomplete="off"></p>
		<p><label>New Password</label>
			<input type="password" id="password" name="password"></p>
		<p><label>Confirm New Password</label>
			<input type="password" id="confPassword" name="confPassword"></p>
		<?php echo generateInputTags(); ?>
		<button type="submit">Change Password</button>

	</fieldset>
	<div id="outputArea"></div>
</form>
</body>
</html>
