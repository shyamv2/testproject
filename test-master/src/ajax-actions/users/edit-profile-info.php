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
  if(isset($_POST['name']) AND !empty(trim($_POST['name']))){
    if(strlen($_POST['name']) > 1){
      $user['name'] = clearString($con, $_POST['name']);
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "Your name must have at least 2 characters...";
      echo json_encode($r); exit;
    }
  }
  else{
    $r['error'] = true;
    $r['msg_error'] = "We're missing your name...";
    echo json_encode($r); exit;
  }
  //Validaion - Email
  if(isset($_POST['email']) AND !empty(trim($_POST['email']))){
    if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
      $email = clearString($con, $_POST['email']);
      if(no_existant_email($con, $email, true)){
        $t_email = "@nwytg.com";
        if(strpos($email, $t_email) === false){
          $user['email'] = $email;
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
  //Validaion - AssociatedEmail
  if(isset($_POST['associated_email']) AND !empty(trim($_POST['associated_email']))){
    if(filter_var($_POST['associated_email'], FILTER_VALIDATE_EMAIL)){
      $user['associated_email'] = clearString($con, $_POST['associated_email']);
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "This associated email is not valid...";
      echo json_encode($r); exit;
    }
  }
  else{
    $user['associated_email'] = "";
  }
  //Validate User type
  if(isset($_POST['type']) AND is_numeric($_POST['type'])){
    $type = mysqli_real_escape_string($con, $_POST['type']);
    if($type >= 0 OR $type <= 2){
      $user['flag_type'] = $type;
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "Pick a valid type...";
      echo json_encode($r); exit;
    }
  }
  else{
    $r['error'] = true;
    $r['msg_error'] = "Are you a student or a teacher?";
    echo json_encode($r); exit;
  }

  //Validate the bio
  if(isset($_POST['bio']) and !empty(trim($_POST['bio']))){
    if(strlen($_POST['bio']) > 350){
      $r['error'] = true;
      $r['msg_error'] = "The length of your bio must be up to 350 characters...";
      echo json_encode($r); exit;
    }
    else{
      $user['bio'] = clearString($con, $_POST['bio']);
    }
  }
  else{
    $user['bio'] = "";
  }
  //Validate the school
  if(isset($_POST['school']) and !empty(trim($_POST['school']))){
    $user['school'] = clearString($con, $_POST['school']);
  }
  else{
    $user['school'] = "";
  }
  //Validate birthdate
  if(isset($_POST['birthdate']) and !empty(trim($_POST['birthdate']))){
    $date = mysqli_real_escape_string($con, $_POST['birthdate']);
    if(validateDate($date, "Y-m-d")){
      $user['birthdate'] = $date;
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "Insert a valid birthdate...";
      echo json_encode($r); exit;
    }
  }
  else{
    $user['birthdate'] = "1111-11-11";
  }
  //Validate Genre
  if(isset($_POST['genre']) and !empty(trim($_POST['genre'])) AND is_numeric($_POST['genre'])){
    $genre = mysqli_real_escape_string($con, $_POST['genre']);
    if($genre >= 1 AND $genre <= 3){
      $user['genre'] = $genre;
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "Pick a valid genre...";
      echo json_encode($r); exit;
    }
  }
  else{
    $user['genre'] = 0;
  }
  if($r['error'] == false){
    $old = find_user($con, $_SESSION['id']);
    $user['id'] = $_SESSION['id'];
    if($user['email'] != $_SESSION['email']){
      $user['verified'] = 0;
    }
    else{
      $user['verified'] = $old['verified'];
    }
    if(edit_profile($con, $user)){
      $_SESSION['verified'] = $user['verified'];
      $_SESSION['name'] = $user['name'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['type'] = $user['flag_type'];

    }

    echo json_encode($r);
  }
 ?>
