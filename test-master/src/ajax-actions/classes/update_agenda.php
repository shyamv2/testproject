<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
if(isset($_GET['agenda']) AND isset($_GET['class_id'])){
  if(is_numeric($_GET['class_id'])){
    $agenda = mysqli_real_escape_string($con, $_GET['agenda']);
    $class_id = mysqli_real_escape_string($con, $_GET['class_id']);
    //EXECUTE query
    $sql = "UPDATE classes SET agenda = '$agenda' WHERE id = $class_id";
    mysqli_query($con, $sql) or die(mysqli_error($con));
  }
}
?>
