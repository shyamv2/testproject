<?php
include "{$_SERVER['DOCUMENT_ROOT']}/src/functions/system.php";
  if(isset($_SESSION['id'])){
    $receiver_id = $_SESSION['id'];
    $sql = "UPDATE notifications SET
    flag_read = 1
    WHERE receiver_id = $receiver_id";
    $result = mysqli_query($con, $sql) or die(mysqli_error($con));
    $r = array();
    if($r){
      $r['status'] = true;
    }
    else{
      $r['status'] = false;
    }
  }
	echo json_encode($r);
?>
