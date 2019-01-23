<!DOCTYPE html>
<!--System file (php functions)-->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php" ?>
<?php
  if(isset($_GET['user_id']) AND is_numeric($_GET['user_id'])){
    if(isset($_GET['secret_key']) AND !empty(trim($_GET['secret_key']))){
      $user = find_user($con, mysqli_real_escape_string($con, $_GET['user_id']));
      $user['secret_key'] = mysqli_real_escape_string($con, $_GET['secret_key']);
      if(validate_secret_key_for_email($con, $user)){
        $sql_verify = "UPDATE users SET verified = 1 WHERE id = {$user['id']}";
        mysqli_query($con, $sql_verify);
        $sql_delete_secret_keys = "DELETE FROM email_validation WHERE user_id = {$user['id']}";
        mysqli_query($con, $sql_delete_secret_keys);
        if(isset($_SESSION['id']) AND $_SESSION['id'] == $user['id']){
          $_SESSION['verified'] = 1;
        }
        $message = "Your email has been validated! Redirecting...";
      }
      else{
        $message = "Your email could not be validated.";
      }
    }
  }
  else{
    header("location : /"); exit;
  }
?>
<html>
<head>
  <title>Confirm Email | iStudy</title>
</head>
<body>

  <center style="margin-top: 100px"><h3><?php echo $message ?></h3></center>

  <!--Footer-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/footer.php" ?>
    <script type="text/javascript" src="/assets/libs/ckeditor/ckeditor.js"></script>
  <script>
  setTimeout("location.href='/'", 6000);
  </script>
</body>
</html>
