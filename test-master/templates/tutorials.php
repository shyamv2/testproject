<!DOCTYPE html>
<!--System file (php functions)-->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php" ?>
<html class="no-js">
<head>
  <title>Help Center | iStudy</title>
  <!--Head file (css and libraries)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/head.php" ?>
</head>
<body bgcolor="white">
  <div class="nav-general-page">
    <img src="/images/logo-min.png">
    <a href="/">Go back to home →</a>
  </div>
  <div class="header-general-page">
    Tutorials
  </div>

  <div style="width: 70%; margin: 20px auto;">
    <p>
      <h1>Get Started with iStudy</h1>
        <br>
        <iframe width="560" height="315" src="https://www.youtube.com/embed/RclNKxC0y1Y" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </p>
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
