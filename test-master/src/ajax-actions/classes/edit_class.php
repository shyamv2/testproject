<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
//result
$r = array();
$r['error'] = false;
$r['msg_error'] = "";
//Class Array
$class = array();
//Verifying if there's SESSION
if(isset($_SESSION['id'])){
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
if($r['error'] == false){
  if(isset($_POST['class_id']) and is_numeric($_POST['class_id']));
  $class['id'] = mysqli_real_escape_string($con, $_POST['class_id']);
  if(user_class_permission($con, $_SESSION['id'], $class['id']) > 1){
    edit_class($con, $class);
  }

}
echo json_encode($r);
 ?>
