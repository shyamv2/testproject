<?php
	include "{$_SERVER['DOCUMENT_ROOT']}/src/functions/system.php";
	if(isset($_SESSION['id'])){
		if(isset($_GET['followed_id'])){
			$result = array();
			$followed_id = mysqli_real_escape_string($con, $_GET['followed_id']);
			$follower_id = $_SESSION['id'];
			//Firstly I check out if exist some registry about
			$sql_check = "SELECT * FROM follows WHERE followed_id = $followed_id AND follower_id = $follower_id";
			$r = mysqli_query($con, $sql_check) or die(mysqli_error());
			if(mysqli_num_rows($r) > 0){
				$result['toggle'] = 0;
				$delete_follow = "DELETE FROM follows WHERE followed_id = $followed_id AND follower_id = $follower_id";
				mysqli_query($con, $delete_follow);
			}
			else{
				$result['toggle'] = 1;
	 			$insert_follow = "INSERT INTO follows
				(followed_id, follower_id)
				VALUES ($followed_id, $follower_id)";
				mysqli_query($con, $insert_follow);
				//Notifications data
				$task = array();
				$follower = find_user($con, $follower_id);
				$task['receiver_id'] = $followed_id;
				$task['sender_id'] = $follower_id;
				$task['icon'] = $follower['picture'];
				$task['link_ref'] = "/profile/$follower_id-" . linka($follower['name']);
				$task['message'] = "<b>{$follower['name']}</b> started to follow you, visit their profile!";
				add_notification($con, $task);
			}
			echo json_encode($result);
		}
	}
?>
