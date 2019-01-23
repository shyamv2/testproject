<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
//Validation
if(isset($_SESSION['id'])){
  if(isset($_GET['class_id']) AND is_numeric($_GET['class_id'])){
    $class = find_class($con, mysqli_real_escape_string($con, $_GET['class_id']));
    if(user_class_permission($con, $_SESSION['id'], $class['id']) > 1){
        //delete class members
        delete_class_members($con, $class['id']);
        //delete class posts (mural)
        delete_class_posts($con, $class['id']);
        //delete enrollment requests to that class
        delete_class_enrollment_requests($con, $class['id']);
        //delete class
        delete_class($con, $class['id']);
    }
  }
}
?>
