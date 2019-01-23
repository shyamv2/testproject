<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/connection.php";
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/chat.php";
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/users.php";
$r = array();
$r['error'] = false;
$r['msg_error'] = "";
if(isset($_SESSION['id'])){
  if(isset($_GET['chat_id']) AND is_numeric($_GET['chat_id'])){
    $chat_id = mysqli_real_escape_string($con, $_GET['chat_id']);
    $chat = find_chat($con, $chat_id);
    //Get User
    $user_id = 0;
    if($_SESSION['id'] == $chat['user_id_one']){
      $user_id = $chat['user_id_two'];
    }
    else if($_SESSION['id'] == $chat['user_id_two']){
      $user_id = $chat['user_id_one'];
    }
    else{
      $r['error'] = true;
      $r['msg_error'] = "No chat was found!";
      echo json_encode($r); exit;
    }
    $user = find_user($con, $user_id);
    set_messages_as_read($con, $chat['id']);
    //Get messages
    $list_chat_messages = array_reverse(list_chat_messages($con, $chat_id));
    $body = "";
    if(!empty($list_chat_messages)){
      foreach($list_chat_messages as $msg){
        $side = "";
        if($msg['from_id'] == $_SESSION['id']){
          $side = "right";
        }
        else{
          $side = "left";
        }
        if(isset($msg['attach']) and !empty($msg['attach'])){
          if($msg['attach_type'] == 'image'){
            $message = "<img src='" . $msg['attach'] . "' class='img-chat'>" . $msg['msg'];
          }
          else{
            $message = "<iframe src='" . $msg['attach'] . "' width='310' height='175' frameborder='0'  allowfullscreen></iframe>" . $msg['msg'];
          }
        }
        else{
          $message = linkify_str(htmlentities($msg['msg']));
        }
        if(!empty($message)){
          $msg_status = ($msg['read_flag'] == 0) ? "Sent" : "Read";
          $body .= "<div data-toggle='tooltip' data-placement='top' title='" . time_elapsed_string($msg['registry']) . " · " . $msg_status . "' class='msg-chat msg-chat-" . $side . "'>" . $message .  "</div>";
        }
      }
    }
    else{
      $body = "<center class='sayhi'>Say <b>Hi</b>!</center>";
    }
      //Returning to JS
      $r['userName'] = $user['name'];
      $r['userPicture'] = $user['picture'];
      $r['messages'] = $body;
      $r['lastSeen'] = time_elapsed_string(user_last_seen($con, $user_id));

      echo json_encode($r);
  }
}

?>
