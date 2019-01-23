<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
//Error Message
$r = array();
$r['error'] = false;
$r['msg_error'] = "";
if(isset($_SESSION['id'])){
  if($_SESSION['verified'] == 0){
    $r['error'] = true;
    $r['msg_error'] = "You need to verify your email in order to complete this action!";
    echo json_encode($r); exit;
  }
  if(isset($_POST['value']) AND is_numeric($_POST['value'])){
    if(isset($_POST['user_id']) AND is_numeric($_POST['user_id'])){
      $user_to = find_user($con, mysqli_real_escape_string($con, $_POST['user_id']));
      if(!$user_to){
        $r['error'] = true;
        $r['msg_error'] = "This user doesn't exist...";
        echo json_encode($r); exit;
      }
      $value =  mysqli_real_escape_string($con, $_POST['value']);
      if(filter_var($value, FILTER_VALIDATE_FLOAT)){
        $value = abs($value);
        $max_transfer = user_num_educoin($con, $_SESSION['id']);
        if($value > $max_transfer){
          $r['error'] = true;
          $r['msg_error'] = "You don't have this amount to transfer...";
          echo json_encode($r); exit;
        }
        //Register Educoin Left Side
        $coin = array();
        $coin['edu_value'] = $value * (-1);
        $coin['origin'] = 'transfered to ' . mysqli_real_escape_string($con, $user_to['name']);
        $coin['user_id'] = $_SESSION['id'];

        register_educoin($con, $coin);

        //Register Educoin Right Side
        $coin = array();
        $coin['edu_value'] = $value;
        $coin['origin'] = 'transfered by ' . mysqli_real_escape_string($con, $_SESSION['name']);
        $coin['user_id'] = $user_to['id'];

        register_educoin($con, $coin);

        //Notify
        //Task for notification
        $task = array();
        $task['sender_id'] = $_SESSION['id'];
        $task['receiver_id'] = $user_to['id'];
        $task['message'] = "<b>" . mysqli_real_escape_string($con, $_SESSION['name']) . "</b> transfered <b>" . $value . " educoins</b> to your account.";
        $task['message'] = mysqli_real_escape_string($con, $task['message']);
        $task['link_ref'] = "/profile/" . $_SESSION['id'] . "-" . linka($_SESSION['name']);
        $task['icon'] = $_SESSION['picture'];
        add_notification($con, $task);
        echo json_encode($r); exit;

      }
      else{
        $r['error'] = true;
        $r['msg_error'] = "Invalid Value...";
        echo json_encode($r); exit;
      }
    }
  }
  else{
    $r['error'] = true;
    $r['msg_error'] = "Invalid Value...";
    echo json_encode($r); exit;
  }
}
else{
  $r['error'] = true;
  $r['msg_error'] = "Login to continue...";
  echo json_encode($r); exit;
}
?>
