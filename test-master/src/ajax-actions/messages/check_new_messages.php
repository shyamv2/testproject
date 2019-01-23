<?php
include_once "{$_SERVER['DOCUMENT_ROOT']}/src/functions/connection.php";
include_once "{$_SERVER['DOCUMENT_ROOT']}/src/functions/chat.php";
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
  $receiver_id = $_SESSION['id'];
  $sql = "SELECT chat_messages.*, chat_messages_attaches.link as attach, chat_messages_attaches.type as attach_type FROM chat_messages LEFT JOIN chat_messages_attaches ON chat_messages.id = chat_messages_attaches.message_id WHERE chat_messages.to_id = $receiver_id AND chat_messages.loaded_flag = 0";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $result = array();
  while($each = mysqli_fetch_assoc($r)){
    $each['registry'] = translateDateHalf($each['registry']);
    if(isset($each['attach']) and !empty($each['attach'])){
      if($each['attach_type'] == 'image'){
        $each['msg'] = "<img src='" . $each['attach'] . "' class='img-chat'>" . $each['msg'];
      }
      else{
        $each['msg'] = "<iframe src='" . $each['attach'] . "' width='310' height='175' frameborder='0'  allowfullscreen></iframe>" . $each['msg'];
      }
    }
    else{
      $each['msg'] = linkify_str($each['msg']);
    }
    $result[] = $each;
  }
  load_messages($con);
  if(!empty($result)){
    echo "data: " . json_encode($result) . "\n\n";
    flush();
  }
?>
