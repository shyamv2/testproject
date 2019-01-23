<?php
  include_once "{$_SERVER['DOCUMENT_ROOT']}/src/functions/system.php";
  if(isset($_SESSION['id'])){
    $user_id = $_SESSION['id'];
    $coins_history = educoin_history($con, $user_id);
  }
  else{
  	echo "An error occured!";
  	exit;
  }
?>
<?php if(count($coins_history) > 0) : ?>
<table class="table">
  <thead class="thead-inverse">
    <tr>
      <th scope="col" style="padding-left: 30px;">VALUE</th>
      <th scope="col">ORIGIN</th>
      <th scope="col">REGISTRY</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($coins_history as $coin) : ?>
    <tr>
      <th scope="row"><img src="/images/coin.gif" style="width: 15px; margin-right: 5px;">
        <div style="font-size: 20px; display: inline;"><?php echo $coin['edu_value'] ?></div>
        <div style="font-size: 12px; display: inline;">EDU</div>
      </th>
      <td style="text-transform: capitalize"><?php echo $coin['origin'] ?></td>
      <td><?php echo time_elapsed_string($coin['registry']) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
  <center>No coins earned yet.</center>
<?php endif; ?>
