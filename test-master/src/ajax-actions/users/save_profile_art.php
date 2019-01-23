<?php
	include "{$_SERVER['DOCUMENT_ROOT']}/src/functions/system.php";
	require "{$_SERVER['DOCUMENT_ROOT']}/src/libs/wideimage/lib/WideImage.php";
	if(isset($_POST['image']) &&
	isset($_POST['x']) &&
	isset($_POST['y']) &&
	isset($_POST['w']) &&
	isset($_POST['h'])){

        $image =  WideImage::load($_SERVER['DOCUMENT_ROOT'] . $_POST['image']);

        $image = $image->crop($_POST['x'], $_POST['y'], $_POST['w'], $_POST['h']);

        $fileName = sha1($_POST['image']) . date("Y.m.d-H.i.s") . ".jpg";

        $dir = $_SERVER['DOCUMENT_ROOT'] . "/images/cover_pictures/" . $fileName;

        $image->saveToFile($dir, 70);
        $user_id = $_SESSION['id'];
        $dir = "/images/cover_pictures/" . $fileName;
        $sql_edit = "UPDATE users SET cover_picture = '$dir' WHERE id = $user_id";
        mysqli_query($con, $sql_edit) or die(mysqli_error());
	}
?>
