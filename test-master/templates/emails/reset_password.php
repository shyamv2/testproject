<html>
<?php $user = $data; ?>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
  Hello, <?php echo $user['name'] ?><br><br>

  You've requested to reset your password on iStudy. Please, click the link down below and follow the steps:
  <br><br>
  <a href="http://inventxr.com/templates/reset_password.php?user_id=<?php echo $user['id'] ?>&secret_key=<?php echo $user['key'] ?>">Reset Your Password</a>
  <br><br>
  Thank you so much!
  <br><br>
  Best,
  <br>
  iStudy Team
</body>
</html>
