<?php
	include "{$_SERVER['DOCUMENT_ROOT']}/src/functions/system.php";
	unset($_COOKIE['pass_id']);
	setcookie("pass_id", "", time() - 3600);
	session_destroy();
	header('location: /');
	die();
?>
