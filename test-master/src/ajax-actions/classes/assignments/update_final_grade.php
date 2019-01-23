<?php
	include "{$_SERVER['DOCUMENT_ROOT']}/src/functions/system.php";
	$r = array();
	$r['error'] = false;
	$r['msg_error'] = "";
	if(isset($_SESSION['id'])){
		if(isset($_POST['class_id']) AND is_numeric($_POST['class_id'])){
			if(isset($_POST['user_id']) AND is_numeric($_POST['user_id'])){
					$grade = array();
					$grade['class_id'] = mysqli_real_escape_string($con, $_POST['class_id']);
					$grade['user_id'] = mysqli_real_escape_string($con, $_POST['user_id']);
					//Verify if user has permission
					if(user_class_permission($con, $_SESSION['id'], $grade['class_id'])){
						if(isset($_POST['grade'])){
							$grade['grade'] = clearString($con, $_POST['grade']);
							//ready to go now

							//verify if exist
							$sql_verify_if_exists = "SELECT id FROM members_final_grades
							WHERE user_id = {$grade['user_id']} AND class_id = {$grade['class_id']} limit 1";
							$r_verify_if_exists = mysqli_query($con, $sql_verify_if_exists) or die(mysqli_error($con));
							//IF exist: update
							if(mysqli_num_rows($r_verify_if_exists) > 0){
								$old = mysqli_fetch_assoc($r_verify_if_exists);
								//Update
								$sql_update = "UPDATE members_final_grades SET grade = '{$grade['grade']}' WHERE id = {$old['id']}";
								$r_update = mysqli_query($con, $sql_update);
								if($r_update){
									echo json_encode($r); exit;
								}
							}
							else{
								//Create
								$sql_create = "INSERT INTO members_final_grades
								(class_id, user_id, grade)
								VALUES
								(
										{$grade['class_id']},
										{$grade['user_id']},
										'{$grade['grade']}'
								)";
								$r_create = mysqli_query($con, $sql_create);
								if($r_create){
									echo json_encode($r); exit;
								}
							}

						}
					}
			}
		}
	}
?>
