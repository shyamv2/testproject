<!DOCTYPE html>
<?php
  //Verify if user is logged in
  if(!isset($_SESSION['id'])) {
      header("location: /");
      exit;
  }
  $list_posts = get_feed_news($con);
  $user_loggedin = find_user($con, $_SESSION['id']);

  $num_association_requests = num_association_requests($con, $_SESSION['id']);
  $list_associated_accounts = list_associated_accounts($con);
?>
<html>
<head>
  <title>Feed News | iStudy</title>
  <!--Head file (css and libraries)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/head.php" ?>
</head>
<body>
  <!--MENUS (TOP and LEFT)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/menu-top.php" ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/menu-left.php" ?>

  <!--CONTENT-->
  <div class="content">
    <div class="row row-content" style="width: 75%">
      <!--=====================================================================================================-->
                <!--LEFT SIDE-->
      <!--=====================================================================================================-->
      <div class="col-sm-3" style="position: fixed; width: 18.75%">
        <div class="panel" style="padding: 0">
          <div class="panel-header">
            My Classes
            <div style="float: right">
              <button type="button" data-toggle="modal" data-target="#createClass" onclick="loadModal('create_new_class.php', 'createClass', this)" class="panel-header-button"><i class="fa fa-plus"></i> New</button>
            </div>
          </div>
          <div class="panel-body list-classes-dashboard">
            <div class="list-group">
            <?php foreach($list_user_classes as $listed_class) : ?>
              <a href="/class/<?php echo $listed_class['id'] . '-' . linka($listed_class['name']) ?>" class="list-group-item list-group-item-action"
                style="padding-top: 5px; padding-bottom: 5px; font-size: 14px;">
                <?php echo limitName($listed_class['name'], 30) ?>
              </a>
            <?php endforeach; ?>
            </div>
          </div>
        </div>
        <a href="/yewno">
          <div class="yewno-dashboard">
            <img src="/images/yewno-logo.png"> Publish your own article
          </div>
        </a>
      </div>
<!--=====================================================================================================-->
          <!--Right SIDE-->
