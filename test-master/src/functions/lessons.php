<?php
//Create lesson
function save_lesson($con, $lesson){
    $lesson['author_id'] = $_SESSION['id'];
    $sql = "INSERT INTO class_lessons
    (class_id ,author_id, name, skills, type, cover_link, article)
    VALUES
    (
      {$lesson['class_id']},
      {$lesson['author_id']},
      '{$lesson['name']}',
      '{$lesson['skills']}',
      {$lesson['type']},
      '{$lesson['cover_link']}',
      '{$lesson['article']}'
    )";
    mysqli_query($con, $sql) or die(mysqli_error($con));
    $lesson_id = mysqli_insert_id($con);
    return $lesson_id;
}
//List Lessons by Class Id
function list_class_lessons($con, $class_id){
  $sql = "SELECT class_lessons.*, users.id as author_id FROM class_lessons LEFT JOIN users ON class_lessons.author_id = users.id WHERE class_lessons.class_id = $class_id ORDER BY class_lessons.id DESC";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $lessons = array();
  while($each = mysqli_fetch_assoc($r)){
    $lessons[] = $each;
  }
  return $lessons;
}
function  find_lesson($con, $lesson_id){
  $sql = "SELECT * FROM class_lessons WHERE id = $lesson_id limit 1";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_fetch_assoc($r);
}
function delete_lesson($con, $lesson_id){
  $sql = "DELETE FROM class_lessons WHERE id = " . $lesson_id;
  mysqli_query($con, $sql) or die(mysqli_error($con));
}
//edit lesson
function edit_lesson($con, $lesson){
  $sql = "UPDATE class_lessons SET
  name = '{$lesson['name']}',
  skills = '{$lesson['skills']}',
  type = {$lesson['type']},
  cover_link = '{$lesson['cover_link']}',
  article = '{$lesson['article']}'
  WHERE id = " . $lesson['id'];
  return mysqli_query($con, $sql) or die(mysqli_error($con));
}
 ?>
