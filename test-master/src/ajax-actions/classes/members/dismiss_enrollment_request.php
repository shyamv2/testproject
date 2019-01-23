<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
if(isset($_SESSION['id'])){
  if(isset($_GET['class_id']) AND is_numeric($_GET['class_id'])){
    if(isset($_GET['requester_id']) AND is_numeric($_GET['requester_id'])){
      $class_id = mysqli_real_escape_string($con, $_GET['class_id']);
      $requester_id = mysqli_real_escape_string($con, $_GET['requester_id']);
      $class = find_class($con, $class_id);
      if(user_class_permission($con, $_SESSION['id'], $class['id']) > 1){
        remove_enrollment_request($con, $class['id'], $requester_id);

        //Task for notification
        $task = array();
        $task['sender_id'] = $_SESSION['id'];
        $task['receiver_id'] = $requester_id;
        $task['message'] = "We are sorry to inform that your enrollment request to join the class <b>" . mysqli_real_escape_string($con, $class['name']) . "</b> was rejected.";
        $task['message'] = mysqli_real_escape_string($con, $task['message']);
        $task['link_ref'] = "#";
        $task['icon'] = "/images/reject.png";
        //Add notification
        add_notification($con, $task);
      }
    }
  }
}

?>
