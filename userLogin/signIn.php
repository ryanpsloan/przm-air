<?php
session_start();
include("../lib/csrf.php");
?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>Sign In</title>
</head>
<body>
<form id="signInForm" action="signInProcessor.php" method="POST">
<?php echo generateInputTags();?>
	<label for="email">Email:</label>
	<input type="email" id="email" name="email" required="true"><br>
	<label for="password">Password:</label>
	<input type="password" id="password" name="password" required="true"><br>
	<button type="submit">Sign In</button>

</form>
<form id="signUpForm" action="signUp.php" method="POST">
	<p>OR</p>
	<button type="submit">Sign Up</button>
</form>
</body>
</html>