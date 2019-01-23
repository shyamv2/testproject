<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
$r = array();
$r['error'] = false;
$r['msg_error'] = "";
if(isset($_SESSION['id'])){
  if($_SESSION['verified'] == 0){
    $r['error'] = true;
    $r['msg_error'] = "You need to verify your email in order to complete this action!";
    echo json_encode($r); exit;
  }
  if(isset($_POST['chat_id']) AND is_numeric($_POST['chat_id'])){
    if(isset($_POST['msg']) AND !empty(trim($_POST['msg']))){
      $chat_id = mysqli_real_escape_string($con, $_POST['chat_id']);
      $chat = find_chat($con, $chat_id);
      //msg
      $msg = clearString($con, $_POST['msg']);
      //Get User
      $to_id = 0;
      if($_SESSION['id'] == $chat['user_id_one']){
        $to_id = $chat['user_id_two'];
      }
      else{
        $to_id = $chat['user_id_one'];
      }
      $from_id = $_SESSION['id'];
      $sql = "INSERT INTO chat_messages
      (chat_id, from_id, to_id, msg)
      VALUES
      (
        $chat_id,
        $from_id,
        $to_id,
        '$msg'
      )";
      mysqli_query($con, $sql) or die(mysqli_error($con));
      $msg_id = mysqli_insert_id($con);
      if(isset($_POST['attach']) AND isset($_POST['type'])){
        $link = mysqli_real_escape_string($con, $_POST['attach']);
        $type = mysqli_real_escape_string($con, $_POST['type']);
        $sqlAttach = "INSERT INTO chat_messages_attaches
        (message_id, link, type)
        VALUES
        (
          $msg_id,
          '$link',
          '$type'
        )";
        mysqli_query($con, $sqlAttach) or die(mysqli_error($con));
      }
      update_chat_last_update($con, $chat_id);
      echo json_encode($r);
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "Write Something to send...";
    }
  }
}

?>
