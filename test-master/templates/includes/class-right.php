<div class="col-sm-4" style="padding: 0">
  <!--Agenda-->
  <div class="panel" style="padding: 0">
    <div class="panel-header">
      <!--Calendar-->
      <div style="float: right; position:relative; top: -5px">
        <button type="button" onclick="location.href = '/templates/calendar.php?class_id=<?php echo $class['id'] ?>'" class="panel-header-button"
        style="font-size: 20px;"><i class="fa fa-calendar" data-toggle="tooltip" title="Go to Calendar"></i></button>
      </div>
      Agenda and Calendar
    </div>
    <div class="panel-body">
      <label style="width: 100%;" id="agendaBox">
        <?php if(user_class_permission($con, $_SESSION['id'], $class['id']) > 0) : ?>
          <div type="text" class="agenda" id="agenda"><?php echo $class['agenda'] ?></div>
        <?php else: ?>
          <?php echo $class['agenda'] ?>
        <?php endif; ?>
      </label>
    </div>
  </div>

  <!--Class Code-->
  <div class="panel" style="padding: 0">
    <div class="panel-header">Class Code</div>
    <div style="padding: 20px"><?php echo $class['code'] ?></div>
  </div>


  <?php if(user_class_permission($con, $_SESSION['id'], $class['id']) > 0) : ?>
  <!--Admin Panel-->
  <div class="panel" style="padding: 0">
    <div class="panel-header">Admin Panel</div>
    <div class="panel-body list-group" style="padding: 0">
      <a class="list-group-item list-group-item-action justify-content-between" href="/templates/send-email.php?class_id=<?php echo $class['id'] ?>">
        Send Email
      </a>
      <a class="list-group-item list-group-item-action justify-content-between" href="/templates/users-requests.php?class_id=<?php echo $class['id'] ?>">
        Member Requests
        <span class="badge badge-pill badge-danger"><?php echo $n_member_requests ?></span>
      </a>
    </div>
  </div>
  <?php endif; ?>
</div>

<!--=======================================================-->
                      <!--MODALS-->
<!--=======================================================-->
<!--EDIT CLASS-->
<div class="modal fade" id="editClass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-edit"></i> Edit Class</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modal-js">
      </div>
    </div>
  </div>
</div>


<!--NEW LESSON-->
<div class="modal fade bd-example-modal-lg" id="newLesson" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create New Lesson</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modal-js">
      </div>
    </div>
  </div>
</div>

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



<!--MINI-FIXED-HEADER-->
<div class="class-mini-header">
  <a href="/class/<?php echo $class['id'] . '-' . linka($class['name']) ?>" id="class-mini-name">
    <i class="fa fa-home"></i>
    <?php echo $class['name'] ?>
  </a>
  <!--Options-->
  <?php if(user_class_permission($con, $_SESSION['id'], $class['id']) < 2) : ?>
    <div class="class-options" style="bottom: 5px;">
      <button class="btn-outlined" style="padding: 7px 20px" data-toggle="modal" data-target="#memberProfile" onclick="loadModal('member_profile.php', 'memberProfile', this, <?php echo $user_enrollment_id ?>, true)">My Status</button>
      <div class="dropdown">
        <button type="button" class="btn-outlined" style="padding: 7px 20px" data-toggle="dropdown"><i class="fa fa-gear"></i></button>
        <div class="dropdown-menu dropdown-menu-right">
          <a class="dropdown-item" href="#" onclick="leaveClass(<?php echo $class['id'] ?>)">Leave Class</a>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>




<script src="/assets/libs/tinymce/js/tinymce/tinymce.min.js"></script>
<?php if(user_class_permission($con, $_SESSION['id'], $class['id']) > 0) : ?>
<script>
  //Agenda
  tinymce.init({
    mode : 'exact',
    selector: '#agenda',
    inline: true,
    theme: 'modern',
    skin: 'light',
    menubar: false,
    plugins: 'lists link image media table emoticons advlist autolink',
    toolbar: 'bold italic bullist numlist | link emoticons | ',
    setup: function(editor){
      editor.on('keyup', function(e) {
          updateAgenda(editor.getContent(), <?php echo $class['id'] ?>);
      });
    }
  });
</script>
<?php endif; ?>
