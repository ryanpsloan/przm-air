<?php
include("../lib/csrf.php");
session_start();
if(isset($_SESSION['userId'])) {
	$mysqli = MysqliConfiguration::getMysqli();
	$profile = Profile::getProfileByUserId($mysqli,$_SESSION['userId']);

//var_dump($_SESSION);
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Sign In</title>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">

	<link type="text/css" rel="stylesheet" href="../css/signIn.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
	<script type="text/javascript" src="../js/signIn.js"></script>

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
																			  aria-hidden="true"></span> PRZM AIR</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li></li>

			</ul>

			<ul class="nav navbar-nav navbar-right">
				<li><a href="#"></a></li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
<div id="container">
	<div id="signUpLink">
		<p>New to PRZM Air?</p>
		<p><a href="signUp.php"><strong>Sign Up</strong></a></p>
	</div>

	<div id="signIn">
		<p style="text-align: center"><strong>Sign In</strong></p>
		<hr>
		<div id="outputArea"></div>
		<div id="formDiv">

		<form id="signInForm" action="../php/processors/signInProcessor.php" method="POST">
			<p><label for="email">Email:</label></br>
				<input type="email" id="email" name="email" autocomplete="off"></p>
			<p><label for="password">Password:</label></br>
				<input type="password" id="password" name="password" autocomplete="off"></p>
			<p><a id="forgotPass" href='forgotPass.php'>Forgot Your Password?</a></p>
			<?php echo generateInputTags(); ?>
			<button type="submit">Sign In</button>
		</form>
		</div>

	</div>
</div>

</body>
</html>