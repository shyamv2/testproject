<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
if(isset($_SESSION['id'])){
  if(isset($_POST['enrollment_id']) AND is_numeric($_POST['enrollment_id'])){
    if(isset($_POST['type']) AND is_numeric($_POST['type'])){
      $enrollment_id = mysqli_real_escape_string($con, $_POST['enrollment_id']);
      $type = mysqli_real_escape_string($con, $_POST['type']);
      //Validate type
      if($type >= 0 AND $type <= 2){ //between 0 and 2
          $type_label = "";
          switch ($type){
            case 0:
              $type_label = "a student";
              break;
            case 1:
              $type_label = "a tutor";
              break;
            case 2:
              $type_label = "an administrator";
              break;
          }
      }
      $enrollment = find_enrollment($con, $enrollment_id);
      $class = find_class($con, $enrollment['class_id']);
      if(user_class_permission($con, $_SESSION['id'], $class['id']) > 1){
          $sql = "UPDATE class_members SET
          permission = $type
          WHERE enrollment_id = $enrollment_id";
          mysqli_query($con, $sql) or die(mysqli_error($con));
          //Task for notification
  				$task = array();
  				$task['sender_id'] = $_SESSION['id'];
  				$task['receiver_id'] = $enrollment['member_id'];
  				$task['message'] = "You are now " . $type_label . " in the class <b>" . mysqli_real_escape_string($con, $class['name']) . "</b>. Congratulations!";
          $task['message'] = mysqli_real_escape_string($con, $task['message']);
          $task['link_ref'] = "/class/" . $class['id'] . "-" . linka($class['name']);
  				$task['icon'] = "/images/accept.ico";
  				//Add notification
  				add_notification($con, $task);

      }
    }
  }
}
?>
