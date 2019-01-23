<html>
<?php $feedback = $data; ?>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
 Feedback Below: <br /><br />

 <?php echo $feedback; ?>

<br /><br />
Sent at: <?php echo date ("Y-m-d H:i:s", time()); ?>
<br />
Session ID: <?php echo $feedback['user_id'] ?>
<br />
</body>
</html>
