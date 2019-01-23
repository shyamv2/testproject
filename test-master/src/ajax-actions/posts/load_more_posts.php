<?php
	include "{$_SERVER['DOCUMENT_ROOT']}/src/functions/system.php";
	$r = array();
	$r['exist'] = false;
	$r['more'] = false;
	if(isset($_SESSION['id'])){
		if(isset($_POST['target']) AND isset($_POST['target_id']) AND isset($_POST['last_post'])){
			$target = mysqli_real_escape_string($con, $_POST['target']);
			$target_id = mysqli_real_escape_string($con, $_POST['target_id']);
			$last = mysqli_real_escape_string($con, $_POST['last_post']);

		$sql = "SELECT posts.*, users.name as author_name, users.picture as author_picture
		FROM posts INNER JOIN users
		ON posts.author_id = users.id
		WHERE (posts.target_id = $target_id and posts.target = '$target') and posts.id < $last
		ORDER BY posts.id DESC limit 5";
		  $result = mysqli_query($con, $sql) or die(mysqli_error($con));
			if(mysqli_num_rows($result) > 0){
				$r['exist'] = true;
				if(mysqli_num_rows($result) == 5){
					$r['more'] = true;
				}
			}
			$r['posts'] = "";
			$list_posts = array();
		  while($each = mysqli_fetch_assoc($result)){
				$list_posts[] = $each;
		  }
			$r['posts'] = "<script>var comments = [];</script>";
			foreach($list_posts as $post){
				ob_start();
        include($_SERVER['DOCUMENT_ROOT'] . '/templates/includes/post.php');
        $r['posts'] .=  ob_get_contents();
				$r['posts'] .= <<<EOD
				<script>
			      var postId = $post[id];
			      comments[postId] = $("#comment-input-" + postId).emojioneArea({
			        pickerPosition: "bottom",
			        tonesStyle: "bullet",
			        events: {
			          click: function(editor, event){
			            displayCommentForm($post[id]);
			          }
			        }
			      });
			  </script>
EOD;
        ob_end_clean();
				$r['last_post'] = $post['id'];
			}
		  echo json_encode($r);
		}
	}
?>
