<?php
  //Including all the system and its files
  include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  //Error Array
  $r = array();
  $r['error'] = false;
  $r['msg_error'] = "";

if(isset($_SESSION['id'])){
  if(isset($_POST['assignment_id']) AND is_numeric($_POST['assignment_id'])){
    if(isset($_POST['plain_text']) AND !empty(trim($_POST['plain_text']))){
      ////////////////////////////////////////////////////////////

      //DO NOT FORGET TO CHECK IF STUDENT HAS THE PERMISSiON
      //INCLUDE THE UPLOAD OF FILES
      //AND VERIFY IF THERE's NO OTHER ASSIGNMENT SUBMITTED BY THE SAME USER

      ////////////////////////////////////////////////////////////
      $turnedIn = array();
      $turnedIn['assignment_id'] = mysqli_real_escape_string($con, $_POST['assignment_id']);
      $turnedIn['plain_text'] = clearString($con, $_POST['plain_text']);
      $turnedIn['user_id'] = $_SESSION['id'];

      $assignment = find_assignment($con, $turnedIn['assignment_id']);
      $class = find_class($con, $assignment['class_id']);

      if(user_class_permission($con, $turnedIn['user_id'], $class['id']) == 0){
        //Register
        register_assignment_submission($con, $turnedIn);
        //Reward with Educoins
        $coin = array();
        $coin['edu_value'] = $assignment['educoin_value'];
        $coin['origin'] = 'submitted an assignment';
        $coin['user_id'] = $turnedIn['user_id'];

        register_educoin($con, $coin);

        echo json_encode($r); exit;
      }
      else{
        $r['error'] = true;
        $r['msg_error'] = "You do not have permission to turn in...";
        echo json_encode($r); exit;
      }

     }
     else{
       $r['error'] = true;
       $r['msg_error'] = "Write something to continue...";
       echo json_encode($r); exit;
     }
  }
}
?>
