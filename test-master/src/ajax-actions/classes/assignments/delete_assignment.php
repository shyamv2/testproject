<?php
  //Including all the system and its files
  include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  //Error Array
  $r = array();
  $r['error'] = false;
  $r['msg_error'] = "";

if(isset($_SESSION['id'])){
  if(isset($_GET['assign_id']) AND is_numeric($_GET['assign_id'])){
      $assign_id = mysqli_real_escape_string($con, $_GET['assign_id']);
      $assignment = find_assignment($con, $assign_id);
      $class = find_class($con, $assignment['class_id']);

        if(user_class_permission($con, $_SESSION['id'], $class['id']) > 1){
          delete_assignment($con, $assign_id);
          echo json_encode($r); exit;
        }
        else{
          $r['error'] = true;
          $r['msg_error'] = "You do not have permission to delete this assignment...";
          echo json_encode($r); exit;
        }
     }
  }

?>
