<!DOCTYPE html>
<html class="no-js">
<head>
  <title>iStudy - Home</title>
  <!--Head file (css and libraries)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/head.php" ?>
</head>
<body>
  <div class="intro">
    <div class="intro-content">
      <div class="row">
        <div class="col-sm-8 intro-text">
          <h3>Welcome to</h3>
          <img src="/images/logo-white.png" style="height: 120px">
          <h6>Connecting schools across the globe.</h6>
        </div>
        <div class="col-sm-4">
          <!--Login Panel-->
          <div class="intro-panel">
            <h4 style="text-transform: uppercase; font-size: 14px;"><i class="fa fa-user"></i> Log into your account</h4><hr>
            <form id="loginForm">
              <!--Email-->
              <label>
                <input type="email" class="dark-bg" name="email" placeholder="Email" onkeypress="submitEnterLogin(event)" autocomplete="true">
              </label>
              <!--Password-->
              <label style="margin-bottom: 0">
                <input type="password" class="dark-bg" name="login_password" placeholder="Password" onkeypress="submitEnterLogin(event)">
                <small><a href="javascript:void(0)" data-toggle="modal" data-target="#resetPassword" style="position: relative; top: -5px">Forgot Password?</a></small>
              </label>
              <!--Submit-->
              <button type="button" class="btn btn-primary" style="float: right" onclick="login(this)" id="submit-login">Log in</button>
                <!--Remember me-->
                <label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0" style="width: auto !important">
                  <input type="checkbox" class="custom-control-input" name="remember" value="1" checked>
                  <span class="custom-control-indicator"></span>
                  <span class="custom-control-description" style="font-size: 15px">Remember me</span>
                </label>


            </form>
          </div>
          <!--Sign up Panel-->
          <div class="intro-panel">
            <h4 style="text-transform: uppercase; font-size: 14px;"><i class="fa fa-user-plus"></i> Create a new account</h4><hr>
            <form id="signUpForm" style="padding-bottom: 0">
              <label>
                <input type="text" class="dark-bg" name="signup_name" id="signup_name" placeholder="Name and Last Name">
              </label>
              <label>
                <input type="email" class="dark-bg" name="signup_email" id="signup_email" placeholder="Email">
              </label>
              <div class="btn-group" data-toggle="buttons" style="width: 100%">
                <label class="btn btn-info">
                  <input type="radio" name="type" id="option1" autocomplete="off" value="0"><i class="fa fa-graduation-cap"></i> Student
                </label>
                <label class="btn btn-info">
                  <input type="radio" name="type" id="option2" autocomplete="off" value="1"><i class="fa fa-user-circle"></i> Teacher
                </label>
                <label class="btn btn-info">
                  <input type="radio" name="type" id="option3" autocomplete="off" value="2"><i class="fa fa-child"></i> Parent
                </label>
              </div>
              <label>
                <input type="password" class="dark-bg" name="signup_password" id="signup_password" placeholder="Password">
              </label>
              <div class="row">
                <div class="col-sm-8">
                  <small style="font-size: 10px; line-height: 1px; margin: 7px 0">By signing up to iStudy, you accept our <a href="/legal" target="_blank">Privacy Policy and Terms of Use</a>.</small>
                </div>
                <div class="col-sm-4">
                  <button type="button" class="btn btn-primary" onclick="signUp(this)" style="float: right; font-size: 25px">Sign Up →</button>
                </div>
            </div>
            </form>
          </div>
        </div>
    </div>
  </div>
  <!--Footer-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/footer.php" ?>
  <script>
  function submitEnterLogin(event){
      var key = event.which || event.keyCode;
      if(key == 13){
        var button = document.getElementById('submit-login');
        login(button);
      }
  }
  function sendEmailToResetPassword(button){
    $("#resetPasswordForm").ajaxSubmit({
      dataType: 'json',
      url: '/src/ajax-actions/users/send-email-to-reset-password.php',
      success: function(data){
        if(data['error'] == true){
          alert(data['msg_error']);
        }
        else{
          alert("Check your email and follow the steps provided.");
          $("#resetPassword").modal('toggle');
        }
        button.disabled = false;
        button.innerHTML = "<i class='fa fa-paper-plane'></i> Send";
      },
      beforeSend: function(){
        button.disabled = true;
        button.innerHTML = "<i class='fa fa-paper-plane'></i> Sending...";
      },
      error: function(){
        alert("We are sorry. Something went wrong. Please, Try again!");
        button.disabled = false;
        button.innerHTML = "<i class='fa fa-paper-plane'></i> Send";
      },
      type: 'POST'
    });
  }
  </script>

  <!-- Modal - Reset Password-->
  <div class="modal fade" id="resetPassword">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa fa-envelope"></i> Reset Password</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="resetPasswordForm">
          <div class="modal-body">
            <label>
              We will send you via email the step by step on how to reset your password.<br><br>
              <input type="email" placeholder="Type your email here..." name="email_reset">
            </label>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="sendEmailToResetPassword(this)"><i class="fa fa-paper-plane"></i> Send</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
