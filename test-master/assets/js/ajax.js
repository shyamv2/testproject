//Sign Up function
function signUp(button){
  $("#signUpForm").ajaxSubmit({
    dataType: 'json',
    url: '/src/ajax-actions/users/sign-up.php',
    success: function(data){
      if(data['error'] == true){
        alert(data['msg_error']);
        button.innerHTML = "Sign Up";
        button.disabled = false;
      }
      else{
        alert("Account Successfully Created!");
        location.reload();
      }
    },
    beforeSend: function(){
      button.innerHTML = "Signing up";
      button.disabled = true;
    },
    error: function(){
      alert("Something went wrong, try again!")
      button.innerHTML = "Sign Up";
      button.disabled = false;
    },
    type: "POST"
  })
}
//Login function
function login(button){
  $("#loginForm").ajaxSubmit({
    dataType: 'json',
    url: '/src/ajax-actions/users/log-in.php',
    success: function(data){
      if(data['error'] == true){
        alert(data['msg_error']);
        button.innerHTML = "Log In";
        button.disabled = false;
      }
      else{
        location.reload();
      }
    },
    beforeSend: function(){
      button.innerHTML = "Working...";
      button.disabled = true;
    },
    error: function(){
      alert("Something went wrong, try again!")
      button.innerHTML = "Log In";
      button.disabled = false;
    },
    type: "POST"
  })
}

