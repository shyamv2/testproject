<!DOCTYPE html>
<!--System file (php functions)-->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php" ?>
<?php
  //Verify if user is logged in
  if(!isset($_SESSION['id'])) {
      header("location: /");
      exit;
  }
?>
<html>
<head>
  <title>Yewno Create | iStudy</title>
  <!--Head file (css and libraries)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/head.php" ?>
</head>
<body>
  <!--MENUS (TOP and LEFT)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/menu-top.php" ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/menu-left.php" ?>

  <iframe src="https://create.yewno.com/?access_token=STANFORD_DESIGNERSHIP_a0430b18aeea" width="100%" height="100%" style="padding-top: 60px;border: 0"></iframe>

  <!--Footer-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/footer.php" ?>
    <script type="text/javascript" src="/assets/libs/ckeditor/ckeditor.js"></script>
  <script>

    var email = CKEDITOR.replace("email", {
      uiColor : '#ffffff',
      placeholder : "Write your email here..."
    });
    function submitSendEmailForm(button){
      $("#sendEmailForm").ajaxSubmit({
        dataType: 'json',
        data: {email : email.getData()},
        url: "/form-actions/send_email.php",
        success: function(data){
          if(data['error'] == true){
            alert(data['msg_error']);
            button.disabled = false;
            button.innerHTML = "<i class='fa fa-paper-plane'></i> Send Email";
          }
          else{
            alert("Email successfully sent!");
            location.reload();
          }
        },
        beforeSend: function(){
          button.disabled = true;
          button.innerHTML = "<i class='fa fa-paper-plane'></i> Sending...";
        },
        error: function(){
          alert("Something went wrong...");
          button.disabled = false;
          button.innerHTML = "<i class='fa fa-paper-plane'></i> Send Email";
        },
        type: "POST"
      })
    }
  </script>
</body>
</html>
