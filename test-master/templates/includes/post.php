<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/src/libs/parsedown/Parsedown.php";
$Parsedown = new Parsedown();
$list_attaches = list_post_attaches($con, $post['id']);
//reactions
$list_reactions = list_reactions($con);
$user_reaction = user_reaction_to_post($con, $post['id']);
if(!isset($page)){
  $page = false;
}
?>
<!--============================POST TEMPLATE==========================-->
<div class="post-box panel" id="post-<?php echo $post['id'] ?>">
  <div class="panel-body" style="padding: 20px;">
    <!--====Post Box Head====-->
    <div class="post-box-head">
      <!--===Post Box OPTIONS - RIGHT===-->
      <?php if($_SESSION['id'] == $post['author_id']) : ?>
      <div class="post-box-options">
        <div class="dropdown">
          <button class="btn btn-normal" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
          <ul class="dropdown-menu dropdown-menu-right">
            <li class="dropdown-item" onclick="deletePost(<?php echo $post['id'] ?>)"><i class="fa fa-remove"></i> Delete</li>
            <li class="dropdown-item" onclick="prepareEditPost(<?php echo $post['id'] ?>)"><i class="fa fa-edit"></i> Edit</li>
            <li class="dropdown-item" onclick="location.href='/post/<?php echo $post['id'] ?>'"><i class="fa fa-external-link"></i> Open</li>
          </ul>
        </div>
      </div>
      <?php endif; ?>

      <!--===Post Box Info - LEFT===-->
      <div class="post-box-id">
        <!--User Picture-->
          <img src="<?php echo $post['author_picture'] ?>">
        <div class="post-box-id-name">
          <!--User Name-->
          <a href="/profile/<?php echo $post['author_id'] . '-' . linka($post['author_name']) ?>"><?php echo $post['author_name'] ?></a>
          <!--Verify if it was posted in a class or lesson -->
          <?php if(isset($class['id'])) : ?>
            <!--Label-->
            <span class="label <?php echo display_class_label($con, $post['author_id'], $class['id']) ?>"><i class="fa fa-check"></i></span>
          <?php endif; ?>
          <!--Date-->
          <small><?php echo time_elapsed_string($post['registry']) ?></small>
        </div>
      </div>
    </div>

    <!--====Post Box Body====-->
    <div class="post-box-body">
      <!--TEXT-->
      <div id="raw-post-text"><?php echo $post['post'] ?></div>
      <div id="post-text">
        <?php echo $Parsedown->line(linkify_str(nl2br(shorten_post($post['post'], $post['id'], 250, $page)))); ?>
      </div>
      <!--ATTACHES-->
      <?php $images_exts = array("jpg", "jpeg", "png", "gif"); ?>
      <!--List Images-->
      <div class="post-gallery<?php echo (count($list_attaches) == 1) ? '-no' : '' ?>">
      <?php foreach($list_attaches as $att) : ?>
        <?php if(in_array($att['file_extension'], $images_exts)) : ?>
          <a href="<?php echo $att['file_dir'] ?>">
            <img src="<?php echo $att['file_dir'] ?>">
          </a>
        <?php endif; ?>
        <?php endforeach; ?>
      </div>
      <!--List Files-->
      <?php foreach($list_attaches as $att) : ?>
        <?php if(!in_array($att['file_extension'], $images_exts)) : ?>
        <div class="post-file">
          <div style="float: right">
            <a href="<?php echo $att['file_dir'] ?>"
              download="<?php echo $att['file_name'] ?>"
              data-toggle="tooltip" title="Download">
              <i class="fa fa-download"></i>
            </a>
          </div>
          <b><?php echo $att['file_name'] ?></b> | <small><?php echo formatBytes($att['file_size']) ?></small>
        </div>
        <?php endif; ?>
      <?php endforeach; ?>
      <!--Display Link-->
      <?php
        $link = find_post_links($con, $post['id']);
        if(isset($link)) : ?>
        <?php if($link['type'] == 'video') : ?>
          <div class="videoWrapper">
            <iframe src="<?php echo $link['link'] ?>" type="text/html" frameborder="0" allowfullscreen></iframe>
          </div>
        <?php else: ?>
          <div class="post-form-link">
            <a href="<?php echo $link['link'] ?>" target="_blank" style="all: unset; cursor: pointer" title="<?php echo $link['link'] ?>">
              <?php $site = dom_url_parser($link['link']) ?>
              <div style="float: left; background-image: url('<?php echo $site['image'] ?>'); background-repeat: no-repeat; background-size: cover; width: 30px; height: 30px; margin-right: 10px;"></div>
              <h6 style="line-height: 20px; margin: 0"><?php echo $site['title'] ?></h6><?php echo $site['domain'] ?>
            </a>
          </div>
        <?php endif; ?>
      <?php endif; ?>

    </div>
  </div>

  <!--====Post Reactions Bar====-->
  <div class="post-reactions-bar">
    <div class="post-reactions-bar-comment">
      <button type="button" onclick="focusCommentForm(<?php echo $post['id'] ?>)"><i class="fa fa-comment"></i> Comment</button>
    </div>
    <div class="post-reactions-bar-react">
      <button type="button" data-toggle="popover" data-placement="top"
      data-content="<?php include 'reactions.php' ?>" class="react-button">
      <?php if(isset($user_reaction)) : ?>
        <img src="<?php echo $user_reaction['icon'] ?>" style="width: 20px; display: inline">
        <?php echo $user_reaction['name'] ?>
      <?php else : ?>
        <i class="fa fa-thumbs-up"></i> React
      <?php endif; ?>
      </button>

      <!--List reactions-->
      <?php foreach($list_reactions as $reaction) : ?>
        <?php $num_reactions = number_of_reactions_post($con, $reaction['id'], $post['id']); ?>
        <?php if($num_reactions > 0) : ?>
        <div class="reaction-counter">
          <img src="<?php echo $reaction['icon'] ?>">
          <?php echo $num_reactions; ?>
        </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>


  <!--======================COMMENTS================================-->
  <?php $list_post_responses = list_post_responses($con, $post['id']); ?>

  <div class="comments" style="display: <?php echo (count($list_post_responses) > 0) ? 'block' : 'none' ?> ">

    <div id="list-comments-<?php echo $post['id'] ?>">

      <?php foreach($list_post_responses as $response) : ?>
        <div id="comment-<?php echo $response['id'] ?>">
            <!--===Post Box OPTIONS - RIGHT===-->
            <?php if($_SESSION['id'] == $response['author_id']) : ?>
            <div class="post-box-options">
              <div class="dropdown">
                <button class="btn btn-normal" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
                <ul class="dropdown-menu dropdown-menu-right">
                  <li class="dropdown-item" onclick="deleteComment(<?php echo $response['id'] ?>)"><i class="fa fa-remove"></i> Delete</li>
                  <!--<li class="dropdown-item" onclick="prepareEditPost(<?php echo $post['id'] ?>)"><i class="fa fa-edit"></i> Edit</li>-->
                </ul>
              </div>
            </div>
            <?php endif; ?>

            <div class="post-box-id">
              <!--===Comment - HEAD===-->
              <img src="<?php echo $response['author_picture'] ?>">
              <div class="post-box-id-name">
                <a href="/profile/<?php echo $response['author_id'] . '-' . linka($response['author_name']) ?>"><?php echo $response['author_name'] ?></a>
                <small><?php echo time_elapsed_string($response['registry']) ?></small>
              </div>
            </div>

            <!--===Comment Body===-->
            <div class="comment"><?php echo $Parsedown->line(linkify_str(nl2br($response['response']))); ?></div>
          </div>
      <?php endforeach; ?>

    </div>

  </div>


  <!--=====================COMMENT FORM==============================-->
  <div class="post-box-comment" id="response-form-<?php echo $post['id'] ?>">
    <img src="<?php echo $_SESSION['picture'] ?>">
    <textarea placeholder="Write a comment..." onfocus="displayCommentForm(<?php echo $post['id'] ?>)" id="comment-input-<?php echo $post['id'] ?>"></textarea>
    <!--Submit button-->
  </div>
</div>
