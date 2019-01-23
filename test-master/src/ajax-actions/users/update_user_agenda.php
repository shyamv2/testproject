<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
if(isset($_SESSION['id'])){
  if(isset($_GET['agenda'])){
      $agenda = mysqli_real_escape_string($con, $_GET['agenda']);
      $user_id = $_SESSION['id'];
      //EXECUTE query
      $sql = "UPDATE users SET agenda = '$agenda' WHERE id = $user_id";
      mysqli_query($con, $sql) or die(mysqli_error($con));
  }
}
?>
