<?php
  $user_submitted_assignment = get_submitted_assignment($con, $assign['id'], $_SESSION['id']);
 ?>

<div class="row row-content" style="width: 80%">
  <?php $num_submitted_assignments = num_submitted_assignments($con, $assign['id']); ?>
  <div class="col-sm-6">
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
      <br>
      <?php if(!isset($user_submitted_assignment)) : ?>
        <!--Turn in-->
        <button type="button" class="btn btn-primary" style="float: right" data-toggle="modal" data-target="#turnInAssignment"
        onclick="loadModal('turn_in_assignment.php', 'turnInAssignment', this, <?php echo $assign['id'] ?>, false)"><i class="fa fa-angle-up"></i> Turn in</button>
      <?php else: ?>
        <small style="float: right">Already turned in</small>
      <?php endif ;?>
      <!--Educoin-->
      <div class="educoin">
        <img src="/images/coin.gif">
        <?php echo $assign['educoin_value'] ?>
      </div>

    </div>

    <?php if(isset($user_submitted_assignment)) : ?>
      <h6>Your work</h6>
      <div class="panel" style="padding: 0">
        <div class="panel-header">
          <img src="<?php echo $_SESSION['picture'] ?>" style="width: 30px; float: left: margin-right: 100px; border-radius: 100%">
          <?php echo $_SESSION['name'] ?> | <small><?php echo time_elapsed_string($user_submitted_assignment['registry']) ?></small>
        </div>
        <div class="panel-body" style="padding: 20px">
          <?php echo linkify_str(nl2br($user_submitted_assignment['plain_text'])) ?>
        </div>
      </div>
    <?php endif ;?>
  </div>

    <div class="col-sm-6">
      <div class="panel" style="padding: 0">
        <div class="panel-header">Do you have any questions or observations?</div>
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/post-form.php"; ?>
      </div>
      <div id="mural">
        <!--====LIST POSTS====-->
        <?php foreach($list_posts as $post) : ?>
          <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/post.php"; ?>
        <?php endforeach; ?>
      </div>
    </div>
</div>
