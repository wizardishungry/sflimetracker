Title: <?php echo $episode->getTitle() ?>
<?php foreach($torrents as $torrent): ?>
  <li>
    <?php echo $torrent->getFileName(); ?>
    <?php echo link_to('[torrent]',$torrent->getUrl()) ?>
    <?php echo link_to('[details]','torrent/details?id='.$torrent->getId()); // fixme id numbers in urls boo ?>
    <?php echo link_to('[magnet]',$torrent->getMagnet()); ?> 
  </li>
<?php endforeach; ?>
<?php if($sf_user->isAuthenticated()): ?>
  <?php echo link_to('add file','torrent/upload?episode_id='.$episode->getId()); ?>
<?php endif; ?>
