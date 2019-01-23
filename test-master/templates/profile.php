<!DOCTYPE html>
<!--System file (php functions)-->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/src/functions/system.php" ?>
<?php
  //Verify if user is logged in
  if(!isset($_SESSION['id'])) {
      header("location: /");
      exit;
  }
  if(isset($_GET['id']) AND is_numeric($_GET['id'])){
    $user = find_user($con, mysqli_real_escape_string($con, $_GET['id']));
    //Verify if the user is being followed by the SESSION ID
    $is_following = is_following($con, $user['id']);
    //Numbers of followers and being followed
    $num_followed = num_followed($con, $user['id']);
    $num_following = num_following($con, $user['id']);

    //List user POSTS
    $list_posts = list_user_posts($con, $user['id']);

    //List BADGES
    $list_badges = list_badges($con);

    //Educoin
    $num_educoin = user_num_educoin($con, $user['id']);
  }
  else{
    echo "URL ERROR!"; die;
  }
?>
<html>
<head>
  <title><?php echo $user['name'] ?> Profile | iStudy</title>
  <!--Head file (css and libraries)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/head.php" ?>

  <?php if($user['cover_picture'] != "NULL" AND !empty($user['cover_picture'])) : ?>
    <style>
      .profile-cover{
        background-image: url('<?php echo $user['cover_picture'] ?>');
        background-size: cover;
        background-position: center center;
        height: 250px !important;
      }
    </style>
  <?php endif; ?>
