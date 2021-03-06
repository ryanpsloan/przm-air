<?php
include('../lib/csrf.php');
session_start();

?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>Sign Up</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<!-- JavaScript -->
	<script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
	<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
	<script src="http://malsup.github.com/jquery.form.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/additional-methods.js"></script>
	<script type="text/javascript" src="../js/signUp.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
	<!-- CSS -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
	<link type="text/css" rel="stylesheet" href="../css/signUp.css">
	<script type="text/javascript">
		$(function() {
			$( ".datepicker" ).datepicker({
				changeMonth: true,
				changeYear: true,
				maxDate: "0dy",
				minDate: "-100y"
			});
		});
	</script>

</head>

<body>
<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="../index.php"><span class="glyphicon glyphicon-cloud"
																			  aria-hidden="true"></span> PRZM AIR </a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li></li>
				<li id="signInLink" class="hidden">
					<a href="signIn.php">Sign In</a>
				</li>

			</ul>

			<ul class="nav navbar-nav navbar-right">

				<li><a href="#"></a></li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav><!-- /.container-fluid -->
</nav>
<div id="container">
	<div id="formDiv">
		<p>This section of the site is down for maintenance. Sorry for the inconvenience</p>
			<fieldset>

				<legend style="text-align: center">Create a Profile</legend>
				<div id="outputArea"></div>
				<form id="signUpForm" action="../php/processors/signUpProcessor.php" method="POST">
				<p><label>First Name</label></br>
				<input type="text" id="first" name="first" autocomplete="off"></p>
				<p></p><label>Middle Name</label></br>
				<input type="text" id="middle" name="middle" autocomplete="off"><br>
				<p><label>Last Name</label></br>
					<input type="text" id="last" name="last" autocomplete="off"></p>
				<p><label>Date Of Birth</label></br>
					<input type="text" id="dob" name="dob" class="datepicker" autocomplete="off"></p>
				<p><label>Email</label></br>
					<input type="email" id="email" name="email" autocomplete="off"></p>
				<hr>
				<p>Minimum of 8 characters: letters, numbers, one capital and no special characters</p>
				<p><label>Password</label></br>
					<input type="password" id="password" name="password"></p>
				<p><label>Confirm Password</label></br>
					<input type="password" id="confPassword" name="confPassword"></p>
				<button id="button" type="submit">Register</button>
				</form>
		</fieldset>

	</div>
</div>
<?php
echo <<<HTML
		<script>
			$(function() {
				$('#signInLink').removeClass('hidden');
				$(':input').attr('disabled', true);
				$(':button').attr('disabled', true);
			});
		</script>
HTML;
?>
</body>
</html>

