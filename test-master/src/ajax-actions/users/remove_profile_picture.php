<?php
	include "{$_SERVER['DOCUMENT_ROOT']}/src/functions/system.php";
	if(isset($_SESSION['id'])){
		$user_id = $_SESSION['id'];
	    $dir = "/images/profile_picture.jpg";
	    $sql_edit = "UPDATE users SET picture = '$dir' WHERE id = $user_id";
	    mysqli_query($con, $sql_edit) or die(mysqli_error());
	    $_SESSION['picture'] = $dir;
	}
?>
