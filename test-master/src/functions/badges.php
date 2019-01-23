<?php
//BADGES

function list_badges($con){
  $sql = "SELECT * FROM badges";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $badges = array();
  while($each = mysqli_fetch_assoc($r)){
    $badges[] = $each;
  }
  return $badges;
}
//Find Badge by Id
function find_badge($con, $badge_id){
  $sql = "SELECT * FROM badges WHERE id = $badge_id LIMIT 1";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_fetch_assoc($r);
}

function get_user_badge($con, $enrollment_id){
  $sql = "SELECT badge_id FROM users_badges WHERE enrollment_id = $enrollment_id AND current = 1 limit 1";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  if(mysqli_num_rows($r) == 0){
    return 0;
  }
  $r = mysqli_fetch_assoc($r);
  return $r['badge_id'];
}
function give_user_badge($con, $badge){
  $sql_verify = "SELECT * FROM users_badges WHERE (enrollment_id = {$badge['enrollment_id']} AND badge_id = {$badge['badge_id']})";
  $r_verify = mysqli_query($con, $sql_verify) or die(mysqli_error($con));
  if(mysqli_num_rows($r_verify) == 0){
    $sql = "INSERT INTO users_badges
    (badge_id, enrollment_id, user_id, giver_id)
    VALUES (
      {$badge['badge_id']},
      {$badge['enrollment_id']},
      {$badge['user_id']},
      {$badge['giver_id']}
    )";
    return mysqli_query($con, $sql) or die(mysqli_error($con));
  }
  $sql_update = "UPDATE users_badges SET current = 1 WHERE enrollment_id = {$badge['enrollment_id']} AND badge_id = {$badge['badge_id']}";
  return mysqli_query($con, $sql_update) or die(mysqli_error($con));
}
function deactivate_past_badges($con, $badge){
  $sql = "UPDATE users_badges SET current = 0 WHERE enrollment_id = {$badge['enrollment_id']} AND user_id = {$badge['user_id']}";
  return mysqli_query($con, $sql) or die(mysqli_error($con));
}

function num_of_badges($con, $user_id, $badge_id){
  $sql = "SELECT COUNT(*) as num FROM users_badges WHERE user_id = $user_id AND badge_id = $badge_id limit 1";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $r = mysqli_fetch_assoc($r);
  return $r['num'];
}
function register_educoin($con, $coin){

  $sql_insert = "INSERT INTO educoins
  (edu_value, origin, user_id)
  VALUES (
    {$coin['edu_value']},
    '{$coin['origin']}',
    {$coin['user_id']}
  )";
  return mysqli_query($con, $sql_insert) or die(mysqli_error($con));
}
function user_num_educoin($con, $user_id){
  $sql = "SELECT SUM(edu_value) as num FROM educoins WHERE user_id = $user_id";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $r = mysqli_fetch_assoc($r);
  return $r['num'];
}
function educoin_history($con, $user_id){
  $sql = "SELECT * FROM educoins WHERE user_id = $user_id ORDER BY id DESC limit 30";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $coins = array();
  while($each = mysqli_fetch_assoc($r)){
    $coins[] = $each;
  }
  return $coins;
}
function eligible_badge($con, $badge){
  $sql = "SELECT * FROM users_badges
    WHERE enrollment_id = {$badge['enrollment_id']} AND badge_id = {$badge['badge_id']}";
    $r = mysqli_query($con, $sql) or die(mysqli_error($con));
    if(mysqli_num_rows($r) > 0){
      return false;
    }
    return true;
}
 ?>
