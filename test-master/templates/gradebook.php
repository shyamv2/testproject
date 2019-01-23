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
    <div class="row row-content" style="width: 95%">
      <div class="col-sm-12 panel" style="padding: 0  !important; max-width: 100%">
        <div class="panel-header">
          <!--Download Spreadsheet-->
          <div style="float: right; position:relative; top: -5px">
            <?php if(user_class_permission($con, $_SESSION['id'], $class['id']) > 0): ?>
            <button type="button" onclick="sendFinalReportEmail(<?php echo $class['id'] ?>, this)" class="panel-header-button"
            style="font-size: 16px;"><i class="fa fa-envelope" data-toggle="tooltip" title="Send Email"></i> Send Email</button>
            <?php endif; ?>
            <button type="button" onclick="location.href = '/attachments/final-report.php?class_id=<?php echo $class['id'] ?>'" class="panel-header-button"
            style="font-size: 20px;"><i class="fa fa-download" data-toggle="tooltip" title="Download Spreadsheet"></i></button>
          </div>
          <i class="fa fa-book"></i> Gradebook
        </div>
        <fieldset <?php echo (user_class_permission($con, $_SESSION['id'], $class['id']) < 2) ? 'disabled' : '' ?>
          style="max-width: 100% !important; min-width: 100%; position: absolute; overflow-x: auto; background-color: white">
          <table class="table table-bordered" style="min-width: 100%">
            <thead>
              <tr style="background-color: #fafafa">
                <th scope="col" class="headcol">Students</th>
                <?php foreach($list_assignments as $assign) : ?>
                  <th scope="col" data-toggle="tooltip" title="<?php echo $assign['title'] ?>"><?php echo str_limit($assign['title'], 4) ?>...</th>
                <?php endforeach; ?>
                <th scope="col">Final Grade</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($list_class_members as $member) : ?>
                <?php if(user_class_permission($con, $_SESSION['id'], $class['id']) > 1 OR $_SESSION['id'] == $member['id']) : ?>
                  <tr>
                    <td style="min-width: 200px">
                      <button type="button" style="background-color: transparent; border: 0;"
                      data-toggle="modal" data-target="#memberProfile" onclick="loadModal('member_profile.php', 'memberProfile', this, <?php echo $member['enrollment_id'] ?>, true)">
                        <img src="<?php echo $member['picture'] ?>" style="width: 20px; border-radius: 100%; float: left; margin-right: 10px;">
                        <?php echo str_limit($member['name'], 2) ?>
                      </button>
                    </td>

                      <?php foreach($list_assignments as $assign) : ?>
                        <?php $a_grade = find_user_assignment_grade($con, $assign['id'], $class['id'], $member['id']); ?>
                        <td>
                          <input type="text" class="input-grade" value="<?php echo $a_grade ?>"
                          onkeyup="updateAssignmentGrade(<?php echo $member['id'] ?>, <?php echo $assign['id'] ?>, this.value)"
                          onpaste="updateAssignmentGrade(<?php echo $member['id'] ?>, <?php echo $assign['id'] ?>, this.value)">
                        </td>
                      <?php endforeach; ?>

                    </td>
                    <?php $f_grade = find_user_final_grade($con,  $class['id'], $member['id']); ?>
                    <td><input type="text" class="input-grade" value="<?php echo $f_grade ?>"
                      onkeyup="updateFinalGrade(<?php echo $member['id'] ?>, this.value)"
                      onpaste="updateFinalGrade(<?php echo $member['id'] ?>, this.value)"></td>
                  </tr>
                <?php endif; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
      </div>
    </div>

  <!--Footer-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/footer.php" ?>
<?php if(user_class_permission($con, $_SESSION['id'], $class['id']) > 1) : ?>
  <!--MEMBER PROFILE-->
  <div class="modal fade" id="memberProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog personal-space" role="document">
      <div class="modal-content personal-space">
        <div class="modal-header" style="background: #0091f7; padding: 0; border-bottom: 0">
          <h5></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="color: rgba(255,255,255,0.6); margin-top: 20px; margin-right: 20px">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-js" style="background-color: #fafafa; padding: 0 !important">
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
  <script type="text/javascript" src="/assets/libs/ckeditor/ckeditor.js"></script>

  <script type="text/javascript">

          var classId = <?php echo $class['id'] ?>;

  </script>
</body>
</html>
