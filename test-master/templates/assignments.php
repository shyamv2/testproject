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
      $user_enrollment_id = get_user_enrollment_id($con, $class['id']);
      $list_assignments = list_class_assignments($con, $class['id']);

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
  <title><?php echo $class['name'] ?> - Assignments | iStudy</title>
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
        <li class="breadcrumb-item active">Assignments</li>
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

        <?php if(user_class_permission($con, $_SESSION['id'], $class['id']) > 1) : ?>
          <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newAssignment" onclick="loadModal('new_assignment.php', 'newAssignment', this, <?php echo $class['id'] ?>, false)">
            <i class="fa fa-plus"></i> New Assignment
          </button>
        <?php endif; ?>

        <a href="/class/<?php echo $class['id'] . '-' . linka($class['name']) ?>/gradebook" class="btn btn-secondary" style="background: white">
          <i class="fa fa-book"></i> GradeBook
        </a>
        <br><br>
        <?php if(count($list_assignments) == 0) : ?>
          <center>
            <img src="/images/no-content.png"><br>
            There are no posts on this class' mural yet. Be the first to post!
          </center>
        <?php endif; ?>

        <!--List Assignments-->
        <?php foreach($list_assignments as $assign) : ?>
          <?php $num_submitted_assignments = num_submitted_assignments($con, $assign['id']); ?>
          <div class="panel assignment">
            <!--Stats-->
            <div class="stats">
              <div class="stat" style="border-right: 1px solid #eee;">
                <span><?php echo $num_submitted_assignments ?></span> DONE
              </div>
              <div class="stat">
                <span><?php echo count($list_class_members) - $num_submitted_assignments ?></span> NOT DONE
              </div>
            </div>

            <!--Deadline-->
            <div class="deadline">Due Date: <span><?php echo translateDateHalf($assign['deadline']) ?></span></div>

            <!--Title and Description-->
            <div class="title"><?php echo $assign['title'] ?></div>
            <div class="description"><?php echo linkify_str(str_limit($assign['description'], 40)) ?>...</div>

            <div style="float: right">
              <?php if(user_class_permission($con, $_SESSION['id'], $class['id']) > 1) : ?>
                <!--Options-->
                <div class="dropdown" style="display: inline; cursor: pointer">
                  <a class="btn btn-secondary" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
                  <ul class="dropdown-menu dropdown-menu-right">
                    <li class="dropdown-item" onclick="deleteAssignment(<?php echo $assign['id'] ?>)"><i class="fa fa-remove"></i> Delete</li>
                    <!--<li class="dropdown-item" onclick="prepareEditPost(<?php echo $assign['id'] ?>)"><i class="fa fa-edit"></i> Edit</li>-->
                  </ul>
                </div>
              <?php endif; ?>
              <!--Open-->
              <a href="/assignment/<?php echo $assign['id'] ?>" target="_black" class="btn btn-secondary">Open <i class="fa fa-external-link"></i></a>
            </div>
            <!--Educoin-->
            <div class="educoin">
              <img src="/images/coin.gif">
              <?php echo $assign['educoin_value'] ?>
            </div>

          </div>
        <?php endforeach; ?>
      </div>

      <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/class-right.php" ?>
    </div>

  <!--Footer-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/footer.php" ?>

  <!--NEW ASSIGNMENT-->
  <div class="modal fade" id="newAssignment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> New Assignment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-js">
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>

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
