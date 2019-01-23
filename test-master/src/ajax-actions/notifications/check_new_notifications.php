<?php
include_once "{$_SERVER['DOCUMENT_ROOT']}/src/functions/connection.php";
include_once "{$_SERVER['DOCUMENT_ROOT']}/src/functions/notifications.php";
include_once "{$_SERVER['DOCUMENT_ROOT']}/src/functions/chat.php";
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
  $receiver_id = $_SESSION['id'];

  //Notifications
  $sql = "SELECT * FROM notifications WHERE receiver_id = $receiver_id AND flag_read = 0 AND flag_loaded = 0";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $result = array();
  while($each = mysqli_fetch_assoc($r)){
    $each['registry'] = translateDateHalf($each['registry']);
    $result['notifications'][] = $each;
  }
  if(isset($result['notifications']) AND count($result['notifications']) > 0){
    $num = count($result['notifications']);
  }
  else{
    $num = 0;
  }
  $result['num_noti'] = intval($num);
  load_notifications($con);


  //Number of Messages
  $sql_m = "SELECT COUNT(*) as num FROM chat_messages WHERE to_id = $receiver_id AND notified = 0"; // SQL for messages
  $r_m = mysqli_query($con, $sql_m) or die(mysqli_error($con)); //Results for messages
  $r_m = mysqli_fetch_assoc($r_m);
  $result['num_msgs'] = intval($r_m['num']);
  set_messages_as_notified($con);


  //Return Values
  if(!empty($result)){
    if($result['num_noti'] > 0 OR $result['num_msgs'] > 0){
      echo "data: " . json_encode($result) . "\n\n";
      ob_flush();
      flush();
    }
  }
?>
