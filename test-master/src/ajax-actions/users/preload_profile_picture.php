<?php
	include "{$_SERVER['DOCUMENT_ROOT']}/src/functions/system.php";
	if(isset($_FILES['fileUpload'])){
		$r = array();
		$r['error'] = false;
		if($_SESSION['verified'] == 0){
	    $r['error'] = true;
	    $r['msg_error'] = "You need to verify your email in order to complete this action!";
	    echo json_encode($r); exit;
	  }
		//Allowed Extensions
		$allowedExts = array(".jpg", ".png", ".gif");
		//Dir name
		$dir = "{$_SERVER['DOCUMENT_ROOT']}/images/full_profile_pictures/";
		//File informations
		$file = array();
		$file['ext'] = strtolower(substr($_FILES['fileUpload']['name'],-4));
		if(in_array($file['ext'], $allowedExts)){
			if($_FILES['fileUpload']['size'] < 2000000){
				$file['name'] = sha1($_FILES['fileUpload']['name']) . date("Y.m.d-H.i.s") . $file['ext'];
				//Upload
				if(move_uploaded_file($_FILES['fileUpload']['tmp_name'], $dir.$file['name'])){
					$file['size'] = $_FILES['fileUpload']['size'];
					$sql_insert = "INSERT INTO full_profile_pictures
					(file_dir, file_size)
					VALUES
					('$dir.{$file['name']}', {$file['size']})
					";
					mysqli_query($con, $sql_insert) or die(mysqli_error());
					$r['img'] = "/images/full_profile_pictures/" . $file['name'];
				}
			}
			else{
				$r['error'] = true;
				$r['msg_error'] = "Upload an image up to <b>2M</b>!";
			}
		}
		else{
			$r['error'] = true;
			$r['msg_error'] = "Allowed extensions: .jpg, .png e .gif";
		}
		echo json_encode($r);
	}
?>
