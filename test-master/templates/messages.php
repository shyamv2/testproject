<!DOCTYPE html>
<!--System file (php functions)-->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php" ?>
<?php
  //Verify if user is logged in
  if(!isset($_SESSION['id'])) {
      header("location: /");
      exit;
  }
  if(isset($_GET['chatwith']) AND is_numeric($_GET['chatwith'])){
    $chatwith_id = mysqli_real_escape_string($con, $_GET['chatwith']);
    if(!theres_already_chat($con, $chatwith_id)){
      create_chat($con, $chatwith_id);
    }
    else{
      $chat_id = find_chat_by_users($con, $_SESSION['id'], $chatwith_id);
      update_chat_last_update($con, $chat_id);
    }
  }
  $list_chats = list_user_chats($con);
  //Set all chats as loaded in order to not notificate the user two times (static and ajax)
  set_all_chats_as_loaded($con);
?>
<html>
<head>
  <title>Messages | iStudy</title>
  <!--Head file (css and libraries)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/head.php" ?>
  <style>
  .emojionearea{
    height: 40px !important;
  }
  .emojionearea-editor{
    height: 40px !important;
    padding: 8px 0 !important;
  }
  .emojionearea .emojionearea-button>div.emojionearea-button-open{
        background-position: 0 -20px !important;
  }
  </style>
