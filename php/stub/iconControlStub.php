<?php
require_once("../class/profile.php");
if(isset($_SESSION['userId'])) {
$mysqli = MysqliConfiguration::getMysqli();
$profile = Profile::getProfileByUserId($mysqli,$_SESSION['userId']);
$fullName =  ucfirst($profile->__get('userFirstName')).' '.ucfirst($profile->__get('userLastName'));
$userName = <<<EOF
<a><span	class="glyphicon glyphicon-user"></span> Welcome, $fullName  </a>
EOF;
$status = <<< EOF
<a href="signOut.php">Sign Out</a>
EOF;
$account = <<< EOF
<li role="presentation">
	<a href="#account" id="account-tab" role="tab" data-toggle="tab" aria-controls="account"
		aria-expanded="true">
		Account</a>
</li>
EOF;
}
?>