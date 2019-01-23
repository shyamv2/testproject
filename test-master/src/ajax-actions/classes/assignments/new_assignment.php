<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";

//result
$r = array();
$r['error'] = false;
$r['msg_error'] = "";

//Assignment Array
$assign = array();

//Verifying if there's SESSION
if(isset($_SESSION['id'])){
  //Validate class ID
    if(isset($_POST['class_id']) AND is_numeric($_POST['class_id'])){
      $assign['class_id'] = mysqli_real_escape_string($con, $_POST['class_id']);
      if(user_class_permission($con, $_SESSION['id'], $assign['class_id'])){
        $assign['author_id'] = $_SESSION['id'];
      }
      else{
        exit;
      }
    }
    else{
      exit;
    }

    //Title Validation
    if(isset($_POST['title']) AND !empty(trim($_POST['title']))){
      $assign['title'] = clearString($con, $_POST['title']);
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "We're missing the title...";
      echo json_encode($r); exit;
    }

    //Description Validation
    if(isset($_POST['description']) AND !empty(trim($_POST['description']))){
      $assign['description'] = clearString($con, $_POST['description']);
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "We're missing the description...";
      echo json_encode($r); exit;
    }

    //Deadline Validation
    if(isset($_POST['deadline']) AND !empty(trim($_POST['deadline']))){
      if($_POST['deadline'] != "0000-00-00"){
        $assign['deadline'] = mysqli_real_escape_string($con, $_POST['deadline']);
      }
      else{
        $r['error'] = true;
        $r['msg_error'] = "Insert a valid deadline...";
        echo json_encode($r); exit;
      }
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "We're missing the deadline...";
      echo json_encode($r); exit;
    }

    //Educoin Value Validation
    if(isset($_POST['educoin_value']) AND is_numeric($_POST['educoin_value'])){
      if($_POST['educoin_value'] >= 0.5 AND $_POST['educoin_value'] <= 5){
        $assign['educoin_value'] = mysqli_real_escape_string($con, $_POST['educoin_value']);
      }
      else{
        $r['error'] = true;
        $r['msg_error'] = "Come on, do not try to hack the system, please...";
        exit;
      }
    }
}
else{
  exit;
}


if($r['error'] == false){

  $r['id'] = save_assignment($con, $assign);
  $r['link'] = "/assignment/" . $r['id'];
}
echo json_encode($r);
 ?>
