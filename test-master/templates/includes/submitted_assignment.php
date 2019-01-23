<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
if(isset($_SESSION['id'])){
  if(isset($_POST['member_id']) AND is_numeric($_POST['member_id'])){
    if(isset($_POST['assignment_id']) AND is_numeric($_POST['assignment_id'])){
      $user = find_user($con, mysqli_real_escape_string($con, $_POST['member_id']));
      $assign = find_assignment($con, mysqli_real_escape_string($con, $_POST['assignment_id']));
      $class = find_class($con, $assign['class_id']);
      if(user_class_permission($con, $_SESSION['id'], $class['id']) > 0){
        $grade = find_user_assignment_grade($con, $assign['id'], $class['id'], $user['id']);
        $submitted = get_submitted_assignment($con, $assign['id'], $user['id']);
        $enrollment_id = get_user_enrollment_id($con, $class['id'], $user['id']);
        $list_posts = list_posts($con, 'assignment', $assign['id'], $enrollment_id, 30);
      }
      else{

      }
    }
    else{
      exit;
    }
  }
  else{
    exit;
  }
}
else{
  exit;
}
?>
<div class="row row-content" style="min-width: 100%">
<div class="col-sm-6">
  <div class="panel" style="padding: 20px">
  <!--Grade-->
  <label style="float: right;padding-left: 20px; border-left: 1px solid #eee">
    <input type="text" class="input-grade" value="<?php echo $grade ?>" placeholder="Grade" style="border: 1px solid #ddd; box-shadow: 2px 3px 4px rgba(0,0,0,.03); padding: 5px 10px"
    onkeyup="updateAssignmentGrade(<?php echo $user['id'] ?>, <?php echo $assign['id'] ?>, this.value)"
    onpaste="updateAssignmentGrade(<?php echo $user['id'] ?>, <?php echo $assign['id'] ?>, this.value)">
  </label>
  <!--Profile and Status-->
  <div style="font-size: 16px;">
    <img src="<?php echo $user['picture'] ?>" style="width: 30px; float: left; margin-right: 10px; border-radius: 100%">
    <a href="/profile/<?php echo $user['id'] ?>-<?php echo linka($user['name']) ?>"
      style="all: unset; font-weight: bold; cursor: pointer">
      <?php echo $user['name'] ?></a>
      <?php if(count($submitted) > 0) : ?>
        submitted their assignment <?php echo time_elapsed_string($submitted['registry']) ?>.
      <?php else: ?>
        did not submit their assignment yet.
      <?php endif; ?>
    </div>
  </div>
  <!--Submitted Assignment-->
  <?php if(count($submitted) > 0) : ?>
  <div class="panel" style="padding: 20px">
    <?php echo linkify_str(nl2br($submitted['plain_text'])); ?>
  </div>
  <?php endif; ?>
</div>

<div class="col-sm-6">
  <div class="panel" style="padding: 0">
    <div class="panel-header">Private Comments</div>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/post-form.php"; ?>
  </div>
  <div id="mural">
    <!--====LIST POSTS====-->
    <?php foreach($list_posts as $post) : ?>
      <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/post.php"; ?>
    <?php endforeach; ?>
  </div>
</div>
</div>

<script>
  var comments = [];
  <?php foreach($list_posts as $post) : ?>
    var postId = <?php echo $post['id'] ?>;
    comments[postId] = $("#comment-input-" + postId).emojioneArea({
      pickerPosition: "bottom",
      tonesStyle: "bullet",
      events: {
        click: function(editor, event){
          displayCommentForm(<?php echo $post['id'] ?>);
        }
      }
    });
  <?php endforeach; ?>
</script>
<script type="text/javascript">
//Justify images
$(".post-gallery").justifiedGallery();
var postText;
$(document).ready(function(){
  postText = $("#post-textarea").emojioneArea({
    pickerPosition: "bottom",
    tonesStyle: "bullet"
  });
  //Trigger Button for the post form
  $('#post-submit').click(function(){
    var button = document.getElementById('post-submit');
    postToAssignment(<?php echo $assign['id'] ?>, <?php echo $enrollment_id ?>, button);
  });
});

</script>
