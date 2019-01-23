<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
if(isset($_SESSION['id'])){
  if(isset($_GET['class_id']) AND is_numeric($_GET['class_id'])){
    if(isset($_GET['requester_id']) AND is_numeric($_GET['requester_id'])){
      $class_id = mysqli_real_escape_string($con, $_GET['class_id']);
      $requester_id = mysqli_real_escape_string($con, $_GET['requester_id']);
      $class = find_class($con, $class_id);
      if(user_class_permission($con, $_SESSION['id'], $class['id']) > 0){
          register_class_member($con, $class['id'], $requester_id, 0);
          remove_enrollment_request($con, $class['id'], $requester_id);

          //Register Educoin
          $coin = array();
          $coin['edu_value'] = 1.5;
          $coin['origin'] = 'entered a class';
          $coin['user_id'] = $requester_id;

          register_educoin($con, $coin);
          //Task for notification
  				$task = array();
  				$task['sender_id'] = $_SESSION['id'];
  				$task['receiver_id'] = $requester_id;
  				$task['message'] = "Your enrollment request to join the class <b>" . mysqli_real_escape_string($con, $class['name']) . "</b> was accepted. You're now a member of this class.";
          $task['message'] = mysqli_real_escape_string($con, $task['message']);
          $task['link_ref'] = "/class/" . $class['id'] . "-" . linka($class['name']);
  				$task['icon'] = "/images/accept.png";
  				//Add notification
  				add_notification($con, $task);
      }
    }
  }
}
?>
