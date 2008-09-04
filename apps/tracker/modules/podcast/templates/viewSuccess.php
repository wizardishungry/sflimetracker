Title: <?php echo $podcast->getTitle() ?>
<ul><?php  foreach($torrents as $torrent): ?>
  <li>
    <?php echo link_to($torrent->getTitle(),$torrent->getUrl()) ?>
    <?php echo link_to('[details]','torrent/details?id='.$torrent->getId()); // fixme id numbers in urls boo ?>
  </li>
<?php endforeach; ?></ul>
<?php if(!$podcast->getFeedUrl()) echo link_to('add torrent','torrent/upload?podcast_id='.$podcast->getId()); ?>
