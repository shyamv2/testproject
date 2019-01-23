<?php
	ini_set('display_errors',1);
	ini_set('display_startup_erros',1);
	error_reporting(E_ALL);
	include $_SERVER['DOCUMENT_ROOT'] . "/src/libs/PHPMailer/PHPMailerAutoload.php"; //Plugin to send e-mail
	include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/connection.php"; //Connection to database
	include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/users.php"; //Connection to Users functions
	include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/classes.php"; //Connection to Classes functions
	include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/posts.php"; //Connection to Posts (Mural) functions
	include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/notifications.php"; //Connection to Notifications functions
	include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/chat.php"; //Connection to Chat functions
	include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/lessons.php"; //Connection to Lessons functions
	include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/cards.php"; //Connection to Cards functions
	include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/badges.php"; //Connection to badges functions
	include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/assignments.php"; //Connection to assignments functions

	/*
	//If there is a cookie, relog user
	if(!isset($_SESSION['id'])){
		if(isset($_COOKIE['pass_id']) && is_numeric($_COOKIE['pass_id'])){
			$_SESSION['id'] = $_COOKIE['pass_id'];
			$user = find_user($con, $_SESSION['id']);
			$_SESSION['name'] = $user['name'];
			$_SESSION['email'] = $user['email'];
			$_SESSION['picture'] = $user['picture'];
	    $_SESSION['id'] = $user['id'];
		}
	}*/
?>
