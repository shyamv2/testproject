<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
$r = array();
if(isset($_SESSION['id'])){
  if(isset($_GET['post_id']) AND is_numeric($_GET['post_id'])){
    if(isset($_GET['post'])){
    $post_id = mysqli_real_escape_string($con, $_GET['post_id']);
    $post = mysqli_real_escape_string($con, $_GET['post']);
      if(is_author_of_post($con, $post_id, $_SESSION['id'])){
        $sql = "UPDATE posts SET post = '$post' WHERE id = $post_id";
        mysqli_query($con, $sql) or die(mysqli_error());
        echo json_encode($r);
      }
    }
  }
}

?>