//Open Modal
function loadModal(modalPage, modalBox, button, e_data = null, reload = false){
  if(e_data == null){
    e_data = button.value;
  }
	$.ajax({
		url: "/templates/includes/modals/" + modalPage + "?data=" + e_data,
		success: function(data){
			$("#" + modalBox + " .modal-js").html(data);
        if(reload == false){
				  button.removeAttribute("onclick");
        }
		},
		beforeSend: function(){
			$("#" + modalBox + " .modal-js").html("<img src='/images/loading.svg' class='loading'>");
		},
		error: function(){
			$("#" + modalBox + " .modal-js").html("Something went wrong!");
		},
		type: "GET"
	});
}
// Get the snackbar DIV
function showMsg(msg, type, time = 3000){
	if(!type){
		type = "normal";
	}
	var types = {normal: 'rgba(50, 50, 50, .9)', success: 'rgba(8,167,32, .9)', error: 'red'};
    var x = document.getElementById("snackbar");
    x.style.background = types[type];
    x.innerHTML = msg;
    // Add the "show" class to DIV
    x.className = "show";

    // After 3 seconds, remove the show class from DIV
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, time);
}
//Submit form CREATE NEW Class
function createClassSubmit(button){
  $("#create-class").ajaxSubmit({
    dataType: 'json',
    url: '/src/ajax-actions/classes/create_class.php',
    success: function(data){
      if(data['error'] == true){
        showMsg(data['msg_error']);
        button.disabled = false;
      }
      else{
        button.disabled = true;
        showMsg("Everything went okay! Redirectiong...");
        setTimeout(location.href = data['link'], 3000);
      }
    },
    beforeSend: function(){
      button.disabled = true;
    },
    error: function(data){
      alert("Something went wrong..." + data['msg_error']);
      button.disabled = false;
    },
    type: 'POST'
  });
}
//Submit form EDIT Class
function editClassSubmit(button){
  $("#edit-class").ajaxSubmit({
    dataType: 'json',
    url: '/src/ajax-actions/classes/edit_class.php',
    success: function(data){
      if(data['error'] == true){
        alert(data['msg_error']);
      }
      button.disabled = false;
      button.innerHTML = "Save";
      showMsg("✓ Class Edited!");
    },
    beforeSend: function(){
      button.disabled = true;
      button.innerHTML = "Saving...";
    },
    error: function(data){
      alert("Something went wrong..." + data['msg_error']);
      button.disabled = false;
      button.innerHTML = "Save";
    },
    type: 'POST'
  });
}
//Update Agenda
function updateAgenda(agenda, classId){
  $.ajax({
    dataType: "json",
    data: {agenda : agenda, class_id : classId},
    url: "/src/ajax-actions/classes/update_agenda.php",
    type: "GET"
  })
}
//Update User Agenda
function updateUserAgenda(agenda){
  $.ajax({
    dataType: "json",
    data: {agenda : agenda},
    url: "/src/ajax-actions/users/update_user_agenda.php",
    type: "GET"
  })
}
//Update Agenda
function updateStudentSpace(enrollmentId){
  $.ajax({
    dataType: "json",
    data: {student_space : student_space.getData(), enrollment_id : enrollmentId},
    url: "/src/ajax-actions/classes/members/update_student_space.php",
    type: "GET"
  })
}
//Update Agenda
function updateTeacherSpace(enrollmentId){
  $.ajax({
    dataType: "json",
    data: {teacher_space : teacher_space.getData(), enrollment_id : enrollmentId},
    url: "/src/ajax-actions/classes/members/update_teacher_space.php",
    type: "GET"
  })
}
//Enroll to a class using code (for private classes)
function enroll(button){
  //Submit form
  $("#useCodeToEnroll").ajaxSubmit({
    dataType: 'json',
    url: '/src/ajax-actions/classes/members/enroll.php',
    success: function(data){
      if(data['error'] == true){
        alert(data['msg_error']);
        button.innerHTML = "Enroll";
        button.disabled = false;
      }
      else{
        showMsg("✓ A request will be send to this class' staff!");
        $('#createClass').modal('toggle');
      }
    },
    beforeSend: function(){
      button.innerHTML = "Enrolling...";
      button.disabled = true;
    },
    error: function(data){
      alert("Something went wrong.");
      button.innerHTML = "Enroll";
      button.disabled = false;
    },
    type: "POST"
  });
}
//For public classes
function requestEnrollment(button, classId){
    $.ajax({
      url: "/src/ajax-actions/classes/members/request_enrollment.php?class_id=" + classId,
      success: function(){
        showMsg("✓ Success! A request will be send to this class' staff!");
        button.disabled = true;
      },
      error: function(){
        showMsg("Something went wrong!");
      },
      type: "GET"
    });
}
//Accept enrollment requests
function acceptEnrollmentRequest(classId, requesterId){
  $.ajax({
    url: "/src/ajax-actions/classes/members/accept_enrollment_request.php?class_id=" + classId + "&requester_id=" + requesterId,
    type: "GET"
  });
  $("#request-" + requesterId).html("Accepted!");
}
//Dismiss enrollment requests
function dismissEnrollmentRequest(classId, requesterId){
  $.ajax({
    url: "/src/ajax-actions/classes/members/dismiss_enrollment_request.php?class_id=" + classId + "&requester_id=" + requesterId,
    type: "GET"
  });
  $("#request-" + requesterId).html("Dismissed!");
}
//Edit user profile info
function editProfile(button){
  $("#editProfile").ajaxSubmit({
    dataType: 'json',
    url: '/src/ajax-actions/users/edit-profile-info.php',
    success: function(data){
      if(data['error'] == true){
        alert(data['msg_error']);
        button.disabled = false;
        button.innerHTML = "Save Changes";
      }
      else{
        button.disabled = false;
        button.innerHTML = "Save Changes";
        showMsg("✓ Profile Edited!");
      }
    },
    beforeSend: function(){
      button.disabled = true;
      button.innerHTML = "Salving...";
    },
    error: function(data){
      alert("Something went wrong...");
      button.disabled = false;
      button.innerHTML = "Save Changes";
    },
    type: 'POST'
  });
}
//Post
function postToClass(targetId, button){
  $("#postForm").ajaxSubmit({
    dataType: 'json',
    data: {post: postText.data("emojioneArea").getText(), target_id: targetId},
    url: '/src/ajax-actions/posts/post_to_class.php',
    success: function(data){
      if(data['error'] == true){
        alert(data['msg_error']);
        button.disabled = false;
        button.innerHTML = "Post";
      }
      else{
        button.disabled = false;
        button.innerHTML = "Post";
        document.getElementById("mural").insertAdjacentHTML('afterbegin', data['post']);
        $(".no-content").css('display', 'none');
        $('[data-toggle="popover"]').popover({
          trigger: 'focus',
          html: true
        });
        //Justify images
        $(".post-gallery").justifiedGallery();
        $(".post-form-attaches").empty();
        $(".post-form-attaches").css("display", "none");
        //Enable buttons
        $('#uploadFileFakePost').prop("disabled", false);
        $('#b-video').prop("disabled", false);
        $('#b-link').prop("disabled", false);
      }
    },
    beforeSend: function(){
      button.disabled = true;
      button.innerHTML = "Posting...";
      postText.data("emojioneArea").setText("");
    },
    error: function(data){
      alert("Something went wrong..." + data['msg_error']);
      button.disabled = false;
      button.innerHTML = "Post";
    },
    type: 'POST'
  });
}
//Post
function postToLesson(targetId, button){
  $("#postForm").ajaxSubmit({
    dataType: 'json',
    data: {post: postText.data("emojioneArea").getText(), target_id: targetId},
    url: '/src/ajax-actions/posts/post_to_lesson.php',
    success: function(data){
      if(data['error'] == true){
        alert(data['msg_error']);
        button.disabled = false;
        button.innerHTML = "Post";
      }
      else{
        button.disabled = false;
        button.innerHTML = "Post";
        document.getElementById("mural").insertAdjacentHTML('afterbegin', data['post']);
        $('[data-toggle="popover"]').popover({
          trigger: 'focus',
          html: true
        });
        //Justify images
        $(".post-gallery").justifiedGallery();
        $(".no-content").css('display', 'none');
        $(".post-form-attaches").empty();
        $(".post-form-attaches").css("display", "none");
        //Enable buttons
        $('#uploadFileFakePost').prop("disabled", false);
        $('#b-video').prop("disabled", false);
        $('#b-link').prop("disabled", false);

      }
    },
    beforeSend: function(){
      button.disabled = true;
      button.innerHTML = "Posting...";
      postText.data("emojioneArea").setText("");
    },
    error: function(data){
      alert("Something went wrong..." + data['msg_error']);
      button.disabled = false;
      button.innerHTML = "Post";
    },
    type: 'POST'
  });
}
//Post
function postToProfile(targetId, button){
  $("#postForm").ajaxSubmit({
    dataType: 'json',
    data: {post: postText.data("emojioneArea").getText(), target_id: targetId},
    url: '/src/ajax-actions/posts/post_to_profile.php',
    success: function(data){
      if(data['error'] == true){
        alert(data['msg_error']);
        button.disabled = false;
        button.innerHTML = "Post";
      }
      else{
        button.disabled = false;
        button.innerHTML = "Post";
        document.getElementById("mural").insertAdjacentHTML('afterbegin', data['post']);
        $('[data-toggle="popover"]').popover({
          trigger: 'focus',
          html: true
        });
        //Justify images
        $(".post-gallery").justifiedGallery();
        $(".no-content").css('display', 'none');
        $(".post-form-attaches").empty();
        $("#post-attaches").empty();
        $(".post-form-attaches").css("display", "none");
        //Enable buttons
        $('#uploadFileFakePost').prop("disabled", false);
        $('#b-video').prop("disabled", false);
        $('#b-link').prop("disabled", false);
      }
    },
    beforeSend: function(){
      button.disabled = true;
      button.innerHTML = "Posting...";
      postText.data("emojioneArea").setText("");
    },
    error: function(data){
      alert("Something went wrong..." + data['msg_error']);
      button.disabled = false;
      button.innerHTML = "Post";
    },
    type: 'POST'
  });
}
//Post
function postToAssignment(targetId, alternative_target_id, button){
  //ALternative TargetID = enrollmentID
  $("#postForm").ajaxSubmit({
    dataType: 'json',
    data: {post: postText.data("emojioneArea").getText(), target_id: targetId, alternative_target_id: alternative_target_id},
    url: '/src/ajax-actions/posts/post_to_assignment.php',
    success: function(data){
      if(data['error'] == true){
        alert(data['msg_error']);
        button.disabled = false;
        button.innerHTML = "Post";
      }
      else{
        button.disabled = false;
        button.innerHTML = "Post";
        document.getElementById("mural").insertAdjacentHTML('afterbegin', data['post']);
        $('[data-toggle="popover"]').popover({
          trigger: 'focus',
          html: true
        });
        //Justify images
        $(".post-gallery").justifiedGallery();
        $(".no-content").css('display', 'none');
        $(".post-form-attaches").empty();
        $(".post-form-attaches").css("display", "none");
        //Enable buttons
        $('#uploadFileFakePost').prop("disabled", false);
        $('#b-video').prop("disabled", false);
        $('#b-link').prop("disabled", false);

      }
    },
    beforeSend: function(){
      button.disabled = true;
      button.innerHTML = "Posting...";
      postText.data("emojioneArea").setText("");
    },
    error: function(data){
      alert("Something went wrong..." + data['msg_error']);
      button.disabled = false;
      button.innerHTML = "Post";
    },
    type: 'POST'
  });
}
//Delete class
function deleteClass(classId){
  var confirm = window.confirm("Are you sure you want to delete this class? There will be no return...");
  if(confirm == true){
    $.ajax({
      url: "/src/ajax-actions/classes/delete_class.php?class_id=" + classId,
      success: function(){
        alert("Class deleted. Redirecting...");
        location.href = "/";
      },
      error: function(){
        alert("We are sorry! Something went wrong!");
      },
      type: "GET"
    });
  }
}
//Delete Post
function deletePost(postId){
  var conf = confirm("Are you sure you want to delete this post?");
  if(conf == true){
    $.ajax({
      url: "/src/ajax-actions/posts/delete_post.php?post_id=" + postId,
      type: "GET"
    });
    $("#post-" + postId).remove();
    showMsg("Deleted!");
  }
}
//Delete Post
function deleteComment(commentId){
  var conf = confirm("Are you sure you want to delete this comment?");
  if(conf == true){
    $.ajax({
      url: "/src/ajax-actions/posts/comments/delete_comment.php?comment_id=" + commentId,
      type: "GET"
    });
    $("#comment-" + commentId).remove();
    showMsg("Deleted!");
  }
}
var newContent;
var postContent = "";
function prepareEditPost(postId){
  postContent = $.trim($("#post-" + postId + " #raw-post-text").html());
  var form = "<form id='editPostForm-" + postId + "'><textarea id='edit-post-" + postId + "'>" + postContent + "</textarea><br><button type='button' class='btn btn-secondary' onclick='cancelEditPost(" + postId + ")'>Cancel</button><button type='button' class='btn btn-primary' onclick='submitEditPost(" + postId + ", this)'>Save</button></form>";
  $("#post-" + postId + " #post-text").html(form);
  newContent = $('#edit-post-' + postId).emojioneArea({
    pickerPosition: "bottom",
    tonesStyle: "bullet"
  });
}
function cancelEditPost(postId){
  $("#post-" + postId + " #post-text").html(postContent);
}
function submitEditPost(postId, button){
  var content = newContent.data("emojioneArea").getText();
  $.ajax({
    dataType: 'json',
    data: {post : content, post_id : postId},
    url: "/src/ajax-actions/posts/edit_post.php",
    success: function(){
      $("#post-" + postId + " #post-text").html(content);
    },
    beforeSend: function(){
      button.disabled = true;
      button.innerHTML = "Saving...";
      //CKEDITOR.instances['edit-post-' + postId].setData("");
    },
    type: "GET"
  })
}
//Leave Class
function leaveClass(classId){
  var confirm = window.confirm("Are you sure you want to leave this class?");
  if(confirm == true){
    $.ajax({
      url: "/src/ajax-actions/classes/leave_class.php?class_id=" + classId,
      type: "GET"
    });
    showMsg("✓ You are no longer a member of this class. Redirecting...");
    setTimeout(location.href = "/", 3000);
  }
}
//Remove member from a class
function removeMemberClass(classId, memberId){
  var confirm = window.confirm("Are you sure you want to remove this member?");
  if(confirm == true){
    $.ajax({
      url: "/src/ajax-actions/classes/members/remove_member_from_class.php?class_id=" + classId + "&member_id=" + memberId,
      type: "GET"
    });
    $("#member-" + memberId).html("");
    showMsg("✓ Removed");
  }
}
//Set all the nomtifications as read when user click in notifications
function setNotificationsAsRead(){
	$.ajax({
		dataType: 'json',
		url: "/src/ajax-actions/notifications/set_notifications_as_read.php",
		success: function(data){
			$(".notifications-number").html(0);
			$(".notifications-number").css("display", "none");
		},
		error: function(data){
			console.log(data);
		},
		type: "POST"
	});
}
//Server-Sent Event to get new nomtifications
var getNoti = new EventSource("/src/ajax-actions/notifications/check_new_notifications.php");
getNoti.onmessage = function(event){
	var data = JSON.parse(event.data);
  //New and old number of Notifications
	var newNot = parseInt(data['num_noti']);
	var oldNot = parseInt($(".notifications-number").html());
  var numberNoti = newNot + oldNot; // Sum up to see the new number
  //New and old Number of Messages
  var oldMsgs = parseInt($(".messages-number").html());
  var numberMsgs = parseInt(data['num_msgs']) + oldMsgs;
  //If there's any new notifications
	if(newNot > 0){
		$(".notifications-number").css("display", "inline-block");
    $(".notifications-number").html(numberNoti);
		var newNotContent = "";
    var list = [];
    list = data['notifications'];
		list.forEach(function(notification){
			newNotContent += "<a href='" + notification['link_ref'] + "'><li><img src='" + notification['icon'] + "'><div>" + notification['message'] + "<span>" + notification['registry'] + "</span></div></li></a>"
		});
		document.getElementById('list-notifications').insertAdjacentHTML('afterbegin', newNotContent);
	}
  if(numberMsgs > 0){
    $(".messages-number").css("display", "inline-block");
  }
  $(".messages-number").html(numberMsgs);
}
function displayCommentForm(postId){
  if(!$("#commentForm-" + postId).length){
    var form = "<form id='commentForm-" + postId + "' style='float: right; margin-top: 10px;'><button type='button' class='btn btn-secondary' onclick='closeCommentForm(" + postId + ")'>Cancel</button><button type='button' class='btn btn-primary' onclick='submitCommentForm(" + postId + ", this)'><i class='fa fa-'></i>Comment</button></form>";
    $("#response-form-" + postId).append(form);
  }
}
function focusCommentForm(postId){
  $("#comment-input-" + postId + " .emojionearea-editor").focus();
  displayCommentForm(postId);
}
function closeCommentForm(postId, button){
  $("#commentForm-" + postId).remove();
  $("#button-comment-" + postId).css("display", "block");
}
function submitCommentForm(postId, button){
  $("#commentForm-" + postId).ajaxSubmit({
    dataType: 'json',
    data: {post_id : postId, comment : comments[postId].data("emojioneArea").getText()},
    url: "/src/ajax-actions/posts/comments/comment.php",
    success: function(data){
      if(data['error'] == true){
        alert(data['msg_error']);
      }
      else{
        $("#post-"+postId+" .comments").css("display", "block");
        document.getElementById("list-comments-" + postId).insertAdjacentHTML("beforeend", data['comment']);
      }
      button.disabled = false;
      button.innerHTML = "Comment";
    },
    beforeSend: function(){
      button.disabled = true;
      button.innerHTML = "Wait...";
      comments[postId].data("emojioneArea").setText("");
    },
    error: function(){
      alert("Something went wrong...");
      button.disabled = false;
      button.innerHTML = "Comment";
    },
    type: "POST"
  })
}
//Delete LESSON
function deleteLesson(lessonId){
  var confirm = window.confirm("Are you sure you want to delete this lesson?");
  if(confirm == true){
    $.ajax({
      url: "/src/ajax-actions/classes/lessons/delete_lesson.php?lesson_id=" + lessonId,
      type: "GET"
    });
    location.href = "/";
  }
}
//Post card
function postCard(button, enrollment_id){
  $.ajax({
    dataType: 'json',
    data: {card : card.getData(), enrollment_id: enrollment_id},
    url: "/src/ajax-actions/classes/cards/post_card.php",
    success: function(data){
      if(data['error'] == true){
        alert(data['msg_error']);
      }
      else{
        document.getElementById("list-of-cards").insertAdjacentHTML('afterbegin', data['post']);
        CKEDITOR.inline("reply-textarea-" + data['card_id']);
      }
      button.disabled = false;
    },
    beforeSend: function(){
      button.disabled = true;
      CKEDITOR.instances['card_textarea'].setData("");
    },
    error: function(data){
      alert("Something went wrong." + data['error']);
      button.disabled = false;
    },
    type: "POST"
  });
}
//Post card
function replyCardSubmit(button, card_id){
  $.ajax({
    dataType: 'json',
    data: {reply : CKEDITOR.instances['reply-textarea-' + card_id].getData(), card_id: card_id},
    url: "/src/ajax-actions/classes/cards/reply_card.php",
    success: function(data){
      if(data['error'] == true){
        alert(data['msg_error']);
      }
      else{
        document.getElementById("list-replies-" + card_id).insertAdjacentHTML('beforeend', data['reply']);
      }
      button.disabled = false;
    },
    beforeSend: function(){
      button.disabled = true;
      CKEDITOR.instances['reply-textarea-' + card_id].setData("");
    },
    error: function(data){
      alert("Something went wrong." + data['error']);
      button.disabled = false;
    },
    type: "POST"
  });
}
//Delete Post
function deleteCard(cardId){
  var conf = confirm("Are you sure you want to delete this card?");
  if(conf == true){
    $.ajax({
      url: "/src/ajax-actions/classes/cards/delete_card.php?card_id=" + cardId,
      type: "GET"
    });
    $("#card-" + cardId).html("Deleted!");
    showMsg("✓ Card Deleted");
  }
}
function toggleFollow(button, followedId){
	$.ajax({
		url: "/src/ajax-actions/users/toggle_follow.php?followed_id=" + followedId,
		type: "GET",
		beforeSend: function(){
		   button.disabled = true;
		},
		success: function(data){
			data = $.parseJSON(data);
			//It means that the user is following now
			if(data['toggle'] == 1){
				$(button).toggleClass("btn-primary btn-success");
				$(button).html("<span class='fa fa-check'></span> Following");
        showMsg("✓ Following");
			}
			else{
				$(button).toggleClass("btn-success btn-primary");
				$(button).html("<span class='fa fa-user-plus'></span> Follow");
        showMsg("✓ Unfollowed");
			}
			$(button).prop("disabled", false);
		},
		error: function(e){
			showMsg(e.error, 'error');
		}
	});
}

