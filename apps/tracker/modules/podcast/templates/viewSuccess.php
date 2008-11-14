Title: <?php echo $podcast->getTitle() ?>
<ul><?php  foreach($episodes as $episode): ?>
  <li>
    <?php echo link_to($episode->getTitle(),'episode/view?id='.$episode->getId()) ?>
  </li>
<?php endforeach; ?></ul>
<?php if($sf_user->isAuthenticated()): ?>
  <?php if(!$podcast->getFeedUrl()) echo link_to('add episode','episode/add?podcast_id='.$podcast->getId()); ?>
<?php endif; ?>
<?php slot('feed');
echo auto_discovery_link_tag ('rss','feed/feed?id='.$podcast->getId());
end_slot();
?>
