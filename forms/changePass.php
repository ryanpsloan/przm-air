<?php
include("../lib/csrf.php");
include("../php/class/profile.php");
include("/home/gaster15/przm.php");
session_start();
try {
	if(isset($_SESSION['userId'])) {
		$mysqli = MysqliConfiguration::getMysqli();
		$profile = Profile::getProfileByUserId($mysqli, $_SESSION['userId']);
		$fullName = ucfirst($profile->__get('userFirstName')) . ' ' . ucfirst($profile->__get('userLastName'));
		$userName = <<<EOF
		<a><span
			class="glyphicon glyphicon-user"></span> Welcome, $fullName  </a>

EOF;
		$status = <<< EOF
			<a href="../php/processors/signOut.php">Sign Out</a>

EOF;

	}
}catch(Exception $e){
	echo "<div class='alert alert-danger' role='alert'>".$e->getMessage()."</div>";
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Change Password</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
	<link type="text/css" rel="stylesheet" href="../css/changePass.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
	<script type="text/javascript" src="../js/changePass.js"></script>

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
				<li class="disabled"><?php echo $userName?> </li>
				<li class="active"><?php echo $status?></li>
				<li><a href="#"></a></li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
<div id="formDiv">
<fieldset>
	<form id="changePass" action="../php/processors/changePassProcessor.php" method="POST">
		<legend>Change Password</legend>
		<p>Minimum of 8 characters: letters, numbers, one capital and no special characters</p>
		<p><label>Old Password</label><br>
			<input type="password" id="oldPassword" name="oldPassword"></p>
		<p><label>New Password</label><br>
			<input type="password" id="password" name="password"></p>
		<p><label>Confirm New Password</label><br>
			<input type="password" id="confPassword" name="confPassword"></p>
		<p><input type="submit" value="Change Password"></p>
		<?php echo generateInputTags(); ?>
	</form>
	</fieldset>
	<div id="outputArea"></div>
</div>
</form>
</body>
</html>
