<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
if(isset($_SESSION['id'])){
  if(isset($_GET['class_id']) AND is_numeric($_GET['class_id'])){
    $member_id = $_SESSION['id'];
    $class_id = mysqli_real_escape_string($con, $_GET['class_id']);
    $sql = "DELETE FROM class_members WHERE member_id = $member_id AND class_id = $class_id";
    mysqli_query($con, $sql) or die(mysqli_error($con));

    $user = find_user($con, $_SESSION['id']);
    $class = find_class($con, $class_id);
    //Task for notification
    $task = array();
    $task['sender_id'] = $_SESSION['id'];
    $task['receiver_id'] = $class['author_id'];
    $task['message'] = "<b>" . $user['name'] . "</b> has left your class <b>" . mysqli_real_escape_string($con, $class['name']) . "</b>";
    $task['message'] = mysqli_real_escape_string($con, $task['message']);
    $task['link_ref'] =  "/class/" . $class['id'] . "-" . linka($class['name']);
    $task['icon'] = $user['picture'];
    //Add notification
    add_notification($con, $task);
  }
}

?>
