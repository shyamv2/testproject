<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  if(!isset($_SESSION['id'])){
    echo "Login to continue...";
    exit;
  }
  if(isset($_GET['data']) AND is_numeric($_GET['data'])){
    $class_id = mysqli_real_escape_string($con, $_GET['data']);
  }
  else{
    exit;
  }
?>
<link rel="stylesheet" href="/bootstrap-datepicker/css/bootstrap-datepicker.min.css">

      <form id="create-assignment" method="POST">
        <!--Title-->
        <label>
          <b>Title</b>
          <input type="text" name="title" id="title" placeholder="Write the title of the assignment here...">
        </label>
        <!--Description-->
        <label>
          <b>Description</b>
          <textarea type="text" name="description" placeholder="Describe the assignment here..."></textarea>
        </label>
        <div class="row">
          <!--Start-->
          <div class="col-sm-6">
            <label>
              <b>Due Date</b>
              <input type="text" data-provide="datepicker" data-date-format="yyyy-mm-dd" class="form-control" name="deadline" placeholder="Pick a date" min="<?php echo date('Y-m-d', strtotime('yesterday')) ?>">
            </label>
          </div>
          <!--Educoin_value-->
          <div class="col-sm-6">
            <label>
              <b><img src="/images/coin.gif" style="width: 10px"> Educoins for completing</b>
              <select name="educoin_value">
                <option value="0.5">0.50</option>
                <option value="1.0">1.00</option>
                <option value="1.5">1.50</option>
                <option value="2.0">2.00</option>
                <option value="2.5">2.50</option>
                <option value="3.0">3.00</option>
                <option value="3.5">3.50</option>
                <option value="4.0">4.00</option>
                <option value="4.5">4.50</option>
                <option value="5.0">5.00</option>
              </select>
            </label>
          </div>
        </div>


        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" name="send" id="send" onclick="createAssignmentSubmit(this, <?php echo $class_id ?>)">Create Assignment</button>
        </div>
      </form>

  <script src="/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
  <script>


    $(function(){
      $('[data-toggle="tooltip"]').tooltip();
    });

    //Submit form CREATE NEW ASSIGNMENT
    function createAssignmentSubmit(button, classId){
      $("#create-assignment").ajaxSubmit({
        dataType: 'json',
        data: {class_id: classId},
        url: '/src/ajax-actions/classes/assignments/new_assignment.php',
        success: function(data){
          if(data['error'] == true){
            showMsg(data['msg_error']);
            button.disabled = false;
          }
          else{
            button.disabled = true;
            showMsg("Everything went okay! Redirectiong...");
            setTimeout(location.href = data['link'], 3000);
          }
        },
        beforeSend: function(){
          button.disabled = true;
        },
        error: function(data){
          alert("Something went wrong...");
          button.disabled = false;
        },
        type: 'POST'
      });
    }
  </script>
