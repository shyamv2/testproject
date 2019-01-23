<?php
  include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  if(isset($_SESSION['id'])){
      if(isset($_GET['data']) AND is_numeric($_GET['data'])){
        $enrollment_id = mysqli_real_escape_string($con, $_GET['data']);
        $enrollment = find_enrollment($con, $enrollment_id);
        $class = find_class($con, $enrollment['class_id']);
        if((user_class_permission($con, $_SESSION['id'], $class['id']) > 1
        OR $_SESSION['id'] == $enrollment['member_id'])
        OR !no_existant_account_association($con, $enrollment['member_id'], $_SESSION['id'])){
          $member = find_user($con, $enrollment['member_id']);
          $member['last_seen'] = time_elapsed_string(user_last_seen($con, $member['id']));
          $list_cards = list_cards($con, $enrollment['enrollment_id']);
          //List BADGES
          $list_badges = list_badges($con);
          //User Badge
          $user_badge = get_user_badge($con, $enrollment_id);
          $list_assignments = list_class_assignments($con, $class['id']);
        }
        else{
          echo "No Permission"; exit;
        }
      }
  }
  else{
    echo "Log in to continue..."; exit;
  }
  if(user_class_permission($con, $_SESSION['id'], $class['id']) > 1){
    $ts = " teacher-side"; //ts = teacher side
  }
  else{
    $ts = "";
  }
