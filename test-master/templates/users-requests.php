<!DOCTYPE html>
<!--System file (php functions)-->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php" ?>
<?php
  //Verify if user is logged in
  if(!isset($_SESSION['id'])) {
      header("location: /");
      exit;
  }
  if(isset($_GET['class_id']) AND is_numeric($_GET['class_id'])){
    $class = find_class($con, mysqli_real_escape_string($con, $_GET['class_id']));
    if(user_class_permission($con, $_SESSION['id'], $class['id']) > 0){
      $list_member_requests = list_member_requests($con, $class['id']);
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
$list_classes = list_classes($con);
?>
<html>
<head>
  <title><?php echo $class['name'] ?> - Members Requests | iStudy</title>
  <!--Head file (css and libraries)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/head.php" ?>
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
          <?php echo translateDateHalf($class['start_date']) . " - " . translateDateHalf($class['end_date']) ?>
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
        <li class="breadcrumb-item active">Member Requests</li>
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
          <div class="panel-header">Member Requests</div>
          <div class="panel-body">
            <div class="list-group">
              <?php foreach($list_member_requests as $request) : ?>
                <li href="/profile/<?php echo $request['id'] . '-' . linka($request['name']) ?>" class="list-group-item justify-content-between list-profile" id="request-<?php echo $request['requester_id'] ?>">
                  <div><img src="<?php echo $request['picture'] ?>">
                  <?php echo $request['name'] ?> | <?php echo translateDateHalf($request['registry']) ?></div>
                  <div>
                    <div class="btn-group" role="group" aria-label="Basic example">
                      <button type="button" class="btn btn-secondary" onclick="dismissEnrollmentRequest(<?php echo $class['id'] ?>, <?php echo $request['requester_id'] ?>)">Dismiss</button>
                      <button type="button" class="btn btn-primary" onclick="acceptEnrollmentRequest(<?php echo $class['id'] ?>, <?php echo $request['requester_id'] ?>)">Accept</button>
                    </div>
                  </div>
                </li>
              <?php endforeach; ?>
              <?php if(empty($list_member_requests)) : ?>
                <center>
                  <img src="/images/no-content.png" style="width: 110px;"><br>
                  There's no member requests for this class.
                </center>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/class-right.php" ?>
    </div>

  <!--Footer-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/footer.php" ?>

  <script>
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
