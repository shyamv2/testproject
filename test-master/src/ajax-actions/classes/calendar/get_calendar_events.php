<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
if(isset($_SESSION['id'])){
  if(isset($_GET['class_id']) AND is_numeric($_GET['class_id'])){
    $class_id = mysqli_real_escape_string($con, $_GET['class_id']);
    $sql = "SELECT id, title, start, end FROM class_calendar WHERE class_id = $class_id";
    $r = mysqli_query($con, $sql);
    $events = array();
    while($each = mysqli_fetch_assoc($r)){
      $events[] = $each;
    }
    echo json_encode($events);
  }
}
?>
