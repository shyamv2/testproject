<!DOCTYPE html>
<!--System file (php functions)-->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php" ?>
<?php
  //Verify if user is logged in
  if(!isset($_SESSION['id'])) {
      header("location: /");
      exit;
  }
  if(isset($_GET['id']) AND is_numeric($_GET['id'])){
    $assign = find_assignment($con, mysqli_real_escape_string($con, $_GET['id']));
    $class = find_class($con, $assign['class_id']);
    if(is_class_member($con, $_SESSION['id'], $class['id'])){
      $list_class_members = list_class_members($con, $class['id'], 0);
      $list_tutors = list_class_members($con, $class['id'], 1);
      $list_administrators = list_class_members($con, $class['id'], 2);
      $user_enrollment_id = get_user_enrollment_id($con, $class['id']);
      $list_posts = list_posts($con, 'assignment', $assign['id'], $user_enrollment_id, 30);
      $total_members = count($list_tutors) + count($list_class_members) + count($list_administrators);
    }
    else{
      header("location: /");
      exit;
    }
  }
  else{
    echo "URL ERROR!"; die;
  }
?>
<html class="no-js">
<head>
  <title><?php echo $assign['title'] ?> - Assignment | iStudy</title>
  <!--Head file (css and libraries)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/head.php" ?>

  <!--Aligning the 'no-content' center in case of no lesson results-->
  <style>
    .class-menu-assignments{
      box-shadow: -webkit-box-shadow: inset 0px -4px 0px 0px white;
      -moz-box-shadow: inset 0px -4px 0px 0px white;
      box-shadow: inset 0px -4px 0px 0px white;
      opacity: 1 !important;
      background-color: rgba(255, 255, 255, .1);
    }
    .class-menu{
      position: fixed;
      top: 60px;
    }
    .class-menu-name{
      display: block !important;
    }
  </style>
</head>
<body>
  <!--MENUS (TOP and LEFT)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/menu-top.php" ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/menu-left.php" ?>

  <!--CONTENT-->
  <div class="content">
      <!--Class Menu-->
      <div class="class-menu">
        <span class="class-menu-name"><?php echo $class['name'] ?></span>
        <a href="/class/<?php echo $class['id'] . '-' . linka($class['name']) ?>" class="class-menu-stream">
          <i class="fa fa-home"></i> Stream
        </a>
        <a href="/class/<?php echo $class['id'] . '-' . linka($class['name']) ?>/assignments" class="class-menu-assignments">
          <i class="fa fa-tasks"></i> Assignments
        </a>
        <a href="/class/<?php echo $class['id'] . '-' . linka($class['name']) ?>/members" class="class-menu-members">
          <i class="fa fa-group"></i> Members <span class="badge badge-pill badge-default"><?php echo $total_members ?></span>
        </a>
        <a href="/class/<?php echo $class['id'] . '-' . linka($class['name']) ?>/about" class="class-menu-about">
          About
        </a>
      </div>
<br><br>
    <?php
      if(user_class_permission($con, $_SESSION['id'], $class['id']) > 0){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/assignment-page-teacher-side.php";
      }
      else{
        include_once $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/assignment-page-student-side.php";
      }
    ?>

  <!--Footer-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/footer.php" ?>
  <!--MEMBER PROFILE-->
  <div class="modal fade bd-example-modal-lg" id="memberProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i> Member Profile</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-js" style="background-color: #fafafa; padding: 0 !important">
        </div>
      </div>
    </div>
  </div>
  <!--Turn In Assignment-->
  <div class="modal fade" id="turnInAssignment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-angle-up"></i> Turn In Assignment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-js">
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript" src="/assets/libs/ckeditor/ckeditor.js"></script>
  <!--====EmojiArea====-->
  <link rel="stylesheet" href="/assets/libs/emojionearea/dist/emojionearea.min.css">
  <script type="text/javascript" src="/assets/libs/emojionearea/dist/emojionearea.min.js"></script>
  <script type="text/javascript" src="/assets/libs/ckeditor/ckeditor.js"></script>
  <link rel="stylesheet" href="/assets/libs/justified/dist/css/justifiedGallery.min.css" />
  <script src="/assets/libs/justified/dist/js/jquery.justifiedGallery.min.js"></script>
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
  var classId = <?php echo $class['id'] ?>;
  var assignment_id = <?php echo $assign['id'] ?>;
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
      postToAssignment(<?php echo $assign['id'] ?>, <?php echo $user_enrollment_id ?>, button);
    });
  });

  if(firstUser){
    loadSubmittedAssignment(firstUser);
  }
  </script>
</body>
</html>
