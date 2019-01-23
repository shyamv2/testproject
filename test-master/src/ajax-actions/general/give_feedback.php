<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
//Error Message
$r = array();
$r['error'] = false;
$r['msg_error'] = "";
if(isset($_SESSION['id'])){
  if(isset($_POST['feedback'])){
      $feedback = mysqli_escape_string($con, $_POST['feedback']);
      if(!empty($feedback)){
        $result = send_feedback($con, $feedback);
      }
      else{
        $r['error'] = true;
        $r['msg_error'] = "Write something to continue!";
      }
      echo json_encode($con);
  }
}
?>