function giveFeedback(button){
  var msg = document.getElementById("feedback-text").value;
  $.ajax({
    url: "/src/ajax-actions/general/give_feedback.php",
    type: "POST",
    data: {feedback: msg},
    beforeSend: function(){
      button.disabled = true;
      button.innerHTML = "Sending..."
    },
    success: function(data){
      if(data['error'] == true){
        alert(data['msg_error']);
      }
      else{
        alert("Thank you so much! We really appreciate your feedback!");
        location.href = "/";
        $("#FeedbackModal").modal("toggle");
      }
      button.disabled = false;
      button.innerHTML = "Send Message";
    },
    error: function(data){
      alert("Something went wrong." + data['error']);
      button.disabled = false;
      button.innerHTML = "Send Message";
    },
    type: "POST"
  });
}
function changeUserPermissionInClass(enrollment_id, type){
  var confirm = window.confirm("Are you sure you want to continue with this action?");
  if(confirm == true){
    $.ajax({
      data: {enrollment_id : enrollment_id, type : type},
      url: '/src/ajax-actions/classes/members/change_user_permission.php',
      success: function(){
        showMsg("✓ Done!");
        setTimeout(location.reload(), 3000);
      },
      error: function(){
        alert("Something went wrong. Please, try again!");
      },
      type: 'POST'
    });
  }
}
function removeCurrentCover(){
	$.ajax({
		url: "/src/ajax-actions/users/remove_current_cover.php",
		success: function(data){
			$('.profile-cover').css("background-image", "none");
			showMsg("Removed!");
		},
		error: function(){
			alert("Something went wrong!");
		}
	});
}
function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Byte';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
};
function fileName(fullPath){
  var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
    var filename = fullPath.substring(startIndex);
    if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
      return filename.substring(1);
    }
};
function limitString(string, length){
  if(string.length > length){
    return string.substring(0, length) + "...";
  }
  return string;
}