?>


  <!--Profile-->
  <div class="member-profile-id">

    <div class="list-badges">
      <?php foreach($list_badges as $badge) : ?>
        <button type="button" class="badge<?php echo $ts ?> <?php echo ($user_badge == $badge['id']) ? 'active' : '' ?>"
          data-toggle="tooltip" data-placement="bottom" data-animation="false"
        title="<?php echo $badge['name'] ?>"
        id="badge-<?php echo $badge['id'] ?>">
          <img src="<?php echo $badge['icon'] ?>">
        </button>
      <?php endforeach; ?>
    </div>


    <img src="<?php echo $member['picture'] ?>">
    <div class="member-profile-id-name">
      <!--Name-->
      <h4><?php echo $member['name'] ?></h4>
      <!--Last Seen-->
      <small>Last Seen <?php echo $member['last_seen'] ?></small>
      <!--Message Button-->
      <?php if(user_class_permission($con, $_SESSION['id'], $class['id']) > 1) : ?>
        <a href="/messages?chatwith=<?php echo $member['id'] ?>" class="btn btn-secondary" style="color: white !important; border-color: transparent !important"><i class="fa fa-envelope"></i></a>
      <?php endif; ?>
    </div>
  </div>




  <div class="row" style="padding: 0; width: 100%; margin: 0; position: fixed; <?php echo (user_class_permission($con, $_SESSION['id'], $class['id']) > 1) ? 'bottom: 50px; top: 112px' : 'bottom: 0; top: 100px' ?>">

      <div class="col-sm-4" style="overflow-y: auto; padding: 0 !important">
        <div class="personal-space-menu">
          <ul class="nav-tabs" style="border-bottom: 0">
            <li class="active" data-toggle="tab" href="#cards"><i class="fa fa-clone"></i> Cards</li>
            <li data-toggle="tab" href="#assignments"><i class="fa fa-tasks"></i> Assignments</li>
            <li data-toggle="tab" href="#grades"><i class="fa fa-book"></i> Grades</li>
            <li data-toggle="tab" href="#activity"><i class="fa fa-history"></i> Activity</li>
            <li data-toggle="tab" href="#notes"><i class="fa fa-sticky-note"></i> Notes</li>
          </ul>
        </div>
      </div>

  <div class="col-sm-8 tab-content" style="overflow-y: auto; height: 100%; padding: 30px 50px !important;">

    <!--==================================================================================-->
    <!--CARDS-->
    <!--==================================================================================-->
    <div id="cards" class="tab-pane active">
    <!--Create new Card Post-->
    <div class="panel" style="padding: 0; margin: 0">
      <div class="panel-header"><i class="fa fa-clone"></i> Create a new Card</div>
      <div class="panel-body">
        <textarea type="text" placeholder="A Card can be everything. Examples of cards: questions, requests, assignments..." id="card_textarea" class="mural-textarea"></textarea>
        <button type="button" class="btn btn-primary" style="margin-top: 10px;" onclick="postCard(this, <?php echo $enrollment_id ?>)"> Post Card</button>
      </div>
    </div>

    <div id="list-of-cards">
      <?php if(count($list_cards) == 0) : ?>
        <center>
          <img src="/images/no-content.png"><br>
          There's no cards yet. Be the first to post one!
        </center>
      <?php endif; ?>
      <?php foreach($list_cards as $card) : ?>
      <!--Card Modal-->
        <div class="card" id="card-<?php echo $card['id'] ?>">
          <!--Card-->
          <div class="card-block">
            <h4 class="card-title">
              <?php if($_SESSION['id'] == $card['author_id']) : ?>
              <div style="float: right">
                <button class="btn btn-normal" onclick="deleteCard(<?php echo $card['id'] ?>)"><i class="fa fa-remove"></i></button>
              </div>
              <?php endif; ?>
              <img src="<?php echo $card['author_picture'] ?>"><?php echo $card['author_name'] ?>
            </h4>
            <h6 class="card-subtitle mb-2"><small><?php echo time_elapsed_string($card['registry']) ?></small></h6>
            <div class="card-text"><?php echo $card['card'] ?></div>
          </div>
          <!--List Replies-->
          <ul class="list-group list-group-flush" id="list-replies-<?php echo $card['id'] ?>">
            <?php $list_card_replies = list_cards_replies($con, $card['id']); ?>
            <?php foreach($list_card_replies as $card_reply) : ?>
            <li class="list-group-item">
              <div class="d-flex w-100 justify-content-between">
                <h4 class="card-title"><?php echo $card_reply['author_name'] ?></h4>
                <small style="font-size: 13px;"><?php echo time_elapsed_string($card_reply['registry']) ?></small>
              </div>
              <p class="mb-1" style="overflow-wrap: break-word; word-break: break-word;"><?php echo $card_reply['reply'] ?></p>
            </li>
            <?php endforeach; ?>
          </ul>
          <!--Reply Form-->
          <div class="card-footer">
            <textarea type="text" placeholder="Type a reply to this card..." id="reply-textarea-<?php echo $card['id'] ?>"></textarea>
            <button type="button" class="btn btn-secondary" style="margin-top: 5px; float: right" onclick="replyCardSubmit(this, <?php echo $card['id'] ?>)">Reply</button>
          </div>
        </div>
        <script>CKEDITOR.inline("reply-textarea-<?php echo $card['id'] ?>")</script>
      <?php endforeach; ?>
      </div>
    </div>


      <!--==================================================================================-->
      <!--ASSIGNMENTS-->
      <!--==================================================================================-->
      <div id="assignments" class="tab-pane">
        <!--List Assignments-->
        <?php foreach($list_assignments as $assign) : ?>
          <?php $num_submitted_assignments = num_submitted_assignments($con, $assign['id']); ?>
          <div class="panel assignment">

            <!--Deadline-->
            <div class="deadline">Due Date: <span><?php echo translateDateHalf($assign['deadline']) ?></span></div>

            <!--Title and Description-->
            <a href="/assignment/<?php echo $assign['id'] ?>" target="_black" style="all: unset; cursor: pointer">
              <div class="title"><?php echo $assign['title'] ?></div>
            </a>
            <hr>
            <?php $user_submitted_assignment = get_submitted_assignment($con, $assign['id'], $_SESSION['id']); ?>
            <?php if($user_submitted_assignment){
               echo linkify_str(nl2br($user_submitted_assignment['plain_text']));
             }
             else{
               echo "Not turned in yet!";
             }
            ?>
          </div>
        <?php endforeach; ?>
        <?php if(count($list_assignments) == 0){
          echo "Nothing was assigned for this class yet!";
        }
        ?>
      </div>
      <!--==================================================================================-->
      <!--GRADES-->
      <!--==================================================================================-->
        <div id="grades" class="tab-pane">
          <table class="table">
            <tr>
              <th>Assignment</th>
              <th>Grade</th>
            </tr>
            <?php foreach($list_assignments as $assign) : ?>
              <?php $a_grade = find_user_assignment_grade($con, $assign['id'], $class['id'], $member['id']); ?>
              <tr>
                <td><?php echo $assign['title'] ?></td>
                <td><?php echo (!empty(trim($a_grade))) ? $a_grade : 'not graded yet' ?></td>
              </tr>
            <?php endforeach; ?>
            <?php $f_grade = find_user_final_grade($con,  $class['id'], $member['id']); ?>
            <tr>
              <td><b>Final Grade</b></td>
              <td><?php echo (!empty(trim($f_grade))) ? $f_grade : 'not graded yet' ?></td>
            </tr>
          </table>
        </div>
      <!--==================================================================================-->
      <!--ACTIVITY-->
      <!--==================================================================================-->
        <div id="activity" class="tab-pane">
          <?php $user_posts = list_user_posts_for_class($con, $member['id'], $class['id']); ?>
          <?php foreach($user_posts as $post) : ?>
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/post.php"; ?>
          <?php endforeach; ?>
          <?php if(count($user_posts) == 0) {
            echo "No activity found for this class!";
          }
          ?>
        </div>
      <!--==================================================================================-->
      <!--NOTES-->
      <!--==================================================================================-->
      <div id="notes" class="tab-pane">
        <!--Personal Space-->
        <div class="panel" style="padding: 0; margin-left: 0" id="agendaPersonal">
          <div class="panel-header">Personal Space <small style="display: block; font-size: 10px;">Use it as an agenda or just take notes about the class.</small></div>
          <textarea type="text" placeholder="Type whatever you want here. It might be your agenda or a space to take notes about the class..." id="student_space"><?php echo $enrollment['member_space'] ?></textarea>
        </div>

        <?php if(user_class_permission($con, $_SESSION['id'], $class['id']) > 1) : ?>
        <!--Teacher Space-->
        <div class="panel" style="padding: 0; margin-left: 0" id="agendaPersonal">
          <div class="panel-header">Teacher Space <small style="display: block; font-size: 10px;">Write whatever you want here. It's only available to you.</small></div>
          <textarea type="text" placeholder="Type whatever you want here. Remarks about this student is a good example..." id="teacher_space"><?php echo $enrollment['teacher_space'] ?></textarea>
        </div>
        <script>var teacher_space = CKEDITOR.inline("teacher_space");</script>
        <?php endif; ?>
      </div>
