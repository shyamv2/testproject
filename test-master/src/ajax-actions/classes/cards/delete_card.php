<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
if(isset($_SESSION['id'])){
  if(isset($_GET['card_id']) AND is_numeric($_GET['card_id'])){
    $card_id = mysqli_real_escape_string($con, $_GET['card_id']);
    $card = find_card($con, $card_id);
      if($_SESSION['id'] == $card['author_id']){
        delete_card($con, $card['id']);
      }
    }
}

?>
