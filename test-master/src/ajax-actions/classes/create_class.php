<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
//result
$r = array();
$r['error'] = false;
$r['msg_error'] = "";
//Class Array
$class = array();
//Verifying if there's SESSION
if(isset($_SESSION['id']) AND $_SESSION['type'] == 1){
  if($_SESSION['verified'] == 0){
    $r['error'] = true;
    $r['msg_error'] = "You need to verify your email in order to complete this action!";
    echo json_encode($r); exit;
  }
    //NAME
    if(isset($_POST['name']) AND !empty(trim($_POST['name']))){
      $class['name'] = clearString($con, $_POST['name']);
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "We're missing the name...";
      echo json_encode($r); exit;
    }
    //DESCRIPTION
    if(isset($_POST['description']) AND !empty(trim($_POST['description']))){
      $class['description'] = clearString($con, $_POST['description']);
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "We're missing the description...";
      echo json_encode($r); exit;
    }
    //START
    if(isset($_POST['start']) AND !empty(trim($_POST['start']))){
      if($_POST['start'] != "0000-00-00"){
        $class['start_date'] = mysqli_real_escape_string($con, $_POST['start']);
      }
      else{
        $r['error'] = true;
        $r['msg_error'] = "Insert a valid date...";
        echo json_encode($r); exit;
      }
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "We're missing the date it begins...";
      echo json_encode($r); exit;
    }
    //END
    if(isset($_POST['end']) AND !empty(trim($_POST['end']))){
      if($_POST['end'] != "0000-00-00"){
        $class['end_date'] = mysqli_real_escape_string($con, $_POST['end']);
      }
      else{
        $r['error'] = true;
        $r['msg_error'] = "Insert a valid date...";
        echo json_encode($r); exit;
      }
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "We're missing the date it ends...";
      echo json_encode($r); exit;
    }

    //PRIVACY
    if(isset($_POST['privacy']) AND is_numeric($_POST['privacy'])){
      if($_POST['privacy'] == 0 OR $_POST['privacy'] == 1){
        $class['privacy'] = mysqli_real_escape_string($con, $_POST['privacy']);
      }
      else{
        $r['error'] = true;
        $r['msg_error'] = "Whoops! Select a valid value for privacy...";
        echo json_encode($r); exit;
      }
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "What's the privacy of your class?";
      echo json_encode($r); exit;
    }
}
else{
  exit;
}
//Verify last class created by the same user to prevent the creating classes to gain educoin
$last_class = last_class($con, $_SESSION['id']);
if($last_class != false){
  $registry = new DateTime($last_class['registry']);
  $registry = $registry->getTimestamp();
  $past = time() - 1800;
  if(intval($registry) > $past){
    $r['error'] = true;
    $r['msg_error'] = "You can create classes once 30 minutes.";
    echo json_encode($r); exit;
  }
}

if($r['error'] == false){
  $class['code'] = generateRandomCode();
  while(existant_class_code($con, $class['code'])){
    $class['code'] = generateRandomCode();
  }

  $r['id'] = save_class($con, $class);
  $r['link'] = "/class/" . $r['id'] . "-" . linka($class['name']);
  //Register Educoin
  $coin = array();
  $coin['edu_value'] = 1;
  $coin['origin'] = 'created a class';
  $coin['user_id'] = $_SESSION['id'];

  register_educoin($con, $coin);
}
echo json_encode($r);
 ?>
