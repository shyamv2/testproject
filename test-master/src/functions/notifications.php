<?php
//Number of unread notifications of the user (Sessioned)
function unread_notifications($con){
  if(isset($_SESSION['id'])){
    $receiver_id = $_SESSION['id'];
    $sql = "SELECT id FROM notifications WHERE receiver_id = $receiver_id AND flag_read = 0";
    $r = mysqli_query($con, $sql) or die(mysqli_error($con));
    $rows = mysqli_num_rows($r);
    return $rows;
  }
  return false;
}
//List the last 7 notifications of the user
function list_notifications($con){
  if(isset($_SESSION['id'])){
  $receiver_id = $_SESSION['id'];
    $sql = "SELECT * FROM notifications WHERE receiver_id = $receiver_id ORDER BY id DESC limit 7";
    $r = mysqli_query($con, $sql) or die(mysqli_error($con));
    $notifications = array();
    while($each = mysqli_fetch_assoc($r)){
      $notifications[] = $each;
    }
    return $notifications;
  }
  return false;
}
//Create a new notification
function add_notification($con, $task){
  $sql = "INSERT INTO notifications
  (sender_id, receiver_id, message, icon, link_ref, flag_read)
  VALUES
  (
    {$task['sender_id']},
    {$task['receiver_id']},
    '{$task['message']}',
    '{$task['icon']}',
    '{$task['link_ref']}',
    0
  )";
  return mysqli_query($con, $sql) or die(mysqli_error($con));
}
//SET notifications as loaded
function load_notifications($con){
  if(isset($_SESSION['id'])){
    $receiver_id = $_SESSION['id'];
    $sql = "UPDATE notifications SET flag_loaded = 1 WHERE receiver_id = $receiver_id";
    return mysqli_query($con, $sql) or die(mysqli_error($con));
  }
  return false;
}
?>
