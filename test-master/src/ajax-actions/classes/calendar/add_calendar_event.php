<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
if(isset($_SESSION['id'])){
  if(isset($_POST['class_id']) AND is_numeric($_POST['class_id'])){
    $event['class_id'] = mysqli_real_escape_string($con, $_POST['class_id']);
    $event['author_id'] = $_SESSION['id'];
    $event['title'] = mysqli_real_escape_string($con, $_POST['title']);
    $event['start'] = mysqli_real_escape_string($con, $_POST['start']);
    $event['end'] = mysqli_real_escape_string($con, $_POST['end']);

    $sql = "INSERT INTO class_calendar
    (class_id, author_id, description, title, start, end)
    VALUES
    (
      {$event['class_id']},
      {$event['author_id']},
      '',
      '{$event['title']}',
      '{$event['start']}',
      '{$event['end']}'
    )";
    mysqli_query($con, $sql) or die(mysqli_error($con));
    $result = array();
    $result['id'] = mysqli_insert_id($con);
    echo json_encode($result);
  }
}
?>
