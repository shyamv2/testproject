<?php
  //Including all the system and its files
  include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  //Error Array
  $r = array();
  $r['error'] = false;
  $r['msg_error'] = "";

if(!isset($_SESSION['id'])){
  if(!isset($_POST['user_id'])){
    exit;
  }
  if(isset($_POST['password']) AND !empty($_POST['password'])){
    if(isset($_POST['repeat_password']) AND !empty($_POST['repeat_password'])){
      //Verify if they're equal
      if($_POST['password'] == $_POST['repeat_password']){
        $password = sha1($_POST['password']);
        $user_id = mysqli_real_escape_string($con, $_POST['user_id']);
        //Reset Password
        reset_password($con, $password, $user_id);
        echo json_encode($r);
      }
      else{
        $r['error'] = true;
        $r['msg_error'] = "The passwords must be equal...";
        echo json_encode($r);
      }
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "Repeat your password to continue...";
      echo json_encode($r);
    }
  }
  else{
    $r['error'] = true;
    $r['msg_error'] = "Write your new password to continue...";
    echo json_encode($r);
  }
}

?>