</head>
<body style="overflow: auto; height: 100%">
  <!--MENUS (TOP and LEFT)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/menu-top.php" ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/menu-left.php" ?>

  <!--CONTENT-->
  <div class="content" style="height: 100%; bottom: 50px">

    <div class="row row-content" style="height: 100%;">
      <?php
      if(count($list_chats) == 0 AND !isset($_GET['chatwith'])){
        echo "<div class='alert alert-warning' role='alert'>You don't have any message. Find someone and start chatting right now!</div>";
        exit;
      }
      ?>
      <!--LEFTSIDE-->
      <div class="col-sm-4">
        <div class="panel" style="padding: 0; position: relative">
        <div class="panel-header">
          <!--New Chat-->
          <div style="float: right; position:relative; top: -5px">
            <button type="button" onclick="newChatSearch();" data-toggle="modal" data-target="#editProfileInfoModal" class="panel-header-button" style="font-size: 20px;"><i class="fa fa-edit"></i></button>
          </div>
          Messages
        </div>
          <div class="panel-body list-of-chats">
          <div class="list-group">
          <?php $i = 1; ?>
          <?php foreach($list_chats as $chat) : ?>
          <?php
            $last_message = get_chat_last_message($con, $chat['id']);
            if(empty($last_message)){
                $last_message['msg'] = "Say Hi";
                $last_message['registry'] = date('Y-m-d H:i:s');
            }
            $user_id = 0;
            if($_SESSION['id'] == $chat['user_id_one']){
              $user_id = $chat['user_id_two'];
            }
            else{
              $user_id = $chat['user_id_one'];
            }
            $user = find_user($con, $user_id);
            //Number of messages in this chat
            $num_of_msgs = num_of_messages_chat($con, $chat['id']);
            if($num_of_msgs == 0){
              if(!isset($_GET['chatwith'])){
                continue;
              }
              else{
                if($_GET['chatwith'] != $user['id']){
                  continue;
                }
              }
            }
          ?>
          <!--Chat-List-->
          <a href="#" class="list-group-item list-group-item-action flex-column align-items-start<?php echo ($i == 1) ? ' active' : '' ?>" onclick="switchChats(this, <?php echo $chat['id'] ?>)" id="chat-<?php echo $chat['id'] ?>">
            <div class="d-flex w-100 justify-content-between">
              <!--HEAD - Photo and Name-->
              <h6 class="mb-1" style="font-size: 14px">
                <!--User Picture-->
                <img src="<?php echo $user['picture'] ?>" style="width: 30px; height: 30px; border-radius: 100%">
                <!--User Name-->
                <?php echo $user['name'] ?>
              </h6>
              <!--Last Message Date-->
              <small style="font-size: 11px" id="time-last-msg-<?php echo $chat['id'] ?>"><?php echo time_elapsed_string($last_message['registry']) ?></small>
              <!--New Message div-->
              <div class="new-msg" id="new-msg-<?php echo $chat['id'] ?>" style="display: <?php echo ($chat['read_flag'] == 1) ? 'inline-block' : 'none' ?>"></div>
            </div>
            <!--Last message sample-->
            <?php if(isset($last_message['attach']) AND !empty($last_message['attach'])){
              if($last_message['attach_type'] == 'image'){
                $last_message['msg'] = "<i class='fa fa-image'></i> Image";
              }
              else{
                $last_message['msg'] = "<i class='fa fa-video-camera'></i> Video";
              }
            } ?>
            <small class="mb-1 last-msg-chat" id="last-msg-<?php echo $chat['id'] ?>"><?php echo str_limit(strip_tags($last_message['msg']), 30) ?></small>
          </a>
          <?php
          if($i == 1){
            $first_chat = $chat['id'];
          }
          $i++
          ?>
          <?php endforeach; ?>
          </div>
          </div>
        </div>
      </div>


      <!--RIGHTSIDE-->
      <div class="col-sm-8" style="padding-left: 2px;">
        <div class="panel panel-chat">
          <div class="loading-panel">
            <img src="/images/loading.svg">
          </div>
          <!--PANEL CHAT HEAD - CHAT-->
          <div class="panel-chat-head" id="chat-head-conversation">
            <img src="/images/profile_picture.jpg" id="userPicture">
            <div class="panel-chat-head-id">
              <h6 id="userName">Loading...</h6>
              <small id="lastSeen">Loading...</small>
            </div>
          </div>
          <!--PANEL CHAT HEAD - Search new chat-->
          <div class="panel-chat-head" id="chat-head-search" style="display: none">
            <select class="js-example-basic-multiple" name="user[]" multiple="multiple" style="width: 90% !important"></select>
            <div style="float: right"><button class="panel-header-button" id="close-search">X</button></div>
          </div>
          <!--PANEL CHAT BODY-->
          <div class="panel-chat-body">
          </div>
          <!--PANEL CHAT FOOTER-->
          <fieldset class="panel-chat-footer">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Type your message here..." id="inputMessage">
              <span class="input-group-btn">
                <button class="btn btn-secondary" type="button" id="attachImageButton" data-toggle="modal" data-target="#attachImage"><i class="fa fa-image"></i></button>
              </span>
              <span class="input-group-btn">
                <button class="btn btn-secondary" type="button" id="attachYoutubeButton" data-toggle="modal" data-target="#attachYoutube"><i class="fa fa-youtube"></i></button>
              </span>
              <span class="input-group-btn">
                <button class="btn btn-primary" type="button" id="buttonSubmitMessage"><i class="fa fa-paper-plane"></i></button>
              </span>
            </div>
          </fieldset>
        </div>
      </div>
    </div>
    <br>
  <!--Footer-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/footer.php" ?>

  <link rel="stylesheet" href="/assets/libs/emojionearea/dist/emojionearea.min.css">
  <script type="text/javascript" src="/assets/libs/emojionearea/dist/emojionearea.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
  <script>
  $(".messages-number").remove();
  function switchChats(click, chatId){
    $(".list-group-item").removeClass("active");
    $(click).addClass("active");
    loadChat(chatId);
  }
  function submitEnter(e, chatId){
      var key = e.which || e.keyCode;
      console.log("It's working | " + key);
      if(key == 13){
        sendMessage(chatId);
      }
  }
  var openChat;
  var msgArea;
  $(document).ready(function(){
    scrollDown();
    loadChat(<?php echo $first_chat ?>);
    $('[data-toggle="tooltip"]').tooltip();
    msgArea = $("#inputMessage").emojioneArea({
      shortnames: true,
      saveEmojisAs: 'unicode'
    });
    $(".img-chat").click(function(){
        $("#modalImage").css("display", "block");
        modalImg.src = this.src;
    });
  });
  function loadChat(chatId){
    $.ajax({
      dataType: "json",
      url: "/src/ajax-actions/messages/load_chat.php?chat_id=" + chatId,
      success: function(data){
        $("#chat-head-conversation").css("display", "block");
        $("#chat-head-search").css("display", "none");
        $(".panel-chat-footer").prop("disabled", false);
        $("#close-search").attr("onclick", "loadChat(" + chatId + ")");
        $("#userName").html(data.userName);
        $("#userPicture").attr("src", data.userPicture);
        $("#lastSeen").html("Last Seen " + data.lastSeen);
        //$("#lastSeen").html(data.lastSeen);
        $(".panel-chat-body").html(data.messages);
        $('[data-toggle="tooltip"]').tooltip();
        //Press enter to submit
        $(".emojionearea-editor").attr("onkeydown", "submitEnter(event, " + chatId + ")");
        $(".emojionearea-editor").html(""); //Clean the textarea
        $(".emojionearea-editor").focus(); //Give focus to the textarea
        $("#buttonSubmitMessage").attr("onclick", "sendMessage(" + chatId + ")");
        $("#new-msg-" + chatId).css("display", "none");
        scrollDown();
        $(".loading-panel").css("display", "none");
      },
      beforeSend: function(){
        $("#panel-chat-body").html("Loading...");
        $(".loading-panel").css("display", "block");
      },
      error: function(){
        alert("Something went wrong...");
      },
      type: "GET"
    });
    openChat = chatId;
  }
  //Send Message
  function sendMessage(chatId){
    var msg = msgArea.data("emojioneArea").getText();
    $.ajax({
      dataType: "json",
      data: {chat_id: chatId, msg: msg},
      url: "/src/ajax-actions/messages/send_message.php",
      success: function(data){
        if(data['error'] == true){
          showMsg(data['msg_error']);
        }
        else{
          $(".msg-label").remove();
          $(".panel-chat-body").append("<div class='msg-label'>Message Sent</div>");
          $("#last-msg-" + chatId).html(msg);
          $("#time-last-msg-" + chatId).html("just now");
          scrollDown();
        }
      },
      beforeSend: function(){
        if(msg.length > 0){
          $(".panel-chat-body").append("<div class='msg-chat msg-chat-right'>" + msg + "</div>");
           msgArea.data("emojioneArea").setText("");
          scrollDown();
          $(".sayhi").remove();
          $("#chat-" + chatId).prependTo(".list-group");
        }
      },
      error: function(data){
        alert("Something went wrong...");
      },
      type: "POST"
    })
  }
  function scrollDown(){
    var divMsgs = $(".panel-chat-body");
    var height = divMsgs[0].scrollHeight;
    divMsgs.scrollTop(height);
  }
  //Server-Sent Event to get new nomtifications
  var getMessages = new EventSource("/src/ajax-actions/messages/check_new_messages.php");
  getMessages.onmessage = function(event){
  	var data = JSON.parse(event.data);
    data.forEach(function(msg) {
      $("#chat-" + msg['chat_id']).prependTo(".list-group");
      if(msg['attach_type'] == 'image'){
        var lastMsg = "<i class='fa fa-image'></i> Image";
      }
      else if(msg['attach_type'] == 'youtube'){
        var lastMsg = "<i class='fa fa-video-camera'></i> Video";
      }
      else{
        var lastMsg = msg['msg'];
      }
			if(msg['chat_id'] == openChat){
        $(".panel-chat-body").append("<div class='msg-chat msg-chat-left'>" + msg['msg'] + "</div>");
        $("#last-msg-" + msg['chat_id']).html(lastMsg);
        $(".msg-label").remove();
        scrollDown();
      }
      else if($("#last-msg-" + msg['chat_id']).length){
        $("#last-msg-" + msg['chat_id']).html(lastMsg);
        $("#new-msg-" + msg['chat_id']).css("display", "inline-block");
      }
      else{
        location.reload();
      }
      $("#time-last-msg-" + msg['chat_id']).html("just now");
		});
  }
  function sendMessageAttach(button, type){
    var chatId = openChat;
    if(type == 'image'){
      var link = $("#inputImage").val();
      var lastMsg = "<i class='fa fa-image'></i> Image";
    }
    else{
      var link = $("#inputYoutube").val();
      var vidId = link.substr(link.length - 11);
      var link = "https://www.youtube.com/embed/" + vidId;
      var lastMsg = "<i class='fa fa-video-camera'></i> Video";
    }
    var msg =  " ";
      $.ajax({
        dataType: "json",
        data: {chat_id: chatId, msg: msg, attach: link, type: type},
        url: "/src/ajax-actions/messages/send_message.php",
        success: function(data){
          $(".msg-label").remove();
          $(".panel-chat-body").append("<div class='msg-label'>Message Sent</div>");
          $("#last-msg-" + chatId).html(lastMsg);
          $("#time-last-msg-" + chatId).html("just now");
          scrollDown();
        },
        beforeSend: function(){
          if(msg.length > 0){
            if(type == 'image'){
              $(".panel-chat-body").append("<div class='msg-chat msg-chat-right'><img src='" + link + "'></div>");
              $("#attachImage").modal("toggle");
            }
            else{
              $(".panel-chat-body").append("<div class='msg-chat msg-chat-right'><iframe src='" + link + "' width='310' height='175' frameborder='0'  allowfullscreen></iframe></div>");
              $("#attachYoutube").modal("toggle");
            }
            msgArea.data("emojioneArea").setText("");
            scrollDown();
            $(".sayhi").remove();
            $("#chat-" + chatId).prependTo(".list-group");
          }
        },
        error: function(data){
          alert("Something went wrong...");
        },
        type: "POST"
      })
  }
  function previewImage(image){
    $(".previewImage").css("display", "block");
    $(".previewImage img").css("display", "block");
    $(".previewImage img").attr("src", image);
    document.getElementById("submitImage").disabled = false;
  }
  function previewYoutube(link){
    var vidId = link.substr(link.length - 11);
    var embedLink = "https://www.youtube.com/embed/" + vidId;
    $(".previewYoutube").css("display", "block");
    $(".previewYoutube iframe").css("display", "block");
    $(".previewYoutube iframe").attr("src", embedLink);
    document.getElementById("submitYoutube").disabled = false;
  }

  // Get the modal
