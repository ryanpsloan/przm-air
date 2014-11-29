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
<form id="sign>InForm" action="signInProcessor.php" method="POST">
<?php echo generateInputTags();?>
	<label for="email">Email:</label>
	<input type="email" id="email" name="email"><br>
	<label for="password">Password:</label>
	<input type="password" id="password" name="password"><br>
	<button type="submit">Sign In</button>

</form>
</body>
</html>