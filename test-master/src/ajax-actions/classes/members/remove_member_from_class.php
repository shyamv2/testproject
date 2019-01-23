<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
if(isset($_SESSION['id'])){
  if(isset($_GET['class_id']) AND is_numeric($_GET['class_id'])){
    if(isset($_GET['class_id']) AND is_numeric($_GET['class_id'])){
      $member_id = mysqli_real_escape_string($con, $_GET['member_id']);
      $class_id = mysqli_real_escape_string($con, $_GET['class_id']);
      $class = find_class($con, $class_id);
      if($class['author_id'] = $_SESSION['id']){
        $sql = "DELETE FROM class_members WHERE member_id = $member_id AND class_id = $class_id";
        mysqli_query($con, $sql) or die(mysqli_error($con));

        //Task for notification
        $task = array();
        $task['sender_id'] = $_SESSION['id'];
        $task['receiver_id'] = $member_id;
        $task['message'] = "We are sorry to inform that you were removed from the class <b>" . mysqli_real_escape_string($con, $class['name']) . "</b> by its admin.";
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
