<?php
include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php";
  if(!isset($_SESSION['id'])){
    echo "Login to continue...";
    exit;
  }
  else{
    $user = find_user($con, $_SESSION['id']);
  }
?>
<link rel="stylesheet" href="/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
      <form id="editProfile" method="POST">
        <!--Name-->
        <label>
          <b>Name</b>
          <input type="text" name="name" id="name" placeholder="Type your name here..." value="<?php echo $user['name'] ?>">
        </label>
        <!--Email-->
        <label>
          <b>Email</b>
          <input type="email" name="email" placeholder="Type your email here..." value="<?php echo $user['email'] ?>">
        </label>
        <!--Associated Email-->
        <label>
          <b>Associated Email</b>
          <input type="email" name="associated_email" placeholder="Type your associated email here..." value="<?php echo $user['associated_email'] ?>">
        </label>
        <!--Type-->
        <label>
          <b>You're a</b>
          <label class="custom-control custom-radio" style="display: inline">
            <input id="radio3" name="type" type="radio" class="custom-control-input" value="0" <?php echo che(0, $user['flag_type']) ?>>
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Student</span>
          </label>
          <label class="custom-control custom-radio" style="display: inline">
            <input id="radio3" name="type" type="radio" class="custom-control-input" value="1" <?php echo che(1, $user['flag_type']) ?>>
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Teacher</span>
          </label>
          <label class="custom-control custom-radio" style="display: inline">
            <input id="radio3" name="type" type="radio" class="custom-control-input" value="2" <?php echo che(2, $user['flag_type']) ?>>
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Parent</span>
          </label>
        </label>
        <!--Bio-->
        <label>
          <b>Bio</b>
          <textarea type="text" name="bio" placeholder="Briefly describe yourself, what you're interested in, your hobbies..."><?php echo $user['bio'] ?></textarea>
        </label>
        <!--School-->
        <label>
          <b>School</b>
          <input type="text" name="school" placeholder="Type the name of your school here..." value="<?php echo ($user['school'] != 'NULL') ? $user['school'] : '' ?>">
        </label>
        <!--School-->
        <label>
          <b>Birthdate</b>
          <input type="text" data-provide="datepicker" data-date-format="yyyy-mm-dd" name="birthdate" placeholder="Pick a date..." value="<?php echo ($user['birthdate'] != 'NULL') ? $user['birthdate'] : '' ?>">
        </label>
        <!--Gender-->
        <label>
          <b>Gender</b>
          <label class="custom-control custom-radio" style="display: inline">
            <input id="radio3" name="genre" type="radio" class="custom-control-input" value="1" <?php echo che(1, $user['genre']) ?>>
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Male</span>
          </label>
          <label class="custom-control custom-radio" style="display: inline">
            <input id="radio3" name="genre" type="radio" class="custom-control-input" value="2" <?php echo che(2, $user['genre']) ?>>
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Female</span>
          </label>
          <label class="custom-control custom-radio" style="display: inline">
            <input id="radio3" name="genre" type="radio" class="custom-control-input" value="3" <?php echo che(3, $user['genre']) ?>>
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Other</span>
          </label>
        </label>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" name="send" id="send" onclick="editProfile(this)">Save Changes</button>
        </div>
      </form>
  <script src="/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
