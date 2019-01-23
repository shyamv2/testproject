<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
//Error Message
$r = array();
$r['error'] = false;
$r['msg_error'] = "";
if(isset($_SESSION['id'])){
  if(isset($_GET['q'])){
      $term = mysqli_escape_string($con, $_GET['q']);
      $users = search_users($con, $term);
      echo json_encode($users);
  }
}
?>
