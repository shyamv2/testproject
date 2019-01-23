<?php
	include "{$_SERVER['DOCUMENT_ROOT']}/src/functions/system.php";
	if(isset($_FILES['fileUpload'])){
		$r = array();
		$r['error'] = false;
		$r['msg_error'] = "";
		if($_SESSION['verified'] == 0){
	    $r['error'] = true;
	    $r['msg_error'] = "You need to verify your email in order to complete this action!";
	    echo json_encode($r); exit;
	  }
		//Allowed Extensions
		$allowedExts = array(".jpg", ".png", ".gif");
		//Dir name
		$dir = "{$_SERVER['DOCUMENT_ROOT']}/images/full_cover_pictures/";
		//File informations
		$file = array();
		$file['ext'] = strtolower(substr($_FILES['fileUpload']['name'],-4));
		if(in_array($file['ext'], $allowedExts)){
			if($_FILES['fileUpload']['size'] < 2000000){
				$file['name'] = sha1($_FILES['fileUpload']['name']) . date("Y.m.d-H.i.s") . $file['ext'];
				//Upload
				if(move_uploaded_file($_FILES['fileUpload']['tmp_name'], $dir.$file['name'])){
					$file['size'] = $_FILES['fileUpload']['size'];
					$r['img'] = "/images/full_cover_pictures/" . $file['name'];
				}
			}
			else{
				$r['error'] = true;
				$r['msg_error'] = "Upload an image up to <b>2M</b>!";
				echo json_encode($r); exit;
			}
		}
		else{
			$r['error'] = true;
			$r['msg_error'] = "Allowed extensions: .jpg, .png e .gif!";
			echo json_encode($r); exit;
		}
	}
	echo json_encode($r);
?>
