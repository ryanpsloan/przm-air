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
	<script type="text/javascript" src="../js/forgotPass.js"></script>
	<style>
		#formDiv{
			padding: 2em;
			position: absolute;
			top: 10%;
			left: 20%;
			border: 1px solid lightgrey;
			height: 36em;
			width: 30em;
		}
		#outputArea{
			margin-top: 2em;
		}
	</style>
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
																		  aria-hidden="true"></span></a>
	</div>

	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<ul class="nav navbar-nav">
			<li>
				<a href="#"></a>
			</li>

		</ul>

		<ul class="nav navbar-nav navbar-right">
			<li><a href="#"></a></li>
		</ul>
	</div><!-- /.navbar-collapse -->
</div><!-- /.container-fluid -->
</nav>
<div id="formDiv">
	<form id="forgotPass" action="../php/processors/forgotPassProcessor.php" method="POST">
		<fieldset>
			<legend>Reset Password</legend>
			<p><label>Email</label><br>
				<input type="email" id="email" name="email" autocomplete="off"></p>
			<hr>
			<p>Minimum of 8 characters: letters, numbers, one capital, no special characters</p>
			<p><label>New Password</label><br>
			<input type="password" id="password" name="password"></p>
			<p><label>Confirm New Password</label><br>
				<input type="password" id="confPassword" name="confPassword"></p>

			<button type="submit">Change Password</button>
			<?php echo generateInputTags(); ?>
		</fieldset>
	</form>
	<div id="outputArea"></div>
</div>
</body>
</html>
