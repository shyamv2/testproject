<?php
  //Including all the system and its files
  include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  //Error Array
  $r = array();
  $r['error'] = false;
  $r['msg_error'] = "";
  //User Array
  $user = array();
  if(isset($_SESSION['id'])){
    if(isset($_POST['members']) AND !empty($_POST['members'])){
      if(isset($_POST['email']) AND !empty($_POST['email'])){
        $list_of_members = $_POST['members'];
        $sender = $_SESSION['name'];
        $email = '';
        $email = nl2br(mysqli_real_escape_string($con, $_POST['email']));
        $email = preg_replace("/\r\n|\r|\n/",'<br/>', $email);
        if(send_email_to_class($con, $list_of_members, $sender, $email)){
          $r['error'] = false;
        }
        else{
          $r['error'] = true;
          $r['msg_error'] = "Something went wrong, please try again!";
        }
        echo json_encode($r);
      }
      else{
        $r['error'] = true;
        $r['msg_error'] = "Write something to continue...";
        echo json_encode($r);
      }
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "Select one or more members to continue...";
      echo json_encode($r);
    }
  }
 ?>
