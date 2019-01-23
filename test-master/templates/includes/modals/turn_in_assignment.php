<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  if(!isset($_SESSION['id'])){
    echo "Login to continue...";
    exit;
  }
  if(isset($_GET['data']) AND is_numeric($_GET['data'])){
    $assignment_id = mysqli_real_escape_string($con, $_GET['data']);
  }
  else{
    exit;
  }
?>

      <form id="turn-in-assignment" method="POST" enctype="multipart/form-data">
        <!--Plain Text-->
        <label>
          <b>Plain Text</b>
          <textarea type="text" name="plain_text" placeholder="Write what is asked or paste links to external files here..."></textarea>
          <small id="fileHelp" class="form-text text-muted">Please, be careful when submitting your work because you won't have the chance to delete or edit it later.</small>
        </label>

        <!--<label>
          <b>Upload Files</b>
          <input type="file" id="files" name="files[]" multiple>
          <small id="fileHelp" class="form-text text-muted">Your files can't be bigger than 50MB.</small>
        </label>-->

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" name="send" id="send" onclick="turnInAssignmentSubmit(this, <?php echo $assignment_id ?>)">
            Turn In
          </button>
        </div>
      </form>

  <script>


    $(function(){
      $('[data-toggle="tooltip"]').tooltip();
    });

    //Submit form TURN IN ASSIGNMENT
    function turnInAssignmentSubmit(button, assignmentId){
      $("#turn-in-assignment").ajaxSubmit({
        dataType: 'json',
        data: {assignment_id: assignmentId},
        url: '/src/ajax-actions/classes/assignments/turn_in_assignment.php',
        success: function(data){
          if(data['error'] == true){
            showMsg(data['msg_error']);
            button.disabled = false;
          }
          else{
            button.disabled = true;
            showMsg("Everything went okay!");
            setTimeout(location.reload(), 3000);
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
