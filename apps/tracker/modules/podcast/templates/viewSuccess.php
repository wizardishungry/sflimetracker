Title: <?php echo $podcast->getTitle() ?>
<ul><?php  foreach($torrents as $torrent): ?>
  <li><?php echo $torrent ?></li>
<?php endforeach; ?></ul>
<?php if(!$podcast->getFeedUrl()) echo link_to('add torrent','torrent/upload?podcast_id='.$podcast->getId()); ?>
