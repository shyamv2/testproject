<?php
	$list_notifications = list_notifications($con);
	$unread_notifications = unread_notifications($con);
	$num_unread_messages = unread_messages($con);
	load_notifications($con);
	set_messages_as_notified($con);
	register_page_view($con);
?>
<div class="menu-top">
  <!--Nav top - right aligned-->
  <div class="nav-top">

    <!--Notifications-->
    <div class="dropdown dropdown-notifications">
      <a class="nav-a" style="padding-top: 18px" data-toggle="dropdown" onclick="setNotificationsAsRead();">
        <i class="fa fa-bell"></i>
          <span class="badge notifications-number" id="notifica" style="display: <?php echo ($unread_notifications > 0) ? 'inline-block' : 'none' ?>"><?php echo $unread_notifications ?></span>
      </a>
      <ul class="dropdown-menu dropdown-menu-right">
        <div class="notifications-header">Notifications</div>
        <hr>
        <ul class="list-notifications" id="list-notifications">
          <?php foreach($list_notifications as $notification) : ?>
          <a href="<?php echo $notification['link_ref'] ?>">
          <li>
            <img src="<?php echo $notification['icon'] ?>">
            <div>
              <?php echo $notification['message'] ?>
              <span><?php echo time_elapsed_string($notification['registry']) ?></span>
            </div>
          </li>
          </a>
          <?php endforeach; ?>
          <?php if(empty($list_notifications)) : ?>
            <div style="padding: 20px">You don't have any notifications!</div>
          <?php endif; ?>
        </ul>
      </ul>
    </div>

		<!--Messages-->
    <div class="dropdown dropdown-notifications">
      <a href="/messages" class="nav-a" style="padding-top: 18px"  onclick="setNotificationsAsRead();">
        <i class="fa fa-envelope"></i>
          <span class="badge messages-number" style="display: <?php echo ($num_unread_messages > 0) ? 'inline-block' : 'none' ?>"><?php echo $num_unread_messages ?></span>
      </a>
      <ul class="dropdown-menu dropdown-menu-right">
        <div class="notifications-header">Messages</div>
        <hr>
        <ul class="list-notifications" id="list-notifications">
          <!--<a href="/messages">
          <li>
            <img src="{User Picture}">
            <div>
							<h6 style="margin: 0">User Name</h6>
              {Message}
              <span>{Date}</span>
            </div>
          </li>
				</a>-->
            <div style="padding: 20px">You don't have any messages yet!</div>
        </ul>
      </ul>
    </div>

    <!--Profile-->
    <div class="dropdown">
      <a  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-top-link img-link">
        <img src="<?php echo $_SESSION['picture'] ?>">
        <?php echo $_SESSION['name'] ?>
      </a>
      <ul class="dropdown-menu dropdown-menu-right">
        <li><a href="<?php echo profile_link($_SESSION) ?>"><i class="fa fa-user"></i> My Profile</a></li>
				<li><a href="/settings"><i class="fa fa-cog"></i> Settings</a></li>
        <div class="dropdown-divider"></div>
        <li><a href="/templates/logout.php"><i class="fa fa-sign-out"></i> Log out</a></li>
      </ul>
    </div>


  </div>


  <!--Search-->
  <div class="search-top">
    <form method="GET" action="/templates/search.php?search=">
      <i class="fa fa-search"></i>
      <input type="text" placeholder="Search..." value="<?php echo (isset($_GET['search'])) ? htmlentities($_GET['search']) : '' ?>" name="search">
    </form>
  </div>

	<!--SideBar Trigger-->
	<a href="javascript:void(0)" id="sidebarCollapse" class="sidebarTrigger"><i class="fa fa-bars"></i></a>

	<!--Logo-->
	<a href="/" class="menu-top-logo"></a>

</div>



<?php if($_SESSION['verified'] == 0): ?>
	<div class="verify-email">
		You need to verify your email <b><?php echo $_SESSION['email'] ?></b> in order to continue using the platform.
		<button type="button" onclick="resendEmailConfirmation(this)"><i class="fa fa-envelope"></i> Resend Confirmation</button>
	</div>
<?php endif; ?>
