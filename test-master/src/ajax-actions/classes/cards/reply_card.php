<?php
  //Including all the system and its files
  include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  //Error Array
  $r = array();
  $r['error'] = false;
  $r['msg_error'] = "";

if(isset($_SESSION['id'])){
  if(isset($_POST['card_id']) AND is_numeric($_POST['card_id'])){
    if(isset($_POST['reply']) AND !empty(trim($_POST['reply']))){
      $post = array();
      $post['card_id'] = mysqli_real_escape_string($con, $_POST['card_id']);
      $post['reply'] = mysqli_real_escape_string($con, $_POST['reply']);
      $post['author_id'] = $_SESSION['id'];
      //Register Post
      $post_id = save_reply($con, $post);
      $card_reply = find_reply($con, $post_id);
      $card_reply['registry'] = "just now";
    $r['reply'] = <<<EOD
    <li class="list-group-item">
      <div class="d-flex w-100 justify-content-between">
        <h4 class="card-title">$card_reply[author_name]</h4>
        <small style="font-size: 13px;">$card_reply[registry]</small>
      </div>
      <p class="mb-1">$card_reply[reply]</p>
    </li>
EOD;
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
