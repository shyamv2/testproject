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
  <title><?php echo $class['name'] ?> - About | iStudy</title>
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
  .class-menu-about{
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
        <li class="breadcrumb-item"><a href="/class/<?php echo $class['id'] . '-' . linka($class['name']) ?>"><?php echo $class['name'] ?></a></li>
        <li class="breadcrumb-item active">About</li>
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
        <div class="panel" style="padding: 0">
          <div class="panel-header">
            <?php if(user_class_permission($con, $_SESSION['id'], $class['id']) == 2) : ?>
              <!--Edit-->
              <div style="float: right; position:relative; top: -5px">
                <button type="button" class="panel-header-button"
                  onclick="loadModal('edit_class.php', 'editClass', this)" data-toggle="modal" data-target="#editClass" value="<?php echo $class['id'] ?>">
                  <i class="fa fa-edit" data-toggle="tooltip" title="Edit class info"></i> Edit
                </button>
              </div>
            <?php endif; ?>
            About Class
          </div>
          <div class="panel-body" style="padding: 25px;">
            <form>
              <label>
                <b>Class Name</b>
                  <h6><?php echo $class['name'] ?></h6>
              </label>

              <label>
                <b>Start Date</b>
                <?php echo translateDateHalf(date("Y-m-d", strtotime($class['start_date']))) ?>
              </label>

              <label>
                <b>End Date</b>
                <?php echo translateDateHalf(date("Y-m-d", strtotime($class['end_date']))) ?>
              </label>

              <label>
                <b>Description</b>
                <?php echo $class['description'] ?>
              </label>

              <label>
                <b>Registration</b>
                <?php echo translateDateHalf($class['registry']) ?>
              </label>

              <label>
                <b>Author</b>
                <?php $author = find_user($con, $class['author_id']); ?>
                <?php echo $author['name'] ?>
              </label>
              <?php if(user_class_permission($con, $_SESSION['id'], $class['id']) == 2) : ?>
              <label>
                <b>Options</b>
                <a href="#" onclick="archiveClass(<?php echo $class['id'] ?>)">Archive Class</a><br>
                <a href="#" onclick="deleteClass(<?php echo $class['id'] ?>)">Delete Class</a>
              </label>
              <?php endif; ?>

            </form>
          </div>
        </div>
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

  <script type="text/javascript">
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
