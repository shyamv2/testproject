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
  if(isset($_POST['target_id']) AND is_numeric($_POST['target_id'])){
    $post = array();
    $post['target_id'] =  mysqli_real_escape_string($con, $_POST['target_id']);
    $post['alternative_target_id'] = 0;
    $post['target'] = 'profile';
      if(isset($_POST['post']) AND !empty(trim($_POST['post']))){
        $post['post'] = clearString($con, $_POST['post']);
        $post['author_id'] = $_SESSION['id'];
        //Register post
        if(validate_post($con, $post['post'])){
          $post_id = register_post($con, $post);
        }
        else{
          $r['error'] = true;
          $r['msg_error'] = "It seems like you are a robot...";
          echo json_encode($r); exit;
        }
        //Validate attaches
        if(isset($_POST['attaches'])){
          $attaches = array();
          foreach($_POST['attaches'] as $att){
            $attach_id = mysqli_real_escape_string($con, $att);
            $sql_update = "UPDATE posts_files SET post_id = $post_id WHERE id = $attach_id";
            mysqli_query($con, $sql_update) or die(mysqli_error($con));
          }
        }
        //Validate Video
        if(isset($_POST['video']) AND !empty(trim($_POST['video']))){
          $video = mysqli_real_escape_string($con, $_POST['video']);
          $sql_register_video = "INSERT INTO posts_links (post_id, type, link) VALUES
          ($post_id, 'video', '$video')";
          mysqli_query($con, $sql_register_video) or die(mysqli_error($con));
        }
        //Validate Link
        if(isset($_POST['link']) AND !empty(trim($_POST['link']))){
          $link = mysqli_real_escape_string($con, $_POST['link']);
          $sql_register_link = "INSERT INTO posts_links (post_id, type, link) VALUES
          ($post_id, 'link', '$link')";
          mysqli_query($con, $sql_register_link) or die(mysqli_error($con));
        }
        $post = find_post($con, $post_id);
        ob_start();
        include($_SERVER['DOCUMENT_ROOT'] . '/templates/includes/post.php');
        $r['post'] =  ob_get_contents();
        ob_end_clean();

        //Register Educoin
        $coin = array();
        $coin['edu_value'] = 0.05;
        $coin['origin'] = 'posted to profile';
        $coin['user_id'] = $_SESSION['id'];

        register_educoin($con, $coin);

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
