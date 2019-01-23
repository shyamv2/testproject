<?php
	include "{$_SERVER['DOCUMENT_ROOT']}/src/functions/system.php";
	if(isset($_FILES['file'])){
		$r = array();
		$r['error'] = false;
		//Allowed Extensions
		$allowedExts = array("jpg", "jpeg", "png", "gif",
		"ai", "psd", "svg", "bmp", "tif", "tiff",
		"doc", "docx",
		"xls", "xlsx",
		"ppt", "pptx",
		"mp4", "mp3", "mkv", "avi", "wav", "ogg", "mid", "midi", "wma", "mpg", "mpeg",
		"zip", "rar", "giz", "7z", "gz",
		"epub", "pdf", "txt", "csv");
		//Dir name
		$dir = "{$_SERVER['DOCUMENT_ROOT']}/uploaded-files/";
		//File informations
		$file = array();
		$file['ext'] = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		if(in_array($file['ext'], $allowedExts)){
			if($_FILES['file']['size'] < 50000000){
				$file['original_name'] = mysqli_real_escape_string($con, $_FILES['file']['name']);
				$file['given_name'] = sha1($_FILES['file']['name'] . time() . uniqid()) . "." . $file['ext'];
				$file['dir'] = '/uploaded-files/' . $file['given_name'];
				//Upload
				if(move_uploaded_file($_FILES['file']['tmp_name'], $dir.$file['given_name'])){
					$file['size'] = $_FILES['file']['size'];
					$sql_insert = "INSERT INTO posts_files
					(post_id, file_dir, file_name, file_size, file_extension)
					VALUES
					(
						0,
						'{$file['dir']}',
						'{$file['original_name']}',
						{$file['size']},
						'{$file['ext']}'
					)";
					mysqli_query($con, $sql_insert) or die(mysqli_error($con));
					$r['file_id'] = mysqli_insert_id($con);
					echo json_encode($r);
				}
			}
			else{
				$r['error'] = true;
				$r['msg_error'] = "Upload a file up to <b>50M</b>!";
				echo json_encode($r);exit;
			}
		}
		else{
			$r['error'] = true;
			$r['msg_error'] = "This extension is not allowed!";
			echo json_encode($r);exit;
		}
	}
?>
