<?php foreach($list_reactions as $reaction) : ?>
  <div class='reaction-button' id='reaction-<?php echo $post['id'] ?>-<?php echo $reaction['id'] ?>'
  onclick='reactToPost(<?php echo $reaction['id'] ?>, <?php echo $post['id'] ?>)'>
    <img src='<?php echo $reaction['icon'] ?>'/>
    <span><?php echo $reaction['name'] ?></span>
  </div>
<?php endforeach; ?>
