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
  if(isset($_POST['code'])){

    $requester_id = $_SESSION['id'];
    $code =  mysqli_real_escape_string($con, $_POST['code']);
    $class_id = find_class_id_by_code($con, $code);

    if($class_id == false){
      $r['error'] = true;
      $r['msg_error'] = "Invalid Class Code!";
      echo json_encode($r); exit;
    }

    else{
        if(!is_already_enrolled($con, $requester_id, $class_id)){
          $sql = "INSERT INTO enrollment_requests (requester_id, class_id) VALUES ($requester_id, $class_id)";
          mysqli_query($con, $sql) or die(mysqli_error($con));

          $requester = find_user($con, $_SESSION['id']);
          $class = find_class($con, $class_id);
          //Task for notification
          $task = array();
          $task['sender_id'] = $_SESSION['id'];
          $task['receiver_id'] = $class['author_id'];
          $task['message'] = "<b>" . $requester['name'] . "</b> has requested to join your class <b>" . mysqli_real_escape_string($con, $class['name']) . "</b>";
          $task['message'] = mysqli_real_escape_string($con, $task['message']);
          $task['link_ref'] = "/templates/users-requests.php?class_id=" . $class['id'];
          $task['icon'] = $requester['picture'];
          //Add notification
          add_notification($con, $task);
          echo json_encode($r);
        }

        else{
          $r['error'] = true;
          $r['msg_error'] = "You are already enrolled to that class!";
          echo json_encode($r); exit;
        }
      }
    }
  }


?>
