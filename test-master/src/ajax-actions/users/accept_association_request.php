<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
if(isset($_SESSION['id'])){
    if(isset($_GET['requester_id']) AND is_numeric($_GET['requester_id'])){
          $requester_id = mysqli_real_escape_string($con, $_GET['requester_id']);
          $association = array();
          $association['account_id'] = $requester_id;
          $association['associated_with_id'] = $_SESSION['id'];
          if(!no_existant_association_request($con, $association['account_id'], $association['associated_with_id'])){
            register_account_association($con, $association);
            remove_association_request($con, $association['account_id'], $association['associated_with_id']);
          }

          //Register Educoin
          $coin = array();
          $coin['edu_value'] = 1.5;
          $coin['origin'] = 'associated account';
          $coin['user_id'] = $requester_id;

          register_educoin($con, $coin);
          //Task for notification
  				$task = array();
  				$task['sender_id'] = $_SESSION['id'];
  				$task['receiver_id'] = $requester_id;
  				$task['message'] = "Your account association has been accepted by <b>" . $_SESSION['name'] . "</b>";
          $task['message'] = mysqli_real_escape_string($con, $task['message']);
          $task['link_ref'] = "/profile/" . $_SESSION['id'] . "-" . linka($_SESSION['name']);
  				$task['icon'] = $_SESSION['picture'];
  				//Add notification
  				add_notification($con, $task);
    }
}
?>
