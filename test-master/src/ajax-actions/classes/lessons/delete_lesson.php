<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
//Validation
if(isset($_SESSION['id'])){
  if(isset($_GET['lesson_id']) AND is_numeric($_GET['lesson_id'])){
    $lesson = find_lesson($con, mysqli_real_escape_string($con, $_GET['lesson_id']));
    if($lesson['author_id'] == $_SESSION['id']){
        //delete class
        delete_lesson($con, $lesson['id']);
    }
  }
}
?>
