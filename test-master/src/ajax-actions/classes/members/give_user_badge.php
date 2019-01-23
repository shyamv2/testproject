<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
$r = array();
$r['error'] = false;
if(isset($_SESSION['id'])){
  $badge = array();
  //Validate Badge ID
  if(isset($_POST['badge_id']) AND is_numeric($_POST['badge_id'])){
    if($_POST['badge_id'] > 0 AND $_POST['badge_id'] < 6){
      $badge['badge_id'] = mysqli_real_escape_string($con, $_POST['badge_id']);
    }
  }
  else{
    $r['error'] = true;
  }
  //Validate Enrollment ID
  if(isset($_POST['enrollment_id']) AND is_numeric($_POST['enrollment_id'])){
    $badge['enrollment_id'] = mysqli_real_escape_string($con, $_POST['enrollment_id']);
  }
  else{
    $r['error'] = true;
  }
  //Validate USER ID
  if(isset($_POST['user_id']) AND is_numeric($_POST['user_id'])){
    $badge['user_id'] = mysqli_real_escape_string($con, $_POST['user_id']);
  }
  else{
    $r['error'] = true;
  }

  //Giver_id
  $badge['giver_id'] = $_SESSION['id'];

  if(eligible_badge($con, $badge)){
    //Register Educoin
    $coin = array();
    $coin['edu_value'] = $badge['badge_id'];
    $coin['origin'] = 'earned a badge';
    $coin['user_id'] = $badge['user_id'];

    register_educoin($con, $coin);
  }

  //Give badge
  deactivate_past_badges($con, $badge);
  if(!give_user_badge($con, $badge)){
    $r['error'] = true;
  }
  $bd = find_badge($con, $badge['badge_id']);
  $enroll = find_enrollment($con, $badge['enrollment_id']);
  $class = find_class($con, $enroll['class_id']);



  //Task for notification
  $task = array();
  $task['sender_id'] = $_SESSION['id'];
  $task['receiver_id'] = $badge['user_id'];
  $task['message'] = "Congratulations! You were promoted to <b>" . mysqli_real_escape_string($con, $bd['name']) . "</b> in the class <b>" . $class['name'] . "</b>.";
  $task['message'] = mysqli_real_escape_string($con, $task['message']);
  $task['link_ref'] = "/class/" . $class['id'] . "-" . linka($class['name']);
  $task['icon'] = $bd['icon'];
  //Add notification
  add_notification($con, $task);
  echo json_encode($r);
}
?>
