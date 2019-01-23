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
    $class = find_class($con, $_GET['id']);
    if(is_class_member($con, $_SESSION['id'], $class['id'])){
      $list_class_members = list_class_members($con, $class['id'], 0);
      $list_tutors = list_class_members($con, $class['id'], 1);
      $list_administrators = list_class_members($con, $class['id'], 2);
      $n_member_requests = number_of_member_requests($con, $class['id']);
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
<html>
<head>
  <title><?php echo $class['name'] ?> - Members | iStudy</title>
  <!--Head file (css and libraries)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/head.php" ?>
  <style>
  .class-menu-members{
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
        <li class="breadcrumb-item active">Class Members</li>
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

      <!--Lesson-->
      <div class="panel" style="padding: 0">
        <div class="panel-header">
          <!--Download Spreadsheet-->
          <div style="float: right; position:relative; top: -5px">
            <button type="button" onclick="location.href = '/attachments/members-spreadsheets.php?class_id=<?php echo $class['id'] ?>'" class="panel-header-button"
            style="font-size: 20px;"><i class="fa fa-download" data-toggle="tooltip" title="Download Spreadsheet"></i></button>
          </div>
          Class Members
        </div>
        <div class="panel-body" style="padding: 20px;">
          <ul class="member-list">
          <h6>Administrators</h6>
          <?php foreach($list_administrators as $member) : ?>
              <li id="member-<?php echo $member['id'] ?>">
                <a href="/profile/<?php echo $member['id'] . '-' . linka($member['name']) ?>">
                  <img src="<?php echo $member['picture'] ?>">
                  <?php echo str_limit($member['name'], 2); ?>
                </a>
              </li>
          <?php endforeach; ?>


          <?php if(count($list_tutors) > 0) : ?>
            <br>
            <h6>Tutors</h6>
            <?php foreach($list_tutors as $member) : ?>
                <li id="member-<?php echo $member['id'] ?>">
                  <a href="/profile/<?php echo $member['id'] . '-' . linka($member['name']) ?>">
                    <img src="<?php echo $member['picture'] ?>">
                    <?php echo str_limit($member['name'], 2); ?>
                  </a>
                  <?php if(user_class_permission($con, $_SESSION['id'], $class['id']) > 1 AND $member['id'] != $_SESSION['id']) : ?>
                    <div class="btn-group" role="group" aria-label="Basic example" style="float: right">
                      <!--Options-->
                      <div class="dropdown">
                        <button type="button" class="btn btn-normal" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
                        <ul class="dropdown-menu dropdown-menu-right">
                          <a href="#" class="dropdown-item" onclick="removeMemberClass(<?php echo $class['id'] ?>, <?php echo $member['id'] ?>)">
                            <i class="fa fa-times"></i> Remove Member
                          </a>
                          <a href="#" class="dropdown-item" onclick="changeUserPermissionInClass(<?php echo $member['enrollment_id'] ?>, 0)">
                             Make Student
                          </a>
                          <a href="#" class="dropdown-item" onclick="changeUserPermissionInClass(<?php echo $member['enrollment_id'] ?>, 2)">
                            <span class="label teacher"><i class="fa fa-check"></i></span> Make Admin
                          </a>
                        </ul>
                      </div>
                    </div>
                  <?php endif; ?>
                </li>
            <?php endforeach; ?>
          <?php endif; ?>

          <br>
          <h6>Students</h6>
          <?php foreach($list_class_members as $member) : ?>
              <li id="member-<?php echo $member['id'] ?>">
                <a href="/profile/<?php echo $member['id'] . '-' . linka($member['name']) ?>">
                  <img src="<?php echo $member['picture'] ?>">
                  <?php echo str_limit($member['name'], 2); ?>
                </a>
                <?php if(user_class_permission($con, $_SESSION['id'], $class['id']) > 1 AND $member['id'] != $_SESSION['id']) : ?>
                  <div class="btn-group" role="group" aria-label="Basic example" style="float: right">
                    <!--Member profile-->
                    <button type="button" class="btn btn-normal" data-toggle="modal" data-target="#memberProfile" onclick="loadModal('member_profile.php', 'memberProfile', this, <?php echo $member['enrollment_id'] ?>, true)"><i class="fa fa-address-book"></i></button>
                    <!--Options-->
                    <div class="dropdown">
                      <button type="button" class="btn btn-normal" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
                      <ul class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item" onclick="removeMemberClass(<?php echo $class['id'] ?>, <?php echo $member['id'] ?>)">
                          <i class="fa fa-times"></i> Remove Member
                        </a>
                        <a href="#" class="dropdown-item" onclick="changeUserPermissionInClass(<?php echo $member['enrollment_id'] ?>, 1)">
                          <span class="label tutor"><i class="fa fa-check"></i></span> Make Tutor
                        </a>
                        <a href="#" class="dropdown-item" onclick="changeUserPermissionInClass(<?php echo $member['enrollment_id'] ?>, 2)">
                          <span class="label teacher"><i class="fa fa-check"></i></span> Make Admin
                        </a>
                      </ul>
                    </div>
                  </div>
                <?php endif; ?>
              </li>
          <?php endforeach; ?>
          <?php if(count($list_class_members) == 0){
            echo "There's no members in this class yet. Share the class code to get people in: <br><h6>" . $class['code'] . "</h6>";
          } ?>
          </ul>
        </div>
      </div>

      </div>
      <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/class-right.php"; ?>
  </div>

  <!--Footer-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/footer.php" ?>
  <!--Ckeditor javascript-->
  <script type="text/javascript" src="/assets/libs/ckeditor/ckeditor.js"></script>
  <!--====EmojiArea====-->
  <link rel="stylesheet" href="/assets/libs/emojionearea/dist/emojionearea.min.css">
  <script type="text/javascript" src="/assets/libs/emojionearea/dist/emojionearea.min.js"></script>

  <script type="text/javascript">
        $(document).ready(function(){
            $('.panel-lesson').slick({
              infinite: false,
              slidesToShow: 4,
              slidesToScroll: 1,
              variableWidth: true,
              adaptiveHeight: true
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
