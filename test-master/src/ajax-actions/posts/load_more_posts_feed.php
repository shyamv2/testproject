<?php
	include "{$_SERVER['DOCUMENT_ROOT']}/src/functions/system.php";
	$r = array();
	$r['exist'] = false;
	$r['more'] = false;
	if(isset($_SESSION['id'])){
		if(isset($_POST['last_post']) AND is_numeric($_POST['last_post'])){
			$last = mysqli_real_escape_string($con, $_POST['last_post']);
			$user_id = $_SESSION['id'];
		$sql = "SELECT posts.*, posts.id as postID FROM follows
	  INNER JOIN (SELECT posts.*, users.name as author_name, users.picture as author_picture FROM posts LEFT JOIN users ON posts.author_id = users.id) AS posts
	  ON follows.followed_id = posts.author_id OR follows.follower_id = posts.author_id
	  WHERE (follows.follower_id = $user_id OR posts.author_id = $user_id)
	  AND posts.target = 'profile'
	  UNION ALL
	  SELECT posts.*, posts.id as postID FROM class_members
	  INNER JOIN (SELECT posts.*, users.name as author_name, users.picture as author_picture FROM posts LEFT JOIN users ON posts.author_id = users.id) AS posts
	  ON class_members.class_id = posts.target_id
	  WHERE (class_members.member_id = $user_id AND posts.target = 'class')
	  GROUP BY postID ORDER BY postID DESC limit 5";
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
