<?php
//Conection with data base
header('Content-Type: text/html; charset=utf8');
session_start();
//calling helper
include "helper.php";
//datas of database

$bd_user = "root";
$bd_password = "Lockdesign1";
$bd_host = "localhost";
$bd_database = "istudy";

/*
$bd_user = "u666295139_study";
$bd_password = "Ra19271996";
$bd_host = "mysql.hostinger.com.br";
$bd_database = "u666295139_study";
*/

//Connection
$con = mysqli_connect($bd_host, $bd_user, $bd_password, $bd_database);
//Verify the conection
if(mysqli_connect_errno($con)){
	echo "Something went wrong, reload the page or contact an administrator!";
	die();
}


function register_page_view($con){
	include_once $_SERVER['DOCUMENT_ROOT'] . "/src/libs/BrowserDetection/lib/BrowserDetection.php";
	$browser = new BrowserDetection();
  //View Info
	$view = array();
	if(isset($_SESSION['id'])){
		$view['user_id'] = $_SESSION['id'];
	}
	else{
		$view['user_id'] = 0;
	}
	$view['ip'] = $_SERVER['REMOTE_ADDR'];
	$view['page'] = $_SERVER['REQUEST_URI'];
	$view['browser'] = $browser->getName();
	$view['browser_version'] = $browser->getVersion();
	$view['platform_version'] = $browser->getPlatformVersion();
	$view['is_mobile'] = $browser->isMobile();

	//SQL
	$sql = "INSERT INTO page_views (user_id, ip, page, browser, browser_version, platform_version)
	VALUES
	(
		{$view['user_id']},
		'{$view['ip']}',
		'{$view['page']}',
		'{$view['browser']}',
		'{$view['browser_version']}',
		'{$view['platform_version']}'
	)";
	return mysqli_query($con, $sql) or die(mysqli_error($con));
}
?>
