<?php
//Register POST
function register_post($con, $post){
  $sql = "INSERT INTO posts
  (target, target_id, alternative_target_id, author_id, post)
  VALUES
  (
    '{$post['target']}',
    {$post['target_id']},
    {$post['alternative_target_id']},
    {$post['author_id']},
    '{$post['post']}'
  )";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_insert_id($con);
}
//Finding post (mural) by ID
function find_post($con, $post_id){
  $sql = "SELECT posts.*, users.name as author_name, users.picture as author_picture FROM posts INNER JOIN users ON posts.author_id = users.id WHERE posts.id = " . $post_id;
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_fetch_assoc($r);
}
function list_posts($con, $target, $target_id, $alternative_target_id = 0, $limit = 3){
  $sql = "SELECT posts.*, users.name as author_name, users.picture as author_picture FROM posts INNER JOIN users ON posts.author_id = users.id WHERE (posts.target_id = $target_id and posts.alternative_target_id = $alternative_target_id) and posts.target = '$target' ORDER BY posts.id DESC limit $limit";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $posts = array();
  while($each = mysqli_fetch_assoc($r)){
    $posts[] = $each;
  }
  return $posts;
}
function list_user_posts($con, $user_id){
  $sql = "SELECT posts.*, users.name as author_name, users.picture as author_picture FROM posts INNER JOIN users ON posts.author_id = users.id WHERE posts.author_id = $user_id and posts.target = 'profile' ORDER BY posts.id DESC limit 50";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $posts = array();
  while($each = mysqli_fetch_assoc($r)){
    $posts[] = $each;
  }
  return $posts;
}
function list_user_posts_for_class($con, $user_id, $class_id){
  $sql = "SELECT posts.*, users.name as author_name, users.picture as author_picture FROM posts INNER JOIN users ON posts.author_id = users.id WHERE posts.author_id = $user_id and (posts.target = 'class' and posts.target_id = $class_id) ORDER BY posts.id DESC limit 50";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $posts = array();
  while($each = mysqli_fetch_assoc($r)){
    $posts[] = $each;
  }
  return $posts;
}
function list_post_responses($con, $post_id){
  $sql = "SELECT post_responses.*, users.name as author_name, users.picture as author_picture FROM post_responses INNER JOIN users ON post_responses.author_id = users.id WHERE post_responses.post_id = $post_id ORDER BY post_responses.id ASC limit 30";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $responses = array();
  while($each = mysqli_fetch_assoc($r)){
    $responses[] = $each;
  }
  return $responses;
}
function is_author_of_post($con, $post_id, $user_id){
  $sql = "SELECT * FROM posts WHERE id = $post_id AND author_id = $user_id";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  if(mysqli_num_rows($r) > 0){
    return true;
  }
  return false;
}
function is_author_of_comment($con, $comment_id, $user_id){
  $sql = "SELECT * FROM post_responses WHERE id = $comment_id AND author_id = $user_id";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  if(mysqli_num_rows($r) > 0){
    return true;
  }
  return false;
}
function delete_post($con, $post_id){
  $sql = "DELETE FROM posts WHERE id = $post_id";
  return mysqli_query($con, $sql) or die(mysqli_error($con));
}
function delete_comment($con, $comment_id){
  $sql = "DELETE FROM post_responses WHERE id = $comment_id";
  return mysqli_query($con, $sql) or die(mysqli_error($con));
}
function find_post_response($con, $response_id){
  $sql = "SELECT * FROM post_responses WHERE id = $response_id";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_fetch_assoc($r);
}
function list_post_attaches($con, $post_id){
  $sql = "SELECT * FROM posts_files WHERE post_id = $post_id";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $attaches = array();
  while($each = mysqli_fetch_assoc($r)){
    $attaches[] = $each;
  }
  return $attaches;
}
function find_post_links($con, $post_id){
  $sql = "SELECT * FROM posts_links WHERE post_id = $post_id limit 1";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_fetch_assoc($r);
}
function list_reactions($con){
  $sql = "SELECT * FROM reactions";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $reactions = array();
  while($each = mysqli_fetch_assoc($r)){
    $reactions[] = $each;
  }
  return $reactions;
}
function number_of_reactions_post($con, $reaction, $post_id){
  $sql = "SELECT * FROM posts_reactions WHERE post_id = $post_id AND reaction = $reaction";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_num_rows($r);
}
function find_reaction($con, $reaction_id){
  $sql = "SELECT * FROM reactions WHERE id = $reaction_id";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_fetch_assoc($r);
}
function user_reaction_to_post($con, $post_id){
  $sql = "SELECT reactions.* FROM posts_reactions INNER JOIN
  reactions ON posts_reactions.reaction = reactions.id
  WHERE posts_reactions.user_id = {$_SESSION['id']} and
  posts_reactions.post_id = $post_id limit 1";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_fetch_assoc($r);
}
function validate_post($con, $post){
  $user_id = $_SESSION['id'];
  $sql = "SELECT * FROM posts WHERE (post = '$post' AND author_id = $user_id) AND registry > (now() - INTERVAL 11 SECOND) ORDER BY id DESC limit 1";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  if(mysqli_num_rows($r) > 0){
    return false;
  }
  return true;
}
?>
