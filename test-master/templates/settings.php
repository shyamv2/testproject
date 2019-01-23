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
  <title>Account Settings</title>
  <!--Head file (css and libraries)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/head.php" ?>
</head>
<body>
  <!--MENUS (TOP and LEFT)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/menu-top.php" ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/menu-left.php" ?>

  <!--CONTENT-->
  <div class="content">

    <div class="row row-content">
      <div class="col-sm-12">
      <div class="panel" style="padding: 0">
        <div class="panel-header">Settings</div>
          <div class="panel-body" style="padding: 0">
            <div class="row" style="margin: 0">

                <div class="col-sm-4" style="padding: 0 !important; flex: 1">
                  <div class="personal-space-menu">
                    <ul class="nav-tabs" style="border-bottom: 0">
                      <li class="active" data-toggle="tab" href="#association"><i class="fa fa-link"></i> Account Association</li>
                    </ul>
                  </div>
                </div>
                  <div id="association" class="col-sm-8 tab-pane active" style="padding: 20px !important; flex: 1">
                    Associate your account with someone you trust. Usually, students share their accounts with their parents and counselors.
                    <br><br> <b>Account Association allows the associated person to see the personal space for all your classes.</b>
                    <br><br>
                    <form id="association-request-form">
                      <label>
                        <b>Associate my account with: </b>
                        <select class="js-example-basic-multiple" name="users[]" multiple="multiple"></select>
                      </label>
                      <button type="button" class="btn btn-primary" onclick="sendAssociationRequestSubmit(this)">Send request</button>
                    </form>

                    <br>
                    <form id="invite-association">
                      <label>
                        <b>Invite someone to join iStudy </b>
                        <input type="text" placeholder="Email address..." name="email">
                      </label>
                      <button type="button" class="btn btn-primary" onclick="sendInvitation(this)">Send Email</button>
                    </form>
                  </div>
          </div>
      </div>
    </div>
  </div>

  <!--Footer-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/footer.php" ?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
  <script>
  $('.js-example-basic-multiple').select2({
  templateResult: formatResult,
    templateSelection: formatResult,
    ajax: {
      url: "/src/ajax-actions/messages/search_users.php",
      dataType: 'json',
      data: function(params){
        return{
          q: params.term,
          page: params.page
        }
      },
      processResults: function(data, params){
        params.page = params.page || 1;
        return {
              results: $.map(data, function (data) {
                  return {
                      text: data.name + " | " + data.email,
                      id: data.id,
                      img: data.picture
                  }
          })
            };
      },
      minimumInputLength: 1,
      cache: true
    },
    escapeMarkup: function (markup) { return markup; },
    placeholder: "Search a user, usually your parent or counselor...",
  });
  function formatResult(data) {
    var html = data.text;
    if(data.img != undefined){
        html = "<img style='width: 25px; height: 25px; margin-right: 10px; border-radius: 100%' src='"+data.img+"'/>" + html;
    }
  return html;
  }

  function sendAssociationRequestSubmit(button){
    $("#association-request-form").ajaxSubmit({
      dataType: 'json',
      url: '/src/ajax-actions/users/register_association_request.php',
      success: function(data){
        if(data['error'] == true){
          alert(data['msg_error']);
        }
        else{
          showMsg("Success! A request will be sent to them soon!");
          setTimeout("location.reload()", 6000);
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
  function sendInvitation(button){
    var email = $("#invite-association input").val();
    $("#invite-association").ajaxSubmit({
      dataType: 'json',
      data: {email: email},
      url: '/src/ajax-actions/users/send-invitation.php',
      success: function(data){
        if(data['error'] == true){
          alert(data['msg_error']);
        }
        else{
          showMsg("Email Successfully Sent!");
          $("#invite-association input").val("");
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
