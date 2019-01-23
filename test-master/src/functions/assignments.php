<?php
function list_class_assignments($con, $class_id){
  $sql = "SELECT assignments.*, users.name as author_name, users.picture as author_picture
  FROM assignments INNER JOIN users ON assignments.author_id = users.id
  WHERE assignments.class_id = $class_id ORDER BY assignments.id DESC limit 30";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $assignments = array();
  while($each = mysqli_fetch_assoc($r)){
    $assignments[] = $each;
  }
  return $assignments;
}
function save_assignment($con, $assign){
  $sql = "INSERT INTO assignments
  (class_id, author_id, title, description, educoin_value, deadline)
  VALUES
  (
    {$assign['class_id']},
    {$assign['author_id']},
    '{$assign['title']}',
    '{$assign['description']}',
    {$assign['educoin_value']},
    '{$assign['deadline']}'
  )";
  mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_insert_id($con);
}
function find_user_assignment_grade($con, $assignment_id, $class_id, $member_id){
  $sql = "SELECT grade FROM members_assignments_grades WHERE assignment_id = $assignment_id AND (class_id = $class_id AND user_id = $member_id) limit 1";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  if(mysqli_num_rows($r) > 0){
    $r = mysqli_fetch_assoc($r);
    return $r['grade'];
  }
  return "";
}
function find_user_final_grade($con, $class_id, $member_id){
  $sql = "SELECT grade FROM members_final_grades WHERE class_id = $class_id AND user_id = $member_id limit 1";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  if(mysqli_num_rows($r) > 0){
    $r = mysqli_fetch_assoc($r);
    return $r['grade'];
  }
  return "";
}
function num_submitted_assignments($con, $assign_id){
  $sql = "SELECT COUNT(*) as total FROM submitted_assignments WHERE assignment_id = $assign_id";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $r = mysqli_fetch_assoc($r);
  return $r['total'];
}
function find_assignment($con, $assignment_id){
  $sql = "SELECT * FROM assignments WHERE id = $assignment_id";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_fetch_assoc($r);
}
function get_submitted_assignment($con, $assign_id, $user_id){
  $sql = "SELECT * FROM submitted_assignments WHERE assignment_id = $assign_id AND user_id = $user_id limit 1";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_fetch_assoc($r);
}

function register_assignment_submission($con, $turnedIn){
  $sql = "INSERT INTO submitted_assignments
  (assignment_id, user_id, plain_text)
  VALUES
  (
    {$turnedIn['assignment_id']},
    {$turnedIn['user_id']},
    '{$turnedIn['plain_text']}'
  )";
  return mysqli_query($con, $sql) or die(mysqli_error($con));
}
function list_submitted_assignments($con, $assignment_id){
  $sql = "SELECT submitted_assignments.*, users.name as author_name, users.picture as author_picture FROM submitted_assignments
  LEFT JOIN users ON submitted_assignments.user_id = users.id WHERE submitted_assignments.assignment_id = $assignment_id
  ORDER BY submitted_assignments.id DESC";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $submissions = array();
  while($each = mysqli_fetch_assoc($r)){
    $submissions[] = $each;
  }
  return $submissions;
}

 ?>
