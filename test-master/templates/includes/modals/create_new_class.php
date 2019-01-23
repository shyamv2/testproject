<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  if(!isset($_SESSION['id'])){
    echo "Login to continue...";
    exit;
  }
?>
<link rel="stylesheet" href="/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#code">Use Code</a>
    </li>
    <?php if($_SESSION['type'] == 1) : ?>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#create">Create New</a>
    </li>
    <?php endif; ?>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div id="code" class="container tab-pane active"><br>
      <form id="useCodeToEnroll">
        <!--Code-->
        <label>
          <b>Class Code</b>
          <input type="text" name="code" id="code" placeholder="Paste the code provided by your teacher here...">
        </label>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" name="send" id="send" onclick="enroll(this)">Enroll</button>
        </div>
      </form>
    </div>
    <?php if($_SESSION['type'] == 1) : ?>
    <div id="create" class="container tab-pane fade"><br>
      <form id="create-class" method="POST">
        <!--Class Name-->
        <label>
          <b>Class Name</b>
          <input type="text" name="name" id="name" placeholder="Write the name or title of the class here...">
        </label>
        <!--Description-->
        <label>
          <b>Description</b>
          <textarea type="text" name="description" placeholder="Briefly write about this class..."></textarea>
        </label>
        <div class="row">
          <!--Start-->
          <div class="col-sm-6">
            <label>
              <b>When it begins</b>
              <input data-provide="datepicker" data-date-format="yyyy-mm-dd" class="form-control" name="start" placeholder="Pick a date" min="<?php echo date('Y-m-d', strtotime('yesterday')) ?>">
            </label>
          </div>
          <!--End-->
          <div class="col-sm-6">
            <label>
              <b>When it ends</b>
              <input data-provide="datepicker" data-date-format="yyyy-mm-dd" class="form-control" name="end" placeholder="Pick a date"  min="<?php echo date('Y-m-d', strtotime('yesterday')) ?>">
            </label>
          </div>
        </div>
        <!--Privacy-->
        <label>
          <b>Privacy</b>
          <label class="custom-control custom-radio" style="display: inline">
            <input id="radio3" name="privacy" type="radio" class="custom-control-input" value="1">
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description" data-toggle="tooltip" data-animation="false" title="Your class will not appear on search and one will need the code to enroll (you still have to accept their request)">Private</span>
          </label>
          <label class="custom-control custom-radio" style="display: inline">
            <input id="radio3" name="privacy" type="radio" class="custom-control-input" value="0">
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description" data-toggle="tooltip" data-animation="false" title="Your class will appear on search and anyone can enroll without a code (you still have to accept their request)">Public</span>
          </label>
        </label>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" name="send" id="send" onclick="createClassSubmit(this)">Create Class</button>
        </div>
      </form>
    </div>
    <?php endif; ?>
  </div>
  <script src="/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
  <script>
    $(function(){
      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>
