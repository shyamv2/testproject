<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
if(isset($_GET['teacher_space']) AND isset($_GET['enrollment_id'])){
  if(is_numeric($_GET['enrollment_id'])){
    $space = mysqli_real_escape_string($con, $_GET['teacher_space']);
    $enrollment_id = mysqli_real_escape_string($con, $_GET['enrollment_id']);
    //EXECUTE query
    $sql = "UPDATE class_members SET teacher_space = '$space' WHERE enrollment_id = $enrollment_id";
    mysqli_query($con, $sql) or die(mysqli_error($con));
  }
}
?>
