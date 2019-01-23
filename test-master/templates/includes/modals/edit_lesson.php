<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  if(isset($_GET['data']) AND is_numeric($_GET['data'])){
    $lesson_id = mysqli_real_escape_string($con, $_GET['data']);
    $lesson = find_lesson($con, $lesson_id);
  }else{
    exit;
  }
?>

<form id="editLessonForm" method="POST">
  <label>
    <b>Title</b>
    <input type="text" name="name" placeholder="Type the name/title of this lesson here..." value="<?php echo $lesson['name'] ?>">
  </label>
  <label>
    <b>Skills Expected to be acquired</b>
    <textarea type="text" name="skills" placeholder="At the end of this lesson, your students will be able to..."><?php echo $lesson['skills'] ?></textarea>
  </label>
  <label>
    <b>Type of Lesson</b>
    <div class="btn-group" data-toggle="buttons" style="width: 100%">
      <label class="btn btn-secondary" id="option1" style="text-align: center">
        <input type="radio" name="type" value="1" <?php echo ($lesson['type'] == 1) ? "checked" : "" ?>> <img src="/images/icons/image.png" style="width: 100px; display: block; margin: 5px auto"> Text
      </label>
      <label class="btn btn-secondary" id="option2" style="text-align: center">
        <input type="radio" name="type" value="2" <?php echo ($lesson['type'] == 2) ? "checked" : "" ?>> <img src="/images/icons/video-player.png" style="width: 100px; display: block; margin: 5px auto"> Text + Principal Video
      </label>
    </div>
  </label>
  <label id="cover">
    <b>Cover Image/Video</b><small style="font-size: 10px">If you chose only text, this input should be an image URL, otherwise you should enter a youtube link.</small>
    <input type="text" name="cover_link" placeholder="http://" value="<?php echo ($lesson['type'] == 1) ? $lesson['cover_link'] : 'https://youtube.com/watch?v=' . $lesson['cover_link']  ?>">
  </label>
  <label>
    <b>Article</b>
    <textarea type="text" id="cover_input" placeholder="Write your article here..."><?php echo $lesson['article'] ?></textarea>
  </label>
  <input type="number" name="lesson_id" style="display: none" value="<?php echo $lesson['id'] ?>">
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary" name="send" id="send" onclick="editLessonSubmit(this)">Save Changes</button>
  </div>
</form>
<script type="text/javascript">
  var article = CKEDITOR.replace("cover_input");
  //Submit form EDIT LESSON
  function editLessonSubmit(button){
    $("#editLessonForm").ajaxSubmit({
      dataType: 'json',
      data: {article: article.getData()},
      url: '/form-actions/edit_lesson.php',
      success: function(data){
        if(data['error'] == true){
          alert(data['msg_error']);
          button.disabled = false;
        }
        else{
          button.disabled = true;
          alert("Everything went okay! Reloading...");
          location.reload();
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
<?php echo ($lesson['type'] == 1) ? "<script>$('#option1').button('toggle');</script>" : "<script>$('#option2').button('toggle');</script>" ?>
