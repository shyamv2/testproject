<?php
	include "{$_SERVER['DOCUMENT_ROOT']}/src/functions/system.php";
	$r = array();
	$r['error'] = false;
	$r['msg_error'] = "";
	if(isset($_SESSION['id'])){
		if($_SESSION['verified'] == 0){
	    $r['error'] = true;
	    $r['msg_error'] = "You need to verify your email in order to complete this action!";
	    echo json_encode($r); exit;
	  }
		if(isset($_POST['post_id']) AND is_numeric($_POST['post_id'])){
			if(isset($_POST['reaction']) AND is_numeric($_POST['reaction'])){
				$reaction = array();
				$reaction['post_id'] = mysqli_real_escape_string($con, $_POST['post_id']);
				if($_POST['reaction'] >= 1 AND $_POST['reaction'] <= 7){
					$reaction['id'] = mysqli_real_escape_string($con, $_POST['reaction']);
				}
				else{
					exit;
				}
				$reaction['user_id'] = $_SESSION['id'];
				//Remove past reactions
				$sql_remove = "DELETE FROM posts_reactions
				WHERE post_id = {$reaction['post_id']} AND user_id = {$reaction['user_id']}";
				mysqli_query($con, $sql_remove) or die(mysqli_error($con));
				//Register
				$sql_insert = "INSERT INTO posts_reactions
				(post_id, user_id, reaction)
				VALUES
				(
					{$reaction['post_id']},
					{$reaction['user_id']},
					{$reaction['id']}
				)";
				mysqli_query($con, $sql_insert) or die(mysqli_error($con));

				$post = find_post($con, $reaction['post_id']);

				$reaction = find_reaction($con, $reaction['id']);
				//Register Educoin
        $coin = array();
        $coin['edu_value'] = 0.05;
        $coin['origin'] = 'reacted';
        $coin['user_id'] = $_SESSION['id'];

        //register_educoin($con, $coin);

				//Register Educoin
        $coin = array();
        $coin['edu_value'] = 0.05;
        $coin['origin'] = 'received a reaction';
        $coin['user_id'] = $post['author_id'];

        //register_educoin($con, $coin);
				//Task for notification
        $task = array();
        $task['sender_id'] = $_SESSION['id'];
        $task['receiver_id'] = $post['author_id'];
        $task['message'] = $_SESSION['name'] . " reacted with <b>" . $reaction['name'] . "</b> in your post.";
        $task['message'] = mysqli_real_escape_string($con, $task['message']);
        $task['link_ref'] = "/post/" . $post['id'];
        $task['icon'] = $reaction['icon'];
        //Add notification
        add_notification($con, $task);

				echo json_encode($r);
			}
		}
	}
?>