</div>
</div><!--Close Row-->
<!--POST JS-->
<script>
  var comments = [];
  <?php foreach($user_posts as $post) : ?>
    var postId = <?php echo $post['id'] ?>;
    comments[postId] = $("#comment-input-" + postId).emojioneArea({
      pickerPosition: "bottom",
      tonesStyle: "bullet",
      events: {
        click: function(editor, event){
          displayCommentForm(<?php echo $post['id'] ?>);
        }
      }
    });
  <?php endforeach; ?>
</script>
<?php
if(user_class_permission($con, $_SESSION['id'], $class['id']) > 1){
//Sorry, I know this code is mess up
  $list_class_members = list_class_members($con, $enrollment['class_id'], 0);
  foreach ($list_class_members as $key => $value) {
     if($value['enrollment_id'] == $enrollment_id){
        $i = $key;
     }
  }
  $pro = array();
  $pro['prev'] = (isset($list_class_members[$i - 1]['enrollment_id'])) ? $list_class_members[$i - 1]['enrollment_id'] : 'null';
  $pro['next'] = (isset($list_class_members[$i + 1]['enrollment_id'])) ? $list_class_members[$i + 1]['enrollment_id'] : 'null';
  $member_prev = (isset($list_class_members[$i - 1]['enrollment_id'])) ? find_user($con, $list_class_members[$i - 1]['member_id']) : 'null';
  $member_next = (isset($list_class_members[$i + 1]['enrollment_id'])) ? find_user($con, $list_class_members[$i + 1]['member_id']) : 'null';
}
?>
<?php if(user_class_permission($con, $_SESSION['id'], $class['id']) > 1) : ?>
<div class="modal-footer" style="width: 100%; background-color: white; position: fixed; bottom: 0; padding: 10px;">
  <?php if(is_numeric($pro['prev'])) : ?>
  <button type="button" class="btn btn-secondary" onclick="loadModal('member_profile.php', 'memberProfile', this, <?php echo $pro['prev'] ?>, true)" style="float: left"><i class="fa fa-angle-left"></i> <img src="<?php echo $member_prev['picture'] ?>" class="img-button"><?php echo $member_prev['name'] ?></button>
  <?php endif; ?>
  <?php if(is_numeric($pro['next'])) : ?>
    <button type="button" class="btn btn-secondary" onclick="loadModal('member_profile.php', 'memberProfile', this, <?php echo $pro['next'] ?>, true)"><img src="<?php echo $member_next['picture'] ?>" class="img-button"><?php echo $member_next['name'] ?> <i class="fa fa-angle-right"></i></button>
  <?php endif; ?>
</div>
<?php endif; ?>
<script type="text/javascript">
var student_space = CKEDITOR.inline("student_space");
var card = CKEDITOR.inline("card_textarea");
CKEDITOR.instances['student_space'].on('change', function(){
  updateStudentSpace(<?php echo $enrollment['enrollment_id'] ?>);
});
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});
<?php if(user_class_permission($con, $_SESSION['id'], $class['id']) > 1) : ?>
$(".badge").click(function(){
  $(".badge").removeClass("active");
  $(this).addClass("active");
  var badge_id = $(this).attr("id").match(/\d/g).join("");
  var enrollment_id = <?php echo $enrollment_id; ?>;
  var user_id = <?php echo $member['id'] ?>;
  giveUserBadge(badge_id, enrollment_id, user_id);
});
CKEDITOR.instances['teacher_space'].on('change', function(){
  updateTeacherSpace(<?php echo $enrollment['enrollment_id'] ?>);
});
<?php endif; ?>
</script>
