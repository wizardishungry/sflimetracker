<?php if($form['image_url']->getValue()) { ?>
   <a href="<?php echo $form['image_url']->getValue(); ?>">
     <?php echo image_tag($form['image_url']->getValue(), array('class' => 'cover')) ?>
   </a>
<?php } ?>
<?php page_title($podcast->getTitle()) ?>

<div class="delete-form-wrapper">
  <?php echo delete_form_for_object($podcast); ?>
</div>
<div class="form-wrapper <?php if($form->hasErrors()) { echo "open-form"; } ?>">
  <form action="<?php echo url_for('podcast/edit') ?>" method="POST" enctype="multipart/form-data">
    <table>
      <?php include_partial('podcast/podcastform', Array('form'=>$form)); ?>
      <tr class="form-field">
      <td>&nbsp;</td>
      <td>
          <div>
            <input type="submit" value="Save"/>
            <input type="submit" class="close-form" value="Cancel"/>
          </div>
      </td>
      <td>

      </td>
      </tr>
    </table>

    <?php echo $form['id'] ?>
    <?php echo $form['_csrf_token'] ?>

  </form>
</div>

<h2>Feeds</h2>
<div class="podcast_feed">
  <ul class="links">
    <div class="divider">&nbsp;</div>
    <span>combined</span>
    <li><?php echo link_to_with_icon($podcast->getTitle(),'web',$podcast->getUri()); ?></li>
  </ul>
</div>
    
<div class="podcast_feeds">
<?php foreach($feeds as $feed): ?>
  <div class='podcast_feed'>
    <?php 
       echo $feed->getTitle(), ' ',
       (count($feeds)>1?link_to('Edit','podcast_feed/edit?id='.$feed->getID()):'')
    ?>
    <?php echo count($feeds)>1?delete_form_for_object($feed,'podcast_feed/delete'):''; ?>
    <ul class="links">
      <div class="divider">&nbsp;</div>
      <?php foreach($feed->getUris() as $type => $uri): ?>
        <li>
	  <span><?php echo $type ?></span>
          <?php 
            $url = url_for($uri,true);
            echo link_to_with_icon($url, 'rss', $url);
          ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endforeach; ?>
</div>

<h2>Episodes</h2>
<ul>
  <?php if($episodes): ?>
      <?php foreach($episodes as $episode): ?>
        <li>
          <?php echo link_to_with_icon($episode->getCreatedAt('Y M d').
            ' - '.$episode->getTitle(),'episode', 'episode/edit?id='.$episode->getId()) ?>
        </li>
      <?php endforeach; ?>
  <?php endif; ?>
  <?php
    if(true) // TODO add !Podcast->isFeedBased() here
    {
    echo '<li>',link_to_with_icon('Add episode','add','episode/add?podcast_id='.$podcast->getId()),'</li>';
    }
  ?>
</ul>
