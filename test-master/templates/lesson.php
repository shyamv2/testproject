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
    $lesson = find_lesson($con, mysqli_real_escape_string($con, $_GET['id']));
    $class = find_class($con, $lesson['class_id']);
    $author = find_user($con, $lesson['author_id']);
    if(is_class_member($con, $_SESSION['id'], $class['id'])){
      $list_class_members = list_class_members($con, $class['id'], 0);
      $list_tutors = list_class_members($con, $class['id'], 1);
      $list_administrators = list_class_members($con, $class['id'], 2);
      $n_member_requests = number_of_member_requests($con, $class['id']);
      $list_lessons = list_class_lessons($con, $class['id']);
      $list_posts = list_posts($con, 'lesson', $lesson['id']);
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
<html>
<head>
  <title><?php echo $lesson['name'] ?> by <?php echo $author['name'] ?> | iStudy</title>
  <!--Head file (css and libraries)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/head.php" ?>
  <!--Slick (slider)-->
  <link rel="stylesheet" type="text/css" href="/assets/libs/slick/slick/slick.css"/>
  <link rel="stylesheet" type="text/css" href="/assets/libs/slick/slick/slick-theme.css"/>

  <!--Aligning the 'no-content' center in case of no lesson results-->
  <?php if(count($list_lessons) == 0) : ?>
    <style>
      .slick-slider .slick-track, .slick-slider .slick-list{
        width: 100%;
      }
    </style>
  <?php endif; ?>
</head>
<body>
  <!--MENUS (TOP and LEFT)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/menu-top.php" ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/menu-left.php" ?>

  <!--CONTENT-->
  <div class="content">
    <!--Page TITLE-->
    <div class="page-title page-title-class">
      <?php if(user_class_permission($con, $_SESSION['id'], $class['id']) == 0) : ?>
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
        <li class="breadcrumb-item"><a href="/class/<?php echo $class['id'] . '-' . linka($class['name']) ?>"><?php echo $class['name'] ?></a></li>
        <li class="breadcrumb-item active"><?php echo $lesson['name'] ?></li>
      </ol>
    </center>
      <!--Class Menu-->
      <div class="class-menu">
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
            <?php foreach($list_lessons as $lesson_d) : ?>
            <?php if($lesson_d['type'] == 2){
              $lesson_d['cover_link'] = "https://img.youtube.com/vi/" . $lesson_d['cover_link'] . "/0.jpg";
              $lesson_d['link'] = "/lesson/" . $lesson_d['id'] . "-" . linka($lesson_d['name']);
            }
            ?>
            <div class="lesson-box">
              <a href="<?php echo '/lesson/' . $lesson_d['id'] . '-' . linka($lesson_d['name']) ?>">
                <div class="lesson-box-image" style="background-image: url('<?php echo $lesson_d['cover_link'] ?>')">
                  <!--Label-->
                  <span class="label <?php echo display_class_label($con, $lesson_d['author_id'], $class['id']) ?>"
                    style="position: absolute; top: 5px; right: 5px;">
                    <i class="fa fa-check"></i>
                  </span>
                </div>
                <div class="lesson-box-title"><?php echo $lesson_d['name'] ?></div>
              </a>
            </div>
            <?php endforeach; ?>
            <?php if(count($list_lessons) == 0){
                echo "<center style='padding: 20px; width: 100% !important;'><img src='/images/no-content.png' width='100px'>There's no lessons available for this class yet!</center>";
            }
            ?>
        </div>
      <!--Lesson-->
      <div class="panel" style="padding: 0">
        <div class="panel-header">
          <!--Options-->
          <?php if($author['id'] == $_SESSION['id']) : ?>
          <div style="float: right">
            <div class="dropdown">
              <button class="btn btn-normal" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-v"></i>
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#" onclick="deleteLesson(<?php echo $lesson['id'] ?>)"><i class="fa fa-remove"></i> Delete</a>
                <a class="dropdown-item" href="javascript:void(0)" onclick="loadModal('edit_lesson.php', 'editLesson', this, <?php echo $lesson['id'] ?>)" data-toggle="modal" data-target="#editLesson"><i class="fa fa-edit"></i> Edit</a>
              </div>
            </div>
          </div>
          <?php endif; ?>
          <!--Label of Lesson - Author and Title-->
          <small>Lesson by <b><?php echo $author['name'] ?></b></small>
          <h4 style="text-transform: capitalize"><?php echo $lesson['name'] ?></h4>
        </div>
        <div class="panel-body" style="padding: 20px; padding-top: 0">
          <!--Cover-->
          <?php if($lesson['type'] == 1) : ?>
            <img src="<?php echo $lesson['cover_link'] ?>" style="width: 100%">
          <?php else: ?>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $lesson['cover_link'] ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
          <?php endif; ?>
          <br><br>
          <!--Skills Expected-->
          <h6>What you will learn:</h6>
          <p style="color: #aaa; font-size: 13px;"><?php echo $lesson['skills'] ?></p>
          <!--Article-->
          <?php echo $lesson['article'] ?>
        </div>
      </div>


      <!--Lesson Discussion-->
      <div class="panel" style="padding: 0">
        <div class="panel-header" style="max-width: 100% !important;">Write something to the discussion</div>
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/post-form.php" ?>
      </div>
      <div id="mural">
        <!--====LIST POSTS====-->
        <?php foreach($list_posts as $post) : ?>
          <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/post.php"; ?>
        <?php endforeach; ?>
      </div>
      <?php if(count($list_posts) == 3) : ?>
        <!--Load more-->
        <button type="button" class="load-more" onclick="loadMorePosts(this, 'lesson', <?php echo $lesson['id'] ?>, <?php echo $post['id'] ?>)">Load More</button>
      <?php endif; ?>
      </div>
      <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/class-right.php"; ?>
  </div>

  <!--Footer-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/footer.php" ?>

  <!--Slick javascript-->
  <script type="text/javascript" src="/slick/slick/slick.min.js"></script>

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

        $(document).ready(function(){
          //Justify images
          $(".post-gallery").justifiedGallery();

            $('.panel-lesson').slick({
              infinite: false,
              slidesToShow: 4,
              slidesToScroll: 1,
              variableWidth: true,
              adaptiveHeight: true
            });
            $(".panel-lesson").css("display", "block");
          });
          var postText;
            postText = $("#post-textarea").emojioneArea({
              pickerPosition: "bottom",
              tonesStyle: "bullet"
            });
            //Trigger Button for the post form
            $('#post-submit').click(function(){
              var button = document.getElementById('post-submit');
              postToLesson(<?php echo $lesson['id'] ?>, button);
            });
  </script>

  <!--EDIT LESSON-->
  <div class="modal fade" id="editLesson" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-edit"></i> Edit Lesson</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-js">
        </div>
      </div>
    </div>
  </div>
</body>
</html>
