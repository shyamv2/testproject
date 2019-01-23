<form id="postForm" style="max-width: 100% !important; padding: 20px; display: block">
  <div class="post-form-body">
    <!--====Post Form Textarea====-->
      <textarea id="post-textarea" name="post" placeholder="Write your post here..."></textarea>
  </div>
  <div class="post-form-attaches"></div>
  <div class="post-form-footer">
    <!--====Post Submit====-->
    <button type="button" class="btn btn-primary" style="float: right" id="post-submit">
      Post
    </button>
    <!--====Post Features====-->
    <div class="post-features">
      <!--Upload File-->
      <button type="button" data-toggle="tooltip" data-placement="bottom" data-animation="false" title="Upload Files" id="uploadFileFakePost">
        <i class="fa fa-paperclip"></i>
      </button>
      <!--Youtube Video-->
      <span data-toggle="tooltip" data-placement="bottom" data-animation="false" title="Youtube Video">
        <button type="button" data-toggle="modal" data-target="#insertVideo" id="b-video">
          <i class="fa fa-youtube-play"></i>
        </button>
      </span>
      <!--Insert Link-->
      <span data-toggle="tooltip" data-placement="bottom" data-animation="false" title="Insert Link">
        <button type="button" data-toggle="modal" data-target="#insertLink" id="b-link">
          <i class="fa fa-link"></i>
        </button>
      </span>
      <!--Formatting-->
      <span data-toggle="tooltip" data-placement="bottom" data-animation="false" title="Formatting Tips">
        <button type="button" data-toggle="modal" data-target="#formatting">
          <i class="fa fa-bold"></i>
        </button>
      </span>
    </div>
  </div>

  <!--Attaches-->
  <div id="post-attaches" style="display: none">
  </div>
</form>
<!--Real inputs-->
<form id="formUploadFiles">
  <input type="file" name="file" onchange="validateFile(this, this.value)" style="display: none" id="fileUploadPost">
</form>

<!--Modals-->
<!--Insert Youtube Video-->
<div class="modal fade" id="insertVideo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-youtube-play"></i> Insert Youtube Video</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <label>
            <b>URL</b>
            <input type="text" placeholder="https://" onchange="validateLink(this, 'video')" onpaste="validateLink(this, 'video')" id="inputVideo"><br>
            <iframe id="videoObject" type="text/html" width="460" height="265" style="display: none; margin-top: 10px;" frameborder="0" allowfullscreen></iframe>
          </label>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" onclick='submitVideo()' class="btn btn-primary" id="submitVideo" disabled>Use</button>
      </div>
    </div>
  </div>
</div>
<!--Insert Link-->
<div class="modal fade" id="insertLink" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-link"></i> Insert External Link</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <label>
            <b>URL</b>
            <input type="text" placeholder="https://" onchange="validateLink(this, 'link')" onpaste="validateLink(this, 'link')" id="inputLink">
            <a id="linkPreview"></a>
          </label>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" onclick='submitLink(this)' class="btn btn-primary" id="submitLink" disabled>Use</button>
      </div>
    </div>
  </div>
</div>
<!--Formatting-->
<div class="modal fade" id="formatting" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-bold"></i> Formatting tips</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table style="width: 100%;">
          <tr>
            <th><b>RESULT</b></th>
            <th><b>EXAMPLE</b></th>
          </tr>
          <tr>
            <td><b>Bold</b></td>
            <td>**Bold**</td>
          </tr>
          <tr>
            <td><em>Italic</em></td>
            <td>_Italic_</td>
          </tr>
          <tr>
            <td><ul><li>List Item 1</li><li>List Item 2</li></ul></td>
            <td>- List Item 1<br>- List Item 2 </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
function validateLink(link, type){
  var url = link.value;
  if(type == 'video'){
    if (url != undefined || url != '') {
        var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
        var match = url.match(regExp);
        if (match && match[2].length == 11) {
            $("#videoObject").css("display", "block");
            $('#videoObject').attr('src', 'https://www.youtube.com/embed/' + match[2] + '?autoplay=0&enablejsapi=1');
            $('#submitVideo').prop("disabled", false);
        } else {
            $('#submitVideo').prop("disabled", true);
        }
    }
  }
  else if(type == 'link'){
    if(validURL(url)){
      $('#submitLink').prop("disabled", false);
    }
    else{
      $('#submitLink').prop("disabled", true);
    }
  }
}
function submitVideo(){
  //dismiss modal
  $('#insertVideo').modal('toggle');

  //Prepare link
  var url = $('#inputVideo').val();
  var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
  var match = url.match(regExp);
  var newLink = "https://www.youtube.com/embed/" +  match[2];

  //Create iframe for preview
  var iframe = "<div class='post-form-video'><iframe type='text/html' width='460' height='265' src='"+newLink+"' frameborder='0' allowfullscreen></iframe><button type='button' onclick='cancelVideo()'>X</button></div>"
  $('.post-form-attaches').css('display', 'block');
  $('.post-form-attaches').append(iframe);

  //Disable other buttons
  $('#uploadFileFakePost').prop("disabled", true);
  $('#b-video').prop("disabled", true);
  $('#b-link').prop("disabled", true);

  //Insert into the form
  var checkbox = "<input type='checkbox' name='video' value='"+newLink+"' id='video-checkbox' checked>";
  $("#post-attaches").append(checkbox);
}
function submitLink(){
  //dismiss modal
  $('#insertLink').modal('toggle');

  //Prepare link
  var url = $('#inputLink').val();

  //Create a card for preview
  var card = "<div class='post-form-link'><a href="+url+">"+url+"</a><button type='button' onclick='cancelLink()'>X</button></div>"
  $('.post-form-attaches').css('display', 'block');
  $('.post-form-attaches').append(card);

  //Disable other buttons
  $('#uploadFileFakePost').prop("disabled", true);
  $('#b-video').prop("disabled", true);
  $('#b-link').prop("disabled", true);

  //Insert into the form
  var checkbox = "<input type='checkbox' name='link' value='"+url+"' id='link-checkbox' checked>";
  $("#post-attaches").append(checkbox);
}
function cancelVideo(){
  $(".post-form-video").remove();
  if($("video-checkbox")){
    $("#video-checkbox").prop('checked', false);
  }
  if($.trim($('.post-form-attaches').html()).length == 0){
    $(".post-form-attaches").css('display', 'none');
    $('#uploadFileFakePost').prop("disabled", false);
    $('#b-link').prop("disabled", false);
    $('#b-video').prop("disabled", false);
  }
}
function cancelLink(){
  $(".post-form-link").remove();
  if($("link-checkbox")){
    $("#link-checkbox").prop('checked', false);
  }
  if($.trim($('.post-form-attaches').html()).length == 0){
    $(".post-form-attaches").css('display', 'none');
    $('#uploadFileFakePost').prop("disabled", false);
    $('#b-link').prop("disabled", false);
    $('#b-video').prop("disabled", false);
  }
}
</script>
