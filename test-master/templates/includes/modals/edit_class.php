<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  if(isset($_GET['data']) AND is_numeric($_GET['data'])){
    $class_id = mysqli_real_escape_string($con, $_GET['data']);
    $class = find_class($con, $class_id);
  }else{
    exit;
  }
?>
<link rel="stylesheet" href="/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<form id="edit-class" method="POST">
  <!--Class Name-->
  <label>
    <b>Class Name</b>
    <input type="text" name="name" id="name" placeholder="Write the name or title of the class here..." value="<?php echo $class['name'] ?>">
  </label>
  <!--Description-->
  <label>
    <b>Description</b>
    <textarea type="text" name="description" placeholder="Briefly write about this class..."><?php echo $class['description'] ?></textarea>
  </label>
  <div class="row" style="padding: 0 10px;">
    <!--Start-->
    <div class="col-sm-6">
      <label>
        <b>When it begins</b>
        <input data-provide="datepicker" data-date-format="yyyy-mm-dd" class="form-control" name="start" placeholder="Pick a date" min="<?php echo date('Y-m-d', strtotime('yesterday')) ?>" value="<?php echo $class['start_date'] ?>">
      </label>
    </div>
    <!--End-->
    <div class="col-sm-6">
      <label>
        <b>When it ends</b>
        <input data-provide="datepicker" data-date-format="yyyy-mm-dd" class="form-control" name="end" placeholder="Pick a date"  min="<?php echo date('Y-m-d', strtotime('yesterday')) ?>" value="<?php echo $class['end_date'] ?>">
      </label>
    </div>
  </div>
  <!--Privacy-->
  <label>
    <b>Privacy</b>
    <label class="custom-control custom-radio" style="display: inline">
      <input id="radio3" name="privacy" type="radio" class="custom-control-input" value="1" <?php echo che(1, $class['privacy']) ?>>
      <span class="custom-control-indicator"></span>
      <span class="custom-control-description" data-toggle="tooltip" data-animation="false" title="Your class will not appear on search and one will need the code to enroll (you still have to accept their request)">Private</span>
    </label>
    <label class="custom-control custom-radio" style="display: inline">
      <input id="radio3" name="privacy" type="radio" class="custom-control-input" value="0" <?php echo che(0, $class['privacy']) ?>>
      <span class="custom-control-indicator"></span>
      <span class="custom-control-description" data-toggle="tooltip" data-animation="false" title="Your class will appear on search and anyone can enroll without a code (you still have to accept their request)">Public</span>
    </label>
  </label>
  <input type="number" style="display: none" value="<?php echo $class['id'] ?>" name="class_id">
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary" name="send" id="send" onclick="editClassSubmit(this)">Save</button>
  </div>
</form>
  <script src="/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
  <script>
    $(function(){
      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>
