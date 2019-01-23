<?php
  //Including all the system and its files
  include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  //Error Array
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
    $comment['post_id'] = mysqli_real_escape_string($con, $_POST['post_id']);
    if(isset($_POST['comment']) AND !empty(trim($_POST['comment']))){
      $comment['post_response'] = clearString($con, $_POST['comment']);
      $comment['author_id'] = $_SESSION['id'];

      //Register
      $sql = "INSERT INTO post_responses
      (post_id, response, author_id)
      VALUES
      (
        {$comment['post_id']},
        '{$comment['post_response']}',
        {$comment['author_id']}
      )";
      mysqli_query($con, $sql) or die(mysqli_error($con));
      $comment['id'] = mysqli_insert_id($con);
      $response = find_post_response($con, $comment['id']);
      $author = find_user($con, $response['author_id']);
      $author_profile_link = "/profile/" . $author['id'] . "-" . linka($author['name']);
      $response['response'] = linkify_str(nl2br(htmlentities($response['response'])));
      $r['comment'] = <<<EOD
      <div id="comment-$response[id]">
        <div class="post-box-options">
          <div class="dropdown">
            <button class="btn btn-normal" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
            <ul class="dropdown-menu dropdown-menu-right">
              <li class="dropdown-item" onclick="deleteComment($response[id])"><i class="fa fa-remove"></i> Delete</li>
              <!--<li class="dropdown-item" onclick="prepareEditPost($response[id])"><i class="fa fa-edit"></i> Edit</li>-->
            </ul>
          </div>
        </div>
        <div class='post-box-id'>
          <img src="$author[picture]">
          <div class='post-box-id-name'>
            <a href="$author_profile_link">$author[name]</a>
            <small>just now</small>
          </div>
        </div>
        <div class="comment">$response[response]</div>
      </div>
EOD;
      //Register Educoin
      $coin = array();
      $coin['edu_value'] = 0.01;
      $coin['origin'] = "gave feedback";
      $coin['user_id'] = $_SESSION['id'];

      register_educoin($con, $coin);
      //Notify
      $post = find_post($con, $response['post_id']);
      //Task for notification
      $task = array();
      $task['sender_id'] = $_SESSION['id'];
      $task['receiver_id'] = $post['author_id'];
      $task['message'] = "<b>" . mysqli_real_escape_string($con, $_SESSION['name']) . "</b> commented your post. Go check it out!";
      $task['message'] = mysqli_real_escape_string($con, $task['message']);
      $task['link_ref'] = "/post/" . $post['id'];
      $task['icon'] = $_SESSION['picture'];
      //Add notification
      if($post['author_id'] != $_SESSION['id']){
        add_notification($con, $task);
      }
      echo json_encode($r);
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "Write something to continue...";
      echo json_encode($r);
    }
  }
}

?>
