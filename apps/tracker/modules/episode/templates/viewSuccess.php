<?php use_helper('ApacheConfig'); ?>
<h2><?php echo 'Episode "', $episode->getTitle(),'" - ',link_to($podcast->getTitle(),$podcast->getUri()) ?></h2>
<h3>Files</h3>
<ul>
  <?php foreach($torrents as $torrent): ?>
    <li>
      <?php echo $torrent->getFileName(), '(', $torrent->getMimeType(), ') ', pretty_size($torrent->getSize()) ?>
      <ul>
        <li>Format: <?php echo $torrent->getFeed()->getTags(); ?>
        <li>Address: <?php echo link_to($torrent->getUrl(false),$torrent->getUrl(false)); ?>
        <li>Torrent: <?php echo link_to($torrent->getUrl(),$torrent->getUrl()) ?>
        <li>Hash: <?php echo '<a href="',$torrent->getMagnet(),'">',$torrent->getFileSha1(),'</a>'; ?> </li>
      </ul>
    </li>
  <?php endforeach; ?>
</ul>