var modal = document.getElementById('modalImage');

// Get the image and insert it inside the modal - use its "alt" text as a caption
var modalImg = document.getElementById("img01");
$(".img-chat").click(function(){
    $("#modalImage").css("display", "block");
    modalImg.src = this.src;
});

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

//Start new chat
function newChatSearch(){
  $("#chat-head-conversation").css("display", "none");
  $("#chat-head-search").css("display", "block");
  $(".panel-chat-body").html("<center>Say <b>Hi!</b></center>");
  $(".panel-chat-footer").prop("disabled", true);
}

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
		              	    text: data.name,
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
      placeholder: "Search a new user to chat by name or email...",
      maximumSelectionLength: 1
  	});
    $('.js-example-basic-multiple').on('select2:select', function (e) {
      var data = e.params.data;
      location.href = "/messages?chatwith=" + data.id;
    });
 function formatResult(data) {
  	var html = data.text;
		if(data.img != undefined){
		    html = "<img style='width: 30px; height: 30px; margin-right: 10px' src='"+data.img+"'/>" + html;
		}
	return html;
}
  </script>
</body>

<!-- Modal - Attach Image-->
<div class="modal fade" id="attachImage">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-image"></i> Attach Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Paste the link to your image link here:
        <input type="link" class="form-control" placeholder="http://" onchange="previewImage(this.value)" id="inputImage" onkeyup="previewImage(this.value)">
        <div class="previewImage">
          <br><b>Preview</b>
          <div class="error"></div>
          <img src="">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="submitImage" onclick="sendMessageAttach(this, 'image')" disabled><i class="fa fa-paper-plane"></i> Send</button>
      </div>
    </div>
  </div>
</div>
  <!-- Modal - Attach Youtube Video-->
<div class="modal fade" id="attachYoutube" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-youtube"></i> Attach Youtube Video</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Paste a youtube video link here:
        <input type="link" class="form-control" placeholder="http://youtube.com/" id="inputYoutube" onchange="previewYoutube(this.value)" onkeyup="previewYoutube(this.value)">
        <div class="previewYoutube">
          <br><b>Preview</b>
          <div class="error"></div>
          <iframe width="468" height="263.25" src="" frameborder="0"  allowfullscreen></iframe>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="submitYoutube" onclick="sendMessageAttach(this, 'youtube')" disabled><i class="fa fa-paper-plane"></i> Send</button>
      </div>
    </div>
  </div>
</div>



<!-- The Modal -->
<div id="modalImage" class="modal-image">

  <!-- The Close Button -->
  <span class="close-modal-image">&times;</span>

  <!-- Modal Content (The Image) -->
  <img class="modal-image-content" id="img01">

</div>

</html>
