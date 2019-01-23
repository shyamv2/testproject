<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  if(isset($_SESSION['id'])){
    $user_id = mysqli_real_escape_string($con, $_SESSION['id']);
    $list_association_requests = list_association_requests($con, $user_id);
  }else{
    echo "Login to continue..."; exit;
  }
?>
<div class="list-group">
  <?php foreach($list_association_requests as $request) : ?>
    <li href="/profile/<?php echo $request['id'] . '-' . linka($request['name']) ?>" class="list-group-item justify-content-between list-profile" id="request-<?php echo $request['id'] ?>"
      target="_blank">
      <div style="font-size: 13px;"><img src="<?php echo $request['picture'] ?>">
        <?php echo $request['name'] ?></b> - <?php echo $request['email'] ?> | <?php echo translateDateHalf($request['registry']) ?></div>
      <div>
        <div class="btn-group" role="group" aria-label="Basic example" style="float: right">
          <button type="button" class="btn btn-secondary" onclick="dismissAssociationRequest(<?php echo $request['id'] ?>)">Dismiss</button>
          <button type="button" class="btn btn-primary" onclick="acceptAssociationRequest(<?php echo $request['id'] ?>)">Accept</button>
        </div>
      </div>
    </li>
  <?php endforeach; ?>
  <?php if(empty($list_association_requests)) : ?>
    <center>
      <img src="/images/no-content.png" style="width: 110px;"><br>
      You have no account association requests...
    </center>
  <?php endif; ?>
</div>
