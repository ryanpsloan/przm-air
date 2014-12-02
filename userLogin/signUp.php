<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>Sign Up</title>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
	<script type="text/javascript" src="signUp.js"></script>
	<script type="text/javascript" src="signIn.js"></script>
</head>

<body>
<form id="signUp" action="signUpProcessor.php" method="POST">
	<fieldset>
	<legend>Create a Profile</legend>
	<p><label>First Name</label>
	<input type="text" id="first" name="first" autocomplete="off"></p>
	<p><label>Middle Name</label>
	<input type="text" id="middle" name="middle" autocomplete="off"><br>
	<p><label>Last Name</label>
	<input type="text" id="last" name="last" autocomplete="off"></p>
	<p>Enter date of birth in yyyy-mm-dd format</p>
	<p><label>Date Of Birth</label>
	<input type="date" id="dob" name="dob" autocomplete="off"></p>
	<p><label>Email</label>
	<input type="email" id="email" name="email" autocomplete="off"></p>
	<p>Please enter a password of a minimum of 5 characters using at least one capital, one letter, and one digit, no special characters, maximum 12</p>
	<p><label>Password</label>
	<input type="password" id="password" name="password"></p>
	<p><label>Confirm Password</label>
	<input type="password" id="confPassword" name="confPassword"></p>
	<button id="button" type="submit">Register</button>
	</fieldset>
	<div id="outputArea">

	</div>
</form>

</body>
</html>