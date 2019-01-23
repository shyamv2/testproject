<?php
//Register POST CARD
function save_card($con, $post){
  $sql = "INSERT INTO cards
  (enrollment_id, author_id, card)
  VALUES
  (
    {$post['enrollment_id']},
    {$post['author_id']},
    '{$post['card']}'
  )";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_insert_id($con);
}
//Finding post (Card) by ID
function find_card($con, $post_id){
  $sql = "SELECT cards.*, users.name as author_name, users.picture as author_picture FROM cards INNER JOIN users ON cards.author_id = users.id WHERE cards.id = " . $post_id;
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_fetch_assoc($r);
}
function list_cards($con, $enrollment_id){
  $sql = "SELECT cards.*, users.name as author_name, users.picture as author_picture FROM cards INNER JOIN users ON cards.author_id = users.id WHERE cards.enrollment_id = $enrollment_id ORDER BY cards.id DESC limit 50";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $cards = array();
  while($each = mysqli_fetch_assoc($r)){
    $cards[] = $each;
  }
  return $cards;
}

function list_cards_replies($con, $card_id){
  $sql = "SELECT card_replies.*, users.name as author_name FROM card_replies INNER JOIN users ON card_replies.author_id = users.id WHERE card_replies.card_id = $card_id ORDER BY card_replies.id ASC limit 30";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  $card_replies = array();
  while($each = mysqli_fetch_assoc($r)){
    $card_replies[] = $each;
  }
  return $card_replies;
}

//Register CARD REPLY
function save_reply($con, $post){
  $sql = "INSERT INTO card_replies
  (card_id, author_id, reply)
  VALUES
  (
    {$post['card_id']},
    {$post['author_id']},
    '{$post['reply']}'
  )";
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_insert_id($con);
}
//Finding reply (Card) by ID
function find_reply($con, $post_id){
  $sql = "SELECT card_replies.*, users.name as author_name FROM card_replies INNER JOIN users ON card_replies.author_id = users.id WHERE card_replies.id = " . $post_id;
  $r = mysqli_query($con, $sql) or die(mysqli_error($con));
  return mysqli_fetch_assoc($r);
}

function delete_card($con, $card_id){
  $sql = "DELETE FROM cards WHERE id = $card_id";
  return mysqli_query($con, $sql) or die(mysqli_error($con));
}

?>
