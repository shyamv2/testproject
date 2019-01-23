<?php
  //Including all the system and its files
  include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  //Error Array
  $r = array();
  $r['error'] = false;
  $r['msg_error'] = "";

if(isset($_SESSION['id'])){
  if(isset($_POST['enrollment_id']) AND is_numeric($_POST['enrollment_id'])){
    if(isset($_POST['card']) AND !empty(trim($_POST['card']))){
      $post = array();
      $post['enrollment_id'] = mysqli_real_escape_string($con, $_POST['enrollment_id']);
      $post['card'] = mysqli_real_escape_string($con, $_POST['card']);
      $post['author_id'] = $_SESSION['id'];
      //Register Post
      $post_id = save_card($con, $post);
      $card = find_card($con, $post_id);
      $card['registry'] = "just now";
    $r['post'] = <<<EOD
      <!--Card Modal-->
        <div class="card" id="card-$card[id]">
          <!--Card-->
          <div class="card-block">
            <h4 class="card-title">
            <div style="float: right">
              <button class="btn btn-normal" onclick="deleteCard($card[id])"><i class="fa fa-remove"></i></button>
            </div>
            <img src="$card[author_picture]">$card[author_name]</h4>
            <h6 class="card-subtitle mb-2"><small>$card[registry]</small></h6>
            <p class="card-text">$card[card]</p>
          </div>
          <!--List Replies-->
          <ul class="list-group list-group-flush" id="list-replies-$card[id]">
          </ul>
          <!--Reply Form-->
          <div class="card-footer">
            <textarea type="text" placeholder="Type a reply to this card..." id="reply-textarea-$card[id]"></textarea>
            <button type="button" class="btn btn-secondary" style="margin-top: 5px; float: right" onclick="replyCardSubmit(this, $card[id])">Reply</button>
          </div>
        </div>
EOD;
      $r['card_id'] = $card['id'];
      echo json_encode($r); exit;
     }
     else{
       $r['error'] = true;
       $r['msg_error'] = "Write something to continue...";
       echo json_encode($r); exit;
     }
  }
}
?>