</head>
<body>
  <!--MENUS (TOP and LEFT)-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/menu-top.php" ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/menu-left.php" ?>

  <!--CONTENT-->
  <div class="content">

    <div class="row row-content">
      <div class="col-sm-11">
        <div class="panel" style="padding: 0">
          <!--Cover-->
          <div class="profile-cover">
            <?php if($_SESSION['id'] == $user['id']) : ?>
            <!--Cover Picture Options-->
            <div class="dropdown update-cover-picture">
              <button type="button" data-toggle="dropdown"><i class="fa fa-camera"></i> Update Cover</button>
              <div class="dropdown-menu dropdown-menu-right">
                <a href="javascript: void 0" class="dropdown-item" onclick="loadModal('edit_profile_cover.php', 'upload_cover', this, null, true)" data-toggle="modal" data-target="#upload_cover"> Upload New</a>
                <a href="javascript: void 0" class="dropdown-item" onclick="removeCurrentCover();">Remove Current</a>
              </div>
            </div>
            <?php endif; ?>
            <div class="num-follows">
            <!--Followed by-->
            <span><?php echo $num_followed ?></span> Followers
            |
            <!--Following-->
            <span><?php echo $num_following ?></span> Following
            </div>
          </div>
          <!--Profile Picture-->
          <div class="profile-picture">
            <!--Upload new imaage or remove current-->
            <?php if($_SESSION['id'] == $user['id']) : ?>
              <button class="upload-picture" data-toggle="modal" data-target="#editProfilePictureModal" data-backdrop="static" data-keyboard="false" onclick="loadModal('edit_profile_picture.php', 'editProfilePictureModal', this)"><li class="fa fa-camera"></li></button>
            <?php endif; ?>
              <!--Picture-->
              <img src="<?php echo $user['picture'] ?>">
          </div>
          <!--Profile options-->
          <div class="profile-options">
            <?php if($_SESSION['id'] != $user['id']) : ?>
              <button type="button" onclick="toggleFollow(this, <?php echo $user['id'] ?>)" class="btn <?php echo ($is_following) ? 'btn-success' : 'btn-primary' ?>"><?php echo ($is_following) ? "<i class='fa fa-check'></i> Following" : "<i class='fa fa-user-plus'></i> Follow" ?></button>
              <a href="/messages?chatwith=<?php echo $user['id'] ?>" class="btn btn-secondary"><i class="fa fa-envelope"></i></a>
            <?php endif; ?>
          </div>
          <!--Profile Head (Name and Email) - Left Side-->
          <div class="profile-head">
            <div class="profile-name"><?php echo $user['name'] ?></div> <!--Name-->
            <div class="profile-email"><?php echo $user['email'] ?></div> <!--Email-->
          </div>
          <br><hr style="margin-bottom: 0">

          <!--User Badges-->
          <div class="list-badges" style="padding: 15px">
            <?php foreach($list_badges as $badge) : ?>
              <?php
                $num_badges = num_of_badges($con, $user['id'], $badge['id']);
                if($num_badges > 0) : ?>
              <button type="button" class="badge active"
                data-toggle="tooltip" data-placement="bottom" data-animation="false"
              title="<?php echo $badge['name'] ?>"
              id="badge-<?php echo $badge['id'] ?>" style="transform: scale(1)">
                <img src="<?php echo $badge['icon'] ?>">
                <span class="badge badge-pill badge-default"><?php echo $num_badges ?></span>
              </button>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>

          <!--Profile Menu - NAV TABS-->
          <ul class="nav nav-tabs profile-nav-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#code">About</a>
            </li>
            <!--<li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#create">Articles</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#create">Portfolio</a>
            </li>-->
          </ul>
        </div>
      </div>
      <!--Left Side-->
      <div class="col-sm-4">
        <?php if(!empty($user['bio']) AND $user['bio'] != "NULL") : ?>
          <div class="panel" style="padding: 0">
            <div class="panel-header" style="padding-bottom: 0">
              Bio
              <!--Edit Button-->
              <?php if($_SESSION['id'] == $user['id']) : ?>
                <div style="float: right">
                  <button type="button" onclick="loadModal('edit_profile_info.php', 'editProfileInfoModal', this, null, true)" data-toggle="modal" data-target="#editProfileInfoModal" class="panel-header-button"><i class="fa fa-pencil"></i> Edit</button>
                </div>
              <?php endif; ?>
            </div>
            <div class="panel-body">
              <div class="profile-about">
                <?php echo $user['bio'] ?>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <!--Educoins-->
        <div class="panel" style="padding: 0">
          <div class="panel-header" style="padding-bottom: 0">
            Educoin Wallet
            <?php if($_SESSION['id'] == $user['id']) : ?>
            <!--Edit Button-->
              <div style="float: right">
                <button type="button" onclick="loadModal('educoins_history.php', 'educoinsHistory', this, null, true)" data-toggle="modal" data-target="#educoinsHistory" class="panel-header-button"><i class="fa fa-history"></i> History</button>
              </div>
            <?php endif; ?>
          </div>
          <div class="panel-body">
            <div class="educoins">
              <h3><img src="/images/coin.gif" style="width: 20px;position: relative; top: -3px; margin-right: 10px;">
                <div id="eduvalue" style="display: inline"><?php echo number_format($num_educoin, 4, '.', ','); ?></div></h3> EDU
            </div>
          </div>
          <?php if($_SESSION['id'] != $user['id']) : ?>
          <div class="transfer-educoin-box">
            <?php $max_transfer = user_num_educoin($con, $_SESSION['id']); ?>
            <input type="text" placeholder="Max value: <?php echo $max_transfer; ?> " onchange="validateEducoinValue(this, <?php echo $max_transfer; ?>)" onkeyup="validateEducoinValue(this, <?php echo $max_transfer; ?>)">
            <button type="button" onclick="prepareTransferEducoin(this)">
              <i class="fa fa-forward"></i> Transfer
            </button>
          </div>
          <?php endif; ?>
        </div>


        <div class="panel" style="padding: 0">
          <div class="panel-header" style="padding-bottom: 0">
            About
            <!--Edit Button-->
            <?php if($_SESSION['id'] == $user['id']) : ?>
              <div style="float: right">
                <button type="button"  onclick="loadModal('edit_profile_info.php', 'editProfileInfoModal', this)" data-toggle="modal" data-target="#editProfileInfoModal" class="panel-header-button"><i class="fa fa-pencil"></i> Edit</button>
              </div>
            <?php endif; ?>
          </div>
          <div class="panel-body">
            <!--Name-->
            <div class="profile-about">
              <i class="fa fa-id-card"></i> <?php echo $user['name'] ?>
            </div>
            <!--Email-->
            <div class="profile-about">
              <i class="fa fa-envelope"></i> <?php echo $user['email'] ?>
            </div>
            <?php if(!empty($user['school']) AND $user['school'] != "NULL") : ?>
              <!--School-->
              <div class="profile-about">
                <i class="fa fa-university"></i> <?php echo $user['school'] ?>
              </div>
            <?php endif; ?>
            <?php if((!empty($user['birthdate']) AND $user['birthdate'] != "NULL") AND $user['birthdate'] != "1111-11-11") : ?>
              <!--BirthDate-->
              <div class="profile-about">
                <i class="fa fa-birthday-cake"></i> <?php echo translateDateHalf($user['birthdate']) ?>
              </div>
            <?php endif; ?>
            <?php if(!empty($user['genre']) AND $user['genre'] != "NULL") : ?>
              <!--School-->
              <div class="profile-about">
                <i class="fa fa-<?php echo translate_genre($user['genre']) ?>"></i> <?php echo ucwords(translate_genre($user['genre'])) ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="col-sm-7">
        <?php if(count($list_posts) == 0) : ?>
          <center>
            <img src="/images/no-content.png"><br>
            <?php echo ($_SESSION['id'] == $user['id']) ? 'You haven\'t posted anything yet.' : $user['name'] . ' haven\'t posted anything yet.' ?>
          </center>
        <?php endif; ?>
        <div id="mural">
        <?php foreach($list_posts as $post) : ?>
          <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/post.php"; ?>
        <?php endforeach; ?>
        </div>
      </div>

  </div>

  <!--Footer-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/includes/footer.php" ?>
  <link rel="stylesheet" href="/assets/libs/jcrop/css/jquery.Jcrop.min.css" type="text/css" />
  <script src="/assets/libs/jcrop/js/jquery.Jcrop.min.js"></script>
  <!--====EmojiArea====-->
  <link rel="stylesheet" href="/assets/libs/emojionearea/dist/emojionearea.min.css">
  <script type="text/javascript" src="/assets/libs/emojionearea/dist/emojionearea.min.js"></script>
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
  <script>
    //Justify images
    $(".post-gallery").justifiedGallery();


    function prepareTransferEducoin(button){
      $(".transfer-educoin-box button").css({
        "width" : "45%"
      });
      $(".transfer-educoin-box input").css({
        "display" : "inline-block",
        "width" : "54%"
      });
      $(".transfer-educoin-box input").focus();
      //$(".transfer-educoin-box button").html("<i class='fa fa-forward'></i>");
      $(".transfer-educoin-box button").attr("onclick", null);
    }
    function validateEducoinValue(input, max){
      var value = input.value;
      var RE = /^-{0,1}\d*\.{0,1}\d+$/;
      if(!RE.test(value) || parseFloat(value) > max || parseFloat(value) <= 0){
        $(".transfer-educoin-box input").css({
          "border-color" : "red"
        });
        $(".transfer-educoin-box button").attr("onclick", null);
      }
      else{
        $(".transfer-educoin-box input").css({
          "border-color" : "transparent"
        });
        $(".transfer-educoin-box button").attr("onclick", "transferEducoin(" + value + ", <?php echo $user['id'] ?>)");
      }
    }
    function transferEducoin(value, user_id){
      $.ajax({
        dataType: 'json',
        data: {value : value, user_id : user_id},
        url: '/src/ajax-actions/general/transfer_educoin.php',
        success: function(data){
          if(data['error'] == true){
            alert(data['msg_error']);
          }
          else{
            $(".transfer-educoin-box input").val("");
            showMsg(parseFloat(value) + " educoins successfully transfered!");
          }
          $(".transfer-educoin-box button").removeAttr('disabled');
        },
        beforeSend: function(){
          $(".transfer-educoin-box button").attr("disabled", "disabled");
          var oldvalue = parseFloat($("#eduvalue").html());
          var newvalue = parseFloat(value) + oldvalue;
          $("#eduvalue").html(newvalue);
        },
        error: function(){
          showMsg("Something went wrong!");
          $(".transfer-educoin-box button").removeAttr('disabled');
        },
        type: 'POST'
      })
    }
  </script>
  <!-- Modal - Edit Profile PICTURE-->
<div id="editProfilePictureModal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width: 1400px !important;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fa fa-image"></i> Edit profile picture</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body modal-js"></div>
    </div>
  </div>
</div>


<!-- Modal - Edit COVER PICTURE-->
<div id="upload_cover" class="modal fade" role="dialog">
<div class="modal-dialog" style="width: 1400px !important;">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title"><i class="fa fa-image"></i> Upload new cover picture</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body modal-js"></div>
  </div>
</div>
</div>

<!-- Modal - Edit Profile INFO-->
<div id="editProfileInfoModal" class="modal fade" role="dialog">
<div class="modal-dialog" style="width: 1400px !important;">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title"><i class="fa fa-pencil"></i> Edit Profile</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body modal-js"></div>
  </div>
</div>
</div>

<!-- Modal - Educoins History-->
<div id="educoinsHistory" class="modal fade" role="dialog">
<div class="modal-dialog" style="width: 1400px !important;">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title"><i class="fa fa-coin"></i> Educoin History</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body modal-js" style="padding: 0"></div>
  </div>
</div>
</div>


</body>
</html>
