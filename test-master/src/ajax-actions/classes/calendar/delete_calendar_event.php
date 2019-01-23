<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
if(isset($_SESSION['id'])){
  if(isset($_GET['id']) AND is_numeric($_GET['id'])){
    $event_id = mysqli_real_escape_string($con, $_GET['id']);

    $sql = "DELETE FROM class_calendar
    WHERE id = $event_id";
    mysqli_query($con, $sql) or die(mysqli_error($con));
  }
}
?>