function validURL(str) {
  var pattern = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/;
  if(!pattern.test(str)) {
    return false;
  } else {
    return true;
  }
}
$("#uploadFileFakePost").click(function(){
    document.getElementById('fileUploadPost').click();
});
var files_counter = 0;
function validateFile(input, fileName){
  var allowed_extensions = new Array("jpg", "jpeg", "png", "gif", "JPG",
  "ai", "psd", "svg", "bmp", "tif", "tiff",
  "doc", "docx",
  "xls", "xlsx",
  "ppt", "pptx",
  "mp4", "mp3", "mkv", "avi", "wav", "ogg", "mid", "midi", "wma", "mpg", "mpeg",
  "zip", "rar", "giz", "7z", "gz",
  "epub", "pdf", "txt", "csv");
  var file_extension = fileName.split('.').pop(); // split function will split the filename by dot(.), and pop function will pop the last element from the array which will give you the extension as well. If there will be no extension then it will return the filename.
  var file = input.files[0];
  var sizeLimit = 5 * Math.pow(10, 7);
  if($.inArray(file_extension, allowed_extensions) > -1){
    if(file.size <= sizeLimit){
      uploading[files_counter] = null;
      preloadFile(input, file.size);
      files_counter++;
      uploading[files_counter] = null;
    }
    else{
      alert("File size limit: 50MB");
    }
  }
  else{
    alert("This extensions is not allowed!");
  }
}
var uploading = [];
function preloadFile(valid_file, file_size){
  var file = [];
  file['id'] = files_counter;
  file['name'] = limitString(fileName(valid_file.value), 30);
  file['size'] = bytesToSize(file_size);
  //Show that file is being uploaded
  $(".post-form-attaches").css("display", "block");
  var uploading_template = "<div class='post-form-attach' id='att-"+file['id']+"'><div class='attach-progress'><div class='progress-bar'><div class='progress-percentage' id='p-" + file['id'] + "'></div></div><button type='button' onclick='cancelUpload("+file['id']+")'>x</button></div><div class='info'><i class='fa fa-file'></i><b>" + file['name'] + "</b> | <small>" + file['size'] + "</small></div></div>";
  $(".post-form-attaches").append(uploading_template);
  //Disable other buttons
  $('#b-link').prop("disabled", true);
  $('#b-video').prop("disabled", true);
  //Upload file
  $("#formUploadFiles").ajaxSubmit({
    dataType: 'json',
    url: "/src/ajax-actions/posts/upload_post_attach.php",
    uploadProgress: function(event, position, total, percentComplete){
      $('#p-' + file['id']).css("width", percentComplete + "%");
    },
    complete: function(data){
      if(data['error'] == true){
        alert(data['msg_error']);
        $('#p-' + file['id']).css("background-color", "red");
      }
      else{
        $('#p-' + file['id']).css("background-color", "#00ba00");
      }
    },
    success: function(data){
      if(data['error'] == true){
        alert(data['msg_error']);
      }
      else{
        var checkbox = "<input type='checkbox' value='" + data['file_id'] + "' name='attaches[]' id='checkbox-" + file['id'] + "' checked>";
        $("#post-attaches").append(checkbox);
      }
    },
    error: function(){
      showMsg("Something went wrong. Please, Try again!");
    },
    add: function (e, data) {
      uploading[files_counter] = data.submit();
    },
    type: "POST"
  });
}
function cancelUpload(file_id){
  $("#att-" + file_id).remove();
  if($("#checkbox-" + file_id)){
    $("#checkbox-" + file_id).prop('checked', false);
  }
  if($.trim($('.post-form-attaches').html()).length == 0){
    $(".post-form-attaches").css('display', 'none');
    $('#b-link').prop("disabled", false);
    $('#b-video').prop("disabled", false);
  }
  uploading[file_id].abort();
}
function reactToPost(reaction, postId){
  $.ajax({
    dataType: 'json',
    data: {reaction: reaction, post_id: postId},
    url: '/src/ajax-actions/posts/react_to_post.php',
    success: function(){
      showMsg('Reacted!');
    },
    beforeSend: function(){
      var image = $("#reaction-"+postId+"-" + reaction + " img").attr('src');
      var name = $("#reaction-"+postId+"-" + reaction + " span").html();
      var buttonTemplate = "<img src='" + image + "' style='width: 20px; display: inline'>  " + name;
      $("#post-" + postId + ' .react-button').html(buttonTemplate);
    },
    error: function(){
      showMsg("Something went wrong!");
    },
    type: 'POST'
  })
}
var last_post_generated;
function loadMorePosts(button, target, target_id, last_post_given){
  if(last_post_generated){
    last_post = last_post_generated;
  }
  else{
    last_post = last_post_given;
  }
  $.ajax({
    dataType: 'json',
    data: {target: target, target_id: target_id, last_post: last_post},
    url: '/src/ajax-actions/posts/load_more_posts.php',
    success: function(data){
      if(data['exist']){
        $("#mural").append(data['posts']);
        $('[data-toggle="popover"]').popover({
          trigger: 'focus',
          html: true
        });
        //Justify images
        $(".post-gallery").justifiedGallery();
        if(data['more']){
          button.disabled = false;
          button.innerHTML = "Load More";
        }
        else{
          button.style.display = "none";
        }
        last_post_generated = data['last_post'];
      }
      else{
        button.disabled = true;
        button.innerHTML = "No more posts.";
      }
    },
    beforeSend: function(){
      button.disabled = true;
      button.innerHTML = "Loading...";
    },
    error: function(data){
      showMsg("Something went wrong!");
    },
    type: "POST"
  })
}
function loadMorePostsFeed(button, last_post_given){
  if(last_post_generated){
    last_post = last_post_generated;
  }
  else{
    last_post = last_post_given;
  }
  $.ajax({
    dataType: 'json',
    data: {last_post: last_post},
    url: '/src/ajax-actions/posts/load_more_posts_feed.php',
    success: function(data){
      if(data['exist']){
        $("#mural").append(data['posts']);
        $('[data-toggle="popover"]').popover({
          trigger: 'focus',
          html: true
        });
        //Justify images
        $(".post-gallery").justifiedGallery();
        if(data['more']){
          button.disabled = false;
          button.innerHTML = "Load More";
        }
        else{
          button.style.display = "none";
        }
        last_post_generated = data['last_post'];
      }
      else{
        button.disabled = true;
        button.innerHTML = "No more posts.";
      }
    },
    beforeSend: function(){
      button.disabled = true;
      button.innerHTML = "Loading...";
    },
    error: function(data){
      showMsg("Something went wrong!");
    },
    type: "POST"
  })
}

