<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
if(isset($_SESSION['id'])){
    if(isset($_GET['requester_id']) AND is_numeric($_GET['requester_id'])){
          $requester_id = mysqli_real_escape_string($con, $_GET['requester_id']);
          $association = array();
          $association['account_id'] = $requester_id;
          $association['associated_with_id'] = $_SESSION['id'];
          remove_association_request($con, $association['account_id'], $association['associated_with_id']);

          register_educoin($con, $coin);
          //Task for notification
  				$task = array();
  				$task['sender_id'] = $_SESSION['id'];
  				$task['receiver_id'] = $requester_id;
  				$task['message'] = "We're sorry to inform that your account association has been rejected by <b>" . $_SESSION['name'] . "</b>";
          $task['message'] = mysqli_real_escape_string($con, $task['message']);
          $task['link_ref'] = "/profile/" . $_SESSION['id'] . "-" . linka($_SESSION['name']);
  				$task['icon'] = $_SESSION['picture'];
  				//Add notification
  				add_notification($con, $task);
    }
}
?>
