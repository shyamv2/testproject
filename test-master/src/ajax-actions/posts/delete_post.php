<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
if(isset($_SESSION['id'])){
  if(isset($_GET['post_id']) AND is_numeric($_GET['post_id'])){
    $post_id = mysqli_real_escape_string($con, $_GET['post_id']);
      if(is_author_of_post($con, $post_id, $_SESSION['id'])){
        delete_post($con, $post_id);
      }
    }
}

?>