function updateAssignmentGrade(memberId, assignmentId, grade){
  $.ajax({
    dataType: 'json',
    data: {user_id: memberId, assignment_id: assignmentId, class_id: classId, grade: grade},
    url: "/src/ajax-actions/classes/assignments/update_assignment_grade.php",
    success: function(data){
      if(data['error'] == true){
        showMsg("Something went wrong. Retype the grade!");
      }
      else{
        showMsg("✓ Salved");
      }
    },
    beforeSend: function(){
      showMsg("Saving...");
    },
    error: function(){
      showMsg("Something went wrong. Retype the grade!");
    },
    type: "POST"
  });
}
function updateFinalGrade(memberId, grade){
  $.ajax({
    dataType: 'json',
    data: {user_id: memberId, class_id: classId, grade: grade},
    url: "/src/ajax-actions/classes/assignments/update_final_grade.php",
    success: function(data){
      if(data['error'] == true){
        showMsg("Something went wrong. Retype the grade!");
      }
      else{
        showMsg("✓ Salved");
      }
    },
    beforeSend: function(){
      showMsg("Saving...");
    },
    error: function(){
      showMsg("Something went wrong. Retype the grade!");
    },
    type: "POST"
  });
}
function sendFinalReportEmail(class_id, button){
  var conf = confirm("Are you sure of this action? Verify if all the info is right before sending...");
  if(conf == true){
    $.ajax({
      dataType: "json",
      data: {class_id: class_id},
      url: "/src/ajax-actions/classes/assignments/send_final_report_email.php",
      success: function(data){
        if(data['success'] == true){
          showMsg("Successfully sent!");
        }
        else{
          showMsg("Something went wrong!");
        }
        button.disabled = false;
        button.innerHTML = "<i class='fa fa-envelope'></i> Send Email";
      },
      beforeSend: function(){
        button.disabled = true;
        button.innerHTML = "<i class='fa fa-envelope'></i> Sending... It might take a while...";
      },
      error: function(data){
        showMsg("Something went wrong!");
        button.disabled = false;
        button.innerHTML = "<i class='fa fa-envelope'></i> Send Email";
      },
      type: "POST"
    });
  }
}
function giveUserBadge(badge_id, enrollment_id, user_id){
  $.ajax({
    dataType: 'json',
    data: {
      badge_id: badge_id,
      enrollment_id: enrollment_id,
      user_id: user_id
    },
    url: "/src/ajax-actions/classes/members/give_user_badge.php",
    success: function(data){
      if(data['error'] == true){
        showMsg("Something went wrong");
      }
      else{
        showMsg("Badge Switched!");
      }
    },
    error: function(){
      showMsg("Something went wrong!");
    },
    type: 'POST'
  });
}
//Accept association requests
function acceptAssociationRequest(requesterId){
  $.ajax({
    url: "/src/ajax-actions/users/accept_association_request.php?requester_id=" + requesterId,
    type: "GET"
  });
  $("#request-" + requesterId).html("Accepted!");
  setTimeout(location.reload(), 6000);
}
//Dismiss association requests
function dismissAssociationRequest(requesterId){
  $.ajax({
    url: "/src/ajax-actions/users/dismiss_association_request.php?requester_id=" + requesterId,
    type: "GET"
  });
  $("#request-" + requesterId).html("Dismissed!");
  setTimeout(location.reload(), 6000);
}
function resendEmailConfirmation(button){
  $.ajax({
    dataType: 'json',
    url: '/src/ajax-actions/users/send-email-confirmation.php',
    success: function(data){
      if(data['success'] == true){
        showMsg("Email successfully sent!");
        button.innerHTML = "Verify your email!";
      }
      else{
        showMsg("Something went wrong!");
        button.innerHTML = "<i class='fa fa-envelope'></i> Try Again!";
        button.disabled = false;
      }
    },
    beforeSend: function(){
      button.innerHTML = "<i class='fa fa-envelope'></i> Sending...";
      button.disabled = true;
    },
    error: function(){
      showMsg("Something went wrong!");
      button.innerHTML = "<i class='fa fa-envelope'></i> Try Again!";
      button.disabled = false;
    },
    type: "POST"
  });
}
//Delete assignement
function deleteAssignment(assignId){
  var confirm = window.confirm("Are you sure you want to delete this assignment?");
  if(confirm == true){
    $.ajax({
      url: "/src/ajax-actions/classes/assignments/delete_assignment.php?assign_id=" + assignId,
      success: function(){
        showMsg("Assignment successfully deleted!");
        setTimeout("location.reload()", 1500);
      },
      error: function(){
        alert("We are sorry! Something went wrong!");
      },
      type: "GET"
    });
  }
}
