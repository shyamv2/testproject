<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  if(!isset($_SESSION['id'])){
    echo "Login to continue...";
    exit;
  }
  if(isset($_GET['data']) AND is_numeric($_GET['data'])){
    $class_id = mysqli_real_escape_string($con, $_GET['data']);
  }
?>

<form id="createNewLesson">
  <label>
    <b>Title</b>
    <input type="text" name="name" placeholder="Type the name/title of this lesson here...">
  </label>
  <label>
    <b>Skills Expected to be acquired</b>
    <textarea type="text" name="skills" placeholder="At the end of this lesson, your students will be able to..."></textarea>
  </label>
  <label>
    <b>Type of Lesson</b>
    <div class="btn-group" data-toggle="buttons" style="width: 100%">
      <label class="btn btn-info" style="text-align: center">
        <input type="radio" name="type" id="option1" autocomplete="off" value="1"> <img src="/images/icons/image.png" style="width: 100px; display: block; margin: 5px auto"> Text
      </label>
      <label class="btn btn-info" style="text-align: center">
        <input type="radio" name="type" id="option2" autocomplete="off" value="2"> <img src="/images/icons/video-player.png" style="width: 100px; display: block; margin: 5px auto"> Text + Principal Video
      </label>
    </div>
  </label>
  <label id="cover">
    <b>Cover Image/Video</b><small style="font-size: 10px">If you chose only text, this input should be an image URL, otherwise you should enter a youtube link.</small>
    <input type="text" name="cover_link" placeholder="http://">
  </label>
  <label>
    <b>Article</b>
    <textarea type="text" name="article" id="cover_input" placeholder="Write your article here..."></textarea>
  </label>
  <input type="number" name="class_id" value="<?php echo $class_id ?>" style="display: none">
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary" name="send" id="send" onclick="createLessonSubmit(this)">Create Lesson</button>
  </div>
</form>
<script>
  var article = CKEDITOR.replace("cover_input");
  //Submit form CREATE NEW Class
  function createLessonSubmit(button){
    $("#createNewLesson").ajaxSubmit({
      dataType: 'json',
      data: {article: article.getData()},
      url: '/src/ajax-actions/classes/lessons/create_lesson.php',
      success: function(data){
        if(data['error'] == true){
          alert(data['msg_error']);
          button.disabled = false;
        }
        else{
          button.disabled = true;
          alert("Everything went okay! Redirectiong...");
          location.href = data['link'];
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
</script>
