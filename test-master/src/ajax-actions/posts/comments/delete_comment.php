<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
if(isset($_SESSION['id'])){
  if(isset($_GET['comment_id']) AND is_numeric($_GET['comment_id'])){
    $comment_id = mysqli_real_escape_string($con, $_GET['comment_id']);
      if(is_author_of_comment($con, $comment_id, $_SESSION['id'])){
        delete_comment($con, $comment_id);
      }
    }
}

?>
