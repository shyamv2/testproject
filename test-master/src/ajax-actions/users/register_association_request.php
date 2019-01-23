<?php
	include "{$_SERVER['DOCUMENT_ROOT']}/src/functions/system.php";

	$r = array();
	$r['error'] = false;
	$r['msg_error'] = "";
	if(isset($_SESSION['id'])){
		if(isset($_POST['users'])){
			$request = array();
			$request['requester_id'] = $_SESSION['id'];
			$user = find_user($con, $request['requester_id']);
			foreach($_POST['users'] as $user_id){
				if(is_numeric($user_id)){
					$request['receiver_id'] = mysqli_real_escape_string($con, $user_id);
					if(no_existant_association_request($con, $request['requester_id'], $request['receiver_id'])){
						if(no_existant_account_association($con, $request['requester_id'], $request['receiver_id'])){
							register_association_request($con, $request);

							//Send notification
							$task = array();
			        $task['sender_id'] = $_SESSION['id'];
			        $task['receiver_id'] = $request['receiver_id'];
			        $task['message'] = "<b>" . $_SESSION['name'] . "</b> requested an account association with you.";
			        $task['message'] = mysqli_real_escape_string($con, $task['message']);
			        $task['link_ref'] = "/profile/" . $_SESSION['id'] . "-" . linka($_SESSION['name']);
			        $task['icon'] = $_SESSION['picture'];
			        //Add notification
			        add_notification($con, $task);
						}
					}
				}
			}
			echo json_encode($r);
		}
	}
?>
