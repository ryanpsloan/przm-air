<?php
session_start();
$userIdSession = $_SESSION['userId'];
$_SESSION = array();
$params = session_get_cookie_params();
setcookie(session_name(), "", 1, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
session_unset();
session_destroy();
$_SESSION = $userIdSession;
header("Location: ../../index.php");
?>