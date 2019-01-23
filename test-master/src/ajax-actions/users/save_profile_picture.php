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

        $image = $image->resize(150, 150);

        $fileName = sha1($_POST['image']) . date("Y.m.d-H.i.s") . ".jpg";

        $dir = $_SERVER['DOCUMENT_ROOT'] . "/images/profile_pictures/" . $fileName;

        $image->saveToFile($dir);
        $user_id = $_SESSION['id'];
        $dir = "/images/profile_pictures/" . $fileName;
        $sql_edit = "UPDATE users SET picture = '$dir' WHERE id = $user_id";
        mysqli_query($con, $sql_edit) or die(mysqli_error());
        $_SESSION['picture'] = $dir;
	}
?>
