<?php
include("../lib/csrf.php");
session_start();
?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>Sign In</title>
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
	<script type="text/javascript" src="signIn.js"></script>

</head>
<body>
<div id="outputArea"></div>
<form id="signInForm" action="signInProcessor.php" method="POST">

	<label for="email">Email:</label>
	<input type="email" id="email" name="email" autocomplete="off"><br>
	<label for="password">Password:</label>
	<input type="password" id="password" name="password" autocomplete="off"><br>
	<p><a id="forgotPass" href='forgotPass.php'>Forgot Your Password?</a></p>
	<?php echo generateInputTags(); ?>
	<button type="submit">Sign In</button>

</form>

<div id="signUpLink"><p>OR</p>
<p></p><a href="signUp.php">Sign Up</a></div>
</body>
</html>