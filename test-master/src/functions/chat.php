<?php
//Verify if there's already a chat table created
function theres_already_chat($con, $chatwith_id){
  $user_id = $_SESSION['id'];
  $sql = "SELECT * FROM chats WHERE (user_id_one = $user_id AND user_id_two = $chatwith_id) OR (user_id_two = $user_id AND user_id_one = $chatwith_id)";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  if(mysqli_num_rows($r) > 0){
    return true;
  }
  return false;
}
//Create a new chat
function create_chat($con, $chatwith_id){
  $user_id = $_SESSION['id'];
  $sql = "INSERT INTO chats
  (user_id_one, user_id_two)
  VALUES
  (
    $user_id, $chatwith_id
  )";
  return mysqli_query($con, $sql) or die(mysqli_error($con));
}
//List chats
function list_user_chats($con){
  $user_id = $_SESSION['id'];
  $sql = "SELECT * FROM chats WHERE user_id_one = $user_id or user_id_two = $user_id ORDER BY last_update DESC limit 30";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $chats = array();
  while($each = mysqli_fetch_assoc($r)){
    $chats[] = $each;
  }
  return $chats;
}
//Getting chat's last message
function get_chat_last_message($con, $chat_id){
  $sql = "SELECT chat_messages.*, chat_messages_attaches.link as attach, chat_messages_attaches.type as attach_type FROM chat_messages LEFT JOIN chat_messages_attaches ON chat_messages.id = chat_messages_attaches.message_id WHERE chat_messages.chat_id = $chat_id ORDER BY chat_messages.id DESC limit 1";
  $r =  mysqli_query($con, $sql) or die(mysqli_error($con));;
  return mysqli_fetch_assoc($r);
}
//Get chat
function find_chat($con, $chat_id){
  $sql = "SELECT * FROM chats WHERE id = $chat_id limit 1";
  $r =  mysqli_query($con, $sql) or die(mysqli_error($con));;
  return mysqli_fetch_assoc($r);
}
function list_chat_messages($con, $chat_id){
  $sql = "SELECT chat_messages.*, chat_messages_attaches.link as attach, chat_messages_attaches.type as attach_type FROM chat_messages LEFT JOIN chat_messages_attaches ON chat_messages.id = chat_messages_attaches.message_id WHERE chat_messages.chat_id = $chat_id ORDER BY chat_messages.id DESC limit 100";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $msgs = array();
  while($each = mysqli_fetch_assoc($r)){
    $msgs[] = $each;
  }
  return $msgs;
}
function load_messages($con){
  if(isset($_SESSION['id'])){
    $receiver_id = $_SESSION['id'];
    $sql = "UPDATE chat_messages SET loaded_flag = 1 WHERE to_id = $receiver_id";
    return mysqli_query($con, $sql) or die(mysqli_error($con));
  }
  return false;
}
function set_messages_as_read($con, $chat_id){
  if(isset($_SESSION['id'])){
    $receiver_id = $_SESSION['id'];
    $sql = "UPDATE chat_messages SET read_flag = 1 WHERE to_id = $receiver_id";
    return mysqli_query($con, $sql) or die(mysqli_error($con));
  }
  return false;
}
//Number of unread messages of the user (Sessioned)
function unread_messages($con){
  if(isset($_SESSION['id'])){
    $receiver_id = $_SESSION['id'];
    $sql = "SELECT * FROM chat_messages WHERE (to_id = $receiver_id AND read_flag = 0) AND loaded_flag = 0";
    $r = mysqli_query($con, $sql) or die(mysqli_error($con));
    $rows = mysqli_num_rows($r);
    return $rows;
  }
  return false;
}
function user_last_seen($con, $user_id){
  $sql = "SELECT * FROM page_views WHERE user_id = $user_id ORDER BY id DESC limit 1";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $view = mysqli_fetch_assoc($r);
  return $view['registry'];
}
//Last update chat
function update_chat_last_update($con, $chat_id){
  $sql = "UPDATE chats SET last_update = CURRENT_TIMESTAMP WHERE id = $chat_id";
  return mysqli_query($con, $sql) or die(mysqli_error($con));
}
function set_all_chats_as_loaded($con){
  $user_id = $_SESSION['id'];
  $sql = "UPDATE chat_messages SET
  loaded_flag = 1
  WHERE to_id = $user_id";
  return mysqli_query($con, $sql) or die(mysqli_error($con));
}
function num_of_messages_chat($con, $chat_id){
  $sql = "SELECT id FROM chat_messages WHERE chat_id = $chat_id";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_num_rows($r);
}
function find_chat_by_users($con, $user_one, $user_two){
  $sql = "SELECT * FROM chats WHERE (user_id_one = $user_one AND user_id_two = $user_two) OR (user_id_one = $user_two AND user_id_two = $user_one) limit 1";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $chat = mysqli_fetch_assoc($r);
  return $chat['id'];
}
function set_messages_as_notified($con){
  if(isset($_SESSION['id'])){
    $user_id = $_SESSION['id'];
    $sql = "UPDATE chat_messages SET notified = 1 WHERE to_id = $user_id";
    return mysqli_query($con, $sql) or die(mysqli_error($con));
  }
}
?>
