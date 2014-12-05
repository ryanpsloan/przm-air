<?php
require("/etc/apache2/capstone-mysql/przm.php");
include("../php/user.php");
include("../php/profile.php");
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
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

</body>
</html>
<?php
try {
$mysqli = MysqliConfiguration::getMysqli();
$authToken = $_GET['authToken'];
$userId = $_GET['uId'];

$newUser = User::getUserByUserId($mysqli, $userId);
$newUser->setAuthenticationToken($authToken);
$newUser->update($mysqli);
$_SESSION['userId'] = $newUser->getUserId();

echo "<div class='alert alert-success' role='alert'> Your account has been authenticated. You are now signed in
</div>";

}catch(Exception $e){
echo "<div class='alert alert-danger' role='alert'>".$e->getMessage()."</div>";
}
?>