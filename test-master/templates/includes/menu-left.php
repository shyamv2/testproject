<?php
$list_user_classes = list_user_classes($con);
 ?>
<div class="sidebar">
  <!-- Close Sidebar Button -->
  <div id="dismiss">
      <i class="glyphicon glyphicon-arrow-left"></i>
  </div>
  <!--Logo - Spight-->
  <a href="/"><div class="logo"></div></a>
  <!--Profile-->
  <div class="nav-left-profile">
    <a href="<?php echo profile_link($_SESSION) ?>">
      <img src="<?php echo $_SESSION['picture'] ?>">
      <div class="nav-left-profile-commitment">
        Welcome,
      </div>
      <div class="nav-left-profile-name">
        <?php echo $_SESSION['name'] ?>
      </div>
    </a>
  </div>
  <hr class="hr-white">
  <!--Navigation Bar-->
  <div class="nav-left">
    <ul class="nav-left-links">
      <li><a href="<?php echo profile_link($_SESSION) ?>"><i class="fa fa-user"></i> My profile</a></li><hr>
      <li class="label">
        Classes
        <div style="float: right">
          <button type="button" data-toggle="modal" data-target="#createClass" onclick="loadModal('create_new_class.php', 'createClass', this)" id="new-class-button"><i class="fa fa-plus"></i> New</button>
        </div>
      </li>
      <?php foreach($list_user_classes as $listed_class) : ?>
        <li><a href="/class/<?php echo $listed_class['id'] . '-' . linka($listed_class['name']) ?>"> <?php echo limitName($listed_class['name'], 30) ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>

<!--=======================================================-->
                      <!--MODALS-->
<!--=======================================================-->
<!--NEW CLASS-->
<div class="modal fade" id="createClass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Create New Class</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modal-js">
      </div>
    </div>
  </div>
</div>


<div class="overlay"></div>
