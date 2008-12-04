Title: <?php echo $episode->getTitle() ?>
<ul>
  <?php foreach($torrents as $torrent): ?>
    <li>
      <?php echo $torrent->getFileName(); ?>
      <?php echo link_to('[torrent]',$torrent->getUrl()) ?>
      <?php echo link_to('[details]','torrent/details?id='.$torrent->getId()); // fixme id numbers in urls boo ?>
      <?php echo link_to('[magnet]',$torrent->getMagnet()); ?> 
    </li>
  <?php endforeach; ?>
</ul>
<?php if($missing_feeds): ?>
  We do not have files for the following subfeeds:
  <ul>
    <?php foreach($missing_feeds as $feed): ?>
      <li>
        <?php echo $feed ?>
        <?php if($sf_user->isAuthenticated()): ?>
          <?php echo link_to('add file','torrent/upload?feed_id='.$feed->getId().'&episode_id='.$episode->getId()); ?>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

