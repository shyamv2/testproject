<?php
  $list_submitted_assignments = list_submitted_assignments($con, $assign['id']);
?>
<?php $num_submitted_assignments = num_submitted_assignments($con, $assign['id']); ?>
<style>
.content{
  position: absolute !important;
  height: 100%;
}
</style>
<div class="row row-content" style="width: 100%; margin-top: 0 !important; position: absolute; top: 45px; right: 0; left: 0; bottom: 0; max-height: 100%">

<!--List Students-->
<div class="panel" style="width: 25%; position: relative; min-height: 100%; bottom: 0; left: 0; top: 0; overflow-y: scroll; padding: 0 !important; border: 0">
  <div class="list-group assignment-list" >
    <?php $i = 1; ?>
    <?php foreach($list_class_members as $user): ?>
      <?php $a_grade = find_user_assignment_grade($con, $assign['id'], $class['id'], $user['id']); ?>
      <?php $submitted = get_submitted_assignment($con, $assign['id'], $user['id']); ?>
      <!--Chat-List-->
      <a href="javascript: void(0)" class="list-group-item list-group-item-action flex-column align-items-start<?php echo ($i == 1) ? ' active' : '' ?>" onclick="switchMembers(this, <?php echo $user['id'] ?>)" style="height: auto">
        <div class="d-flex w-100 justify-content-between">
          <!--HEAD - Photo and Name-->
          <h6 class="mb-1" style="font-size: 13px">
            <!--User Picture-->
            <img src="<?php echo $user['picture'] ?>" style="width: 20px; height: 20px; border-radius: 100%">
            <!--User Name-->
            <?php echo $user['name'] ?>
            <span class="badge badge-warning" style="background: #0091f7"><?php echo (count($submitted) > 0) ? 'Submitted' : 'Not Submitted' ?></span>
          </h6>
          <!--Last Message Date-->
          <small style="font-size: 11px"><input type="text" class="input-grade" value="<?php echo $a_grade ?>"
            onkeyup="updateAssignmentGrade(<?php echo $user['id'] ?>, <?php echo $assign['id'] ?>, this.value)"
            onpaste="updateAssignmentGrade(<?php echo $user['id'] ?>, <?php echo $assign['id'] ?>, this.value)"></small>
        </div>
      </a>
      <?php if($i == 1) : ?>
        <script> var firstUser = <?php echo $user['id'] ?>; </script>
      <?php endif; ?>
      <?php $i++; ?>
    <?php endforeach; ?>
    <?php if(count($list_class_members) == 0) {
      echo "<div style='padding: 20px'>There's no members in this class yet. Share the class code to get people in: <br><h6>" . $class['code'] . "</h6></div>";
    }
    ?>
  </div>
</div>
<div class="col-sm-9" style="padding: 0; margin: 0; height: 100%; margin-left: 25%; position: fixed;">
  <!--About Assignment-->
  <div class="panel assignment" style="padding: 10px 15px; border: 0 !important; box-shadow: 0px 5px 15px 2px rgba(0,0,0,0.05);">
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
    <div class="title" style="font-size: 16px; font-weight:  600 !important"><?php echo $assign['title'] ?>
      <!--Educoin-->
      <div class="educoin">
        <img src="/images/coin.gif">
        <?php echo $assign['educoin_value'] ?>
      </div>
    </div>
    <div class="description" style="font-size: 13px;"><?php echo linkify_str(str_limit($assign['description'], 20)) ?>...</div>
  </div>

  <!--User Submitted Assignment-->
    <div style="padding: 10px 20px;position: fixed;bottom: 0;top: 195px;overflow-y: auto; min-width: 75%" id="panel-submitted-assignments">
    </div>
  </div>
</div>


<script>

function switchMembers(click, member_id){
  $(".list-group-item").removeClass("active");
  $(click).addClass("active");
  loadSubmittedAssignment(member_id);
}
function loadSubmittedAssignment(member_id){
  $.ajax({
    data: {member_id: member_id, assignment_id: assignment_id},
    url: '/templates/includes/submitted_assignment.php',
    success: function(data){
      $('#panel-submitted-assignments').html(data);
    },
    beforeSend: function(){
      $('#panel-submitted-assignments').html("<img src='/images/loading.svg'>");
    },
    error: function(){
      showMsg("Something went wrong!");
    },
    type: 'POST'
  });
}

</script>
