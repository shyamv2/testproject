<?php
  //Including all the system and its files
  include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  //Error Array
  $r = array();
  $r['error'] = false;
  $r['msg_error'] = "";
  //User Array
  $user = array();
  //Validaion - Name
  if(isset($_POST['signup_name']) AND !empty(trim($_POST['signup_name']))){
    if(strlen(trim($_POST['signup_name'])) > 1){
      $user['name'] = clearString($con, $_POST['signup_name']);
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "We're name must have at least 2 characters...";
      echo json_encode($r); exit;
    }
  }
  else{
    $r['error'] = true;
    $r['msg_error'] = "We're missing your name...";
    echo json_encode($r); exit;
  }
  //Validaion - Email
  if(isset($_POST['signup_email']) AND !empty(trim($_POST['signup_email']))){
    if(filter_var($_POST['signup_email'], FILTER_VALIDATE_EMAIL)){
      if(no_existant_email($con, $_POST['signup_email'])){
        $t_email = "@nwytg.com";
        if(strpos($_POST['signup_email'], $t_email) === false){
          $user['email'] = clearString($con, $_POST['signup_email']);
        }
        else{
          $r['error'] = true;
          $r['msg_error'] = "This email is not valid...";
          echo json_encode($r); exit;
        }
      }
      else{
        $r['error'] = true;
        $r['msg_error'] = "This email is already being used...";
        echo json_encode($r); exit;
      }
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "This email is not valid...";
      echo json_encode($r); exit;
    }
  }
  else{
    $r['error'] = true;
    $r['msg_error'] = "We're missing your email...";
    echo json_encode($r); exit;
  }
  //Validation - User Type
  if(isset($_POST['type']) AND is_numeric($_POST['type'])){
    if($_POST['type'] >= 0 AND $_POST['type'] <= 2){
      $user['type'] = mysqli_real_escape_string($con, $_POST['type']);
    }
  }
  else{
    $r['error'] = true;
    $r['msg_error'] = "Are you a student or a teacher?";
    echo json_encode($r); exit;
  }
  //Validaion - Password
  if(isset($_POST['signup_password']) AND !empty(trim($_POST['signup_password']))){
    $user['password'] = sha1($_POST['signup_password']);
  }
  else{
    $r['error'] = true;
    $r['msg_error'] = "We're missing your password...";
    echo json_encode($r); exit;
  }

  if($r['error'] == false){
    $user['ip'] = $_SERVER['REMOTE_ADDR'];
    $user['verified'] = 0;
    $user['id'] = sign_user_up($con, $user);
    $user_s = find_user($con, $user['id']);
    send_welcoming_email($con, $user_s);
    //Creating session
    $_SESSION['id'] = $user_s['id'];
    $_SESSION['name'] = $user_s['name'];
    $_SESSION['email'] = $user_s['email'];
    $_SESSION['type'] = $user_s['flag_type'];
    $_SESSION['picture'] = $user_s['picture'];
    $_SESSION['verified'] = $user_s['verified'];
    echo json_encode($r);
  }
 ?>
