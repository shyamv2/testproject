<!DOCTYPE html>
<!--System file (php functions)-->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php" ?>
<?php
  if(isset($_SESSION['id'])){
    header("location: /");
    exit;
  }

  if(isset($_GET['user_id']) AND is_numeric($_GET['user_id'])){
    if(isset($_GET['secret_key'])){
      $user = find_user($con, mysqli_real_escape_string($con, $_GET['user_id']));
      $key = mysqli_real_escape_string($con, $_GET['secret_key']);
      if(!validate_password_reset_request($con, $user, $key)){
        header("location: /");
        exit;
      }
    }
    else{
      header("location: /");
      exit;
    }
  }
  else{
    header("location: /");
    exit;
  }
?>
<html class="no-js">
<head>
  <title>Reset your password | iStudy</title>
  <!--Head file (css and libraries)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/head.php" ?>
</head>
<body>
  <img src="/images/logo-min.png" style="display: block; width: 170px; margin: 50px auto">
  <div class="panel" style="width: 500px; margin: 0 auto">
    <div class="panel-header">Reset your password</div>
    <div class="panel-body">
      <form id="reset_password">
        <label>
          <b>New Password</b>
          <input type="password" name="password" placeholder="Type the new password...">
        </label>
        <label>
          <b>Repeat Password</b>
          <input type="password" name="repeat_password" placeholder="Repeat your new password...">
        </label>
        <hr>
        <button type="button" class="btn btn-primary" onclick="resetPassword(this)">Reset Password</button>
      </form>
    </div>
  </div>
  <!--Footer-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/footer.php" ?>
  <script>
  function resetPassword(button){
    $("#reset_password").ajaxSubmit({
      dataType: 'json',
      data: {user_id: <?php echo $user['id'] ?>},
      url: '/src/ajax-actions/users/reset_password.php',
      success: function(data){
        if(data['error'] == true){
          alert(data['msg_error']);
        }
        else{
          alert("Success! Try to log in again!");
          location.reload();
        }
        button.disabled = false;
      },
      beforeSend: function(){
        button.disabled = true;
      },
      error: function(){
        alert("We are sorry. Something went wrong. Please, Try again!");
        button.disabled = false;
      },
      type: 'POST'
    });
  }
  </script>
</body>
</html>