<!--=====================================================================================================-->
      <div class="col-sm-6" style="margin-left: 25%; flex: 0 0 48%; max-width: 48%;">
        <div class="panel" style="padding: 0">
          <div class="panel-header">What's on your mind?</div>
            <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/post-form.php" ?>
        </div>
        <div id="mural">
          <!--====LIST POSTS====-->
        <?php foreach($list_posts as $post) : ?>
          <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/post.php"; ?>
        <?php endforeach; ?>
        </div>
        <?php if(count($list_posts) == 3) : ?>
        <!--Load more-->
        <button type="button" class="load-more" onclick="loadMorePostsFeed(this, <?php echo $post['id'] ?>)">Load More</button>
        <?php endif; ?>

        <!--No-content-->
        <?php if(count($list_posts) == 0) : ?>
          <div class="panel">
            <center id="no-content">
              <img src="/images/no-content.png" style="width: 110px;"><br>
              No posts yet.
            </center>
          </div>
        <?php endif; ?>
      </div>


      <div class="col-sm-3" style="padding: 0">
        <!--Donate-->
        <div class="panel" style="padding: 0">
          <div class="panel-header">Donate to iStudy</div>
          <div class="panel-body">
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" style="width: 100% !important">
              <input type="hidden" name="cmd" value="_s-xclick">
              <input type="hidden" name="hosted_button_id" value="5QNJPMJMX6LG2">
              <center><input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"></center>
            </form>
          </div>
        </div>

        <?php if($_SESSION['type'] > 0) : ?>
        <!--Associated accounts-->
        <div class="panel" style="padding: 0">
          <div class="panel-header">Associated Accounts</div>
          <div class="panel-body list-group" style="padding: 0">
            <?php foreach($list_associated_accounts as $associated) : ?>
            <a class="list-group-item list-group-item-action justify-content-between" style="cursor: pointer"
            data-toggle="collapse" href="#associated-<?php echo $associated['user_id'] ?>">
              <div><img src="<?php echo $associated['picture']; ?>" style="width: 30px; float: left; margin-right: 10px; border-radius: 100%">
                <?php echo $associated['name']; ?>
              </div><span class="fa fa-angle-down"></span>
            </a>
            <?php $associated_classes = list_user_classes_as_student($con, $associated['user_id']) ?>
            <div class="collapse" id="associated-<?php echo $associated['user_id'] ?>">
              <div class="list-group list-classes-dashboard">
              <?php foreach($associated_classes as $class) : ?>
                <a data-toggle="modal" data-target="#memberProfile"
                onclick="loadModal('member_profile.php', 'memberProfile', this, <?php echo $class['enrollment_id'] ?>, true)"
                  class="list-group-item list-group-item-action"
                  style="padding-top: 5px; padding-bottom: 5px; font-size: 14px; cursor: pointer">
                  → <?php echo limitName($class['name'], 30) ?>
                </a>
              <?php endforeach; ?>
              <?php if(count($associated_classes) == 0) : ?>
                <center style="font-size: 11px;">No classes.</center>
              <?php endif; ?>
              </div>
            </div>
            <?php endforeach; ?>
            <a class="list-group-item list-group-item-action justify-content-between" style="cursor: pointer"
            data-toggle="modal" data-target="#associationRequests" onclick="loadModal('account_association_requests.php', 'associationRequests', this)">
              Association Requests
              <span class="badge badge-pill badge-danger"><?php echo $num_association_requests ?></span>
            </a>
          </div>
        </div>
        <?php endif; ?>
        <!--Agenda-->
        <div class="panel" style="padding: 0">
          <div class="panel-header">Agenda</div>
          <div class="panel-body">
            <label style="width: 100%;" id="agendaBox">
              <div type="text" class="agenda" id="agenda"><?php echo $user_loggedin['agenda'] ?></div>
            </label>
          </div>
        </div>

        <div class="bottom-links">

          <a href="/legal">Privacy and Terms of Use</a> ·
          <a href="/tutorials">Tutorials</a> ·
          <a href="https://www.facebook.com/iStudyPlatform"><i class="fa fa-facebook"></i></a> ·
          <a href="https://twitter.com/iStudyPlatform"><i class="fa fa-twitter"></i></a>
        </div>
      </div>
    </div>
  </div>

  <!--Footer-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/footer.php" ?>


  <!--ASSOCIATE REQUESTS-->
  <div class="modal fade" id="associationRequests" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i> Association Requests</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-js">
        </div>
      </div>
    </div>
  </div>
  <!--MEMBER PROFILE-->
  <div class="modal fade" id="memberProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog personal-space" role="document">
      <div class="modal-content personal-space">
        <div class="modal-header" style="background: #0091f7; padding: 0; border-bottom: 0">
          <h5></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="color: rgba(255,255,255,0.6); margin-top: 20px; margin-right: 20px">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-js" style="background-color: #fafafa; padding: 0 !important">
        </div>
      </div>
    </div>
  </div>
  <script src="/assets/libs/tinymce/js/tinymce/tinymce.min.js"></script>
  <!--====EmojiArea====-->
  <link rel="stylesheet" href="/assets/libs/emojionearea/dist/emojionearea.min.css">
  <script type="text/javascript" src="/assets/libs/emojionearea/dist/emojionearea.min.js"></script>
  <script type="text/javascript" src="/assets/libs/ckeditor/ckeditor.js"></script>
  <link rel="stylesheet" href="/assets/libs/justified/dist/css/justifiedGallery.min.css" />
  <script src="/assets/libs/justified/dist/js/jquery.justifiedGallery.min.js"></script>
  <script>
    var comments = [];
    <?php foreach($list_posts as $post) : ?>
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
  <script type="text/javascript">
          //Justify images
          $(".post-gallery").justifiedGallery();

          var postText;
          //Post to the profile
          tinymce.init({
            mode : 'exact',
            selector: '#agenda',  // change this value according to your HTML
            inline: true,
            theme: 'modern',
            skin: 'light',
            menubar: false,
            plugins : 'lists link image media table emoticons tiny_mce_wiris advlist',
            toolbar: 'bold italic bullist numlist | emoticons | ',
            setup: function(editor){
              editor.on('keyup', function(e) {
                  updateUserAgenda(editor.getContent());
              });
            }
          });
            postText = $("#post-textarea").emojioneArea({
              pickerPosition: "bottom",
              tonesStyle: "bullet"
            });
            //Trigger Button for the post form
            $('#post-submit').click(function(){
              var button = document.getElementById('post-submit');
              postToProfile(<?php echo $_SESSION['id'] ?>, button);
            });
  </script>
</body>
</html>
