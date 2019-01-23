<?php
	include "{$_SERVER['DOCUMENT_ROOT']}/src/functions/system.php";
	if($_SESSION['id']){
		$sql_remove = "UPDATE users SET cover_picture = 'NULL' WHERE id = {$_SESSION['id']}";
		mysqli_query($con, $sql_remove) or die(mysqli_error());
		exit;
	}
?>
