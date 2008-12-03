Title: <?php echo $podcast->getTitle() ?>
<ul><?php  foreach($episodes as $episode): ?>
  <li>
    <?php echo link_to($episode->getTitle(),'episode/view?id='.$episode->getId()) ?>
  </li>
<?php endforeach; ?></ul>
<?php if($sf_user->isAuthenticated()): ?>
  <?php
    if(true) // TODO add !Podcast->isFeedBased() here
    {
      echo link_to('add episode','episode/add?podcast_id='.$podcast->getId()),
      ' ', link_to('add new format','feed/add?podcast_id='.$podcast->getId()); 
    }
    else
    {
      echo link_to('add new feed','feed/add?podcast_id='.$podcast->getId()); 
    }
endif; ?> 
<?php slot('feed');
foreach($feeds as $feed) 
{
  auto_discovery_link_tag ('rss','feed/feed?id='.$feed->getId()); // make this use a slug todo
}
end_slot();
?>
