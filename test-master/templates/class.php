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
    $class = find_class($con, mysqli_real_escape_string($con, $_GET['id']));
    if(is_class_member($con, $_SESSION['id'], $class['id'])){
      $list_class_members = list_class_members($con, $class['id'], 0);
      $list_tutors = list_class_members($con, $class['id'], 1);
      $list_administrators = list_class_members($con, $class['id'], 2);
      $n_member_requests = number_of_member_requests($con, $class['id']);
      $list_posts = list_posts($con, 'class', $class['id']);
      $list_lessons = list_class_lessons($con, $class['id']);
      $user_enrollment_id = get_user_enrollment_id($con, $class['id']);
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
  <title><?php echo $class['name'] ?> | iStudy</title>
  <!--Head file (css and libraries)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/head.php" ?>
  <!--Slick (slider)-->
  <link rel="stylesheet" type="text/css" href="/assets/libs/slick/slick/slick.css"/>
  <link rel="stylesheet" type="text/css" href="/assets/libs/slick/slick/slick-theme.css"/>

  <!--Aligning the 'no-content' center in case of no lesson results-->
  <style>
  <?php if(count($list_lessons) == 0) : ?>
      .slick-slider .slick-track, .slick-slider .slick-list{
        width: 100% !important;
      }
  <?php endif; ?>
  .class-menu-stream{
    box-shadow: -webkit-box-shadow: inset 0px -4px 0px 0px white;
    -moz-box-shadow: inset 0px -4px 0px 0px white;
    box-shadow: inset 0px -4px 0px 0px white;
    opacity: 1 !important;
    background-color: rgba(255, 255, 255, .1);
  }
  </style>
</head>
<body>
  <!--MENUS (TOP and LEFT)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/menu-top.php" ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/menu-left.php" ?>

  <!--CONTENT-->
  <div class="content">
    <!--Page TITLE-->
    <div class="page-title page-title-class">
      <?php if(user_class_permission($con, $_SESSION['id'], $class['id']) < 2) : ?>
        <div class="class-options">
          <button class="btn-outlined" data-toggle="modal" data-target="#memberProfile" onclick="loadModal('member_profile.php', 'memberProfile', this, <?php echo $user_enrollment_id ?>, true)">My Status</button>
          <div class="dropdown">
            <button type="button" class="btn-outlined" data-toggle="dropdown"><i class="fa fa-gear"></i></button>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="#" onclick="leaveClass(<?php echo $class['id'] ?>)">Leave Class</a>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <div class="page-title-class-head">
        <div class="page-title-class-head-date">
          <?php echo translateDateHalf(date("Y-m-d", strtotime($class['start_date']))) . " - " . translateDateHalf(date("Y-m-d", strtotime($class['end_date']))) ?>
        </div>
        <div class="page-title-class-head-title">
          <?php echo $class['name'] ?>
        </div>
      </div>
      <center>
      <!--Breadcrumb-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/">Classes</a></li>
        <li class="breadcrumb-item active"><?php echo $class['name'] ?></li>
      </ol>
    </center>

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
    </div>

    <div class="row row-content">
      <div class="col-sm-8">


        <!--Lessons Panel-->
        <div class="panel panel-lesson" style="padding: 0; background-color: #fafafa">
            <!--Button-->
            <?php if(user_class_permission($con, $_SESSION['id'], $class['id']) > 0) : ?>
              <div>
                <button type="button" class="new-lesson" data-toggle="modal" data-target="#newLesson" class="panel-lesson-new" onclick="loadModal('create_new_lesson.php', 'newLesson', this)" value="<?php echo $class['id'] ?>">
                  <i class="fa fa-plus"></i>
                  New Lesson
                </button>
              </div>
            <?php endif; ?>
            <?php foreach($list_lessons as $lesson) : ?>
            <?php if($lesson['type'] == 2){
              $lesson['cover_link'] = "https://img.youtube.com/vi/" . $lesson['cover_link'] . "/0.jpg";
              $lesson['link'] = "/lesson/" . $lesson['id'] . "-" . linka($lesson['name']);
            }
            ?>
            <div class="lesson-box">
              <a href="<?php echo '/lesson/' . $lesson['id'] . '-' . linka($lesson['name']) ?>">
                <div class="lesson-box-image" style="background-image: url('<?php echo $lesson['cover_link'] ?>')">
                  <!--Label-->
                  <span class="label <?php echo display_class_label($con, $lesson['author_id'], $class['id']) ?>"
                    style="position: absolute; top: 5px; right: 5px;">
                    <i class="fa fa-check"></i>
                  </span>
                </div>
                <div class="lesson-box-title">
                  <!--Lesson Name-->
                  <?php echo $lesson['name'] ?>
                </div>
              </a>
            </div>
            <?php endforeach; ?>
            <?php if(count($list_lessons) == 0){
                $width = (user_class_permission($con, $_SESSION['id'], $class['id']) > 0) ? 'auto' : '100%';
                echo "<center style='padding: 20px;width:" . $width . "'><img src='/images/no-content.png' width='100px'>There's no lessons available for this class yet!</center>";
            }
            ?>
        </div>

        <!--Write something to the MURAL FORM-->
        <div class="panel" style="padding: 0">
          <div class="panel-header" style="max-width: 100% !important;">Write Something on the Mural</div>
          <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/post-form.php" ?>
        </div>
        <div id="mural">
          <!--===No Content Warning===-->
          <?php if(count($list_posts) == 0) : ?>
            <center>
              <img src="/images/no-content.png"><br>
              There are no posts on this class' mural yet. Be the first to post!
            </center>
          <?php endif; ?>
          <!--====LIST POSTS====-->
          <?php foreach($list_posts as $post) : ?>
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/post.php"; ?>
          <?php endforeach; ?>
        </div>
        <?php if(count($list_posts) == 3) : ?>
        <!--Load more-->
        <button type="button" class="load-more" onclick="loadMorePosts(this, 'class', <?php echo $class['id'] ?>, <?php echo $post['id'] ?>)">Load More</button>
        <?php endif; ?>
        <br><br>
      </div>
      <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/class-right.php" ?>
  </div>

  <!--Footer-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/footer.php" ?>

  <!--Slick javascript-->
  <script type="text/javascript" src="/assets/libs/slick/slick/slick.min.js"></script>
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
          //Justify images
          $(".post-gallery").justifiedGallery();
          var postText;
          $(document).ready(function(){
            $('.panel-lesson').slick({
              infinite: false,
              slidesToShow: 4,
              slidesToScroll: 1,
              variableWidth: true,
              adaptiveHeight: true
            });
            $(".panel-lesson").css("display", "block");
            postText = $("#post-textarea").emojioneArea({
              pickerPosition: "bottom",
              tonesStyle: "bullet"
            });
            //Trigger Button for the post form
            $('#post-submit').click(function(){
              var button = document.getElementById('post-submit');
              postToClass(<?php echo $class['id'] ?>, button);
            });
          });
          $(window).scroll(function(){
                if ($(this).scrollTop() > 170) {
                    $('.class-menu').css({
                      "position": "fixed",
                      "top": "60px"
                    });
                    $('.class-menu-name').css({
                      "display": "block",
                    });
                } else {
                    $('.class-menu').css({
                      "position": "absolute",
                      "top": "auto",
                      "bottom": "0"
                    });
                    $('.class-menu-name').css({
                      "display": "none",
                    });
                }
          });
  </script>
</body>
</html>
