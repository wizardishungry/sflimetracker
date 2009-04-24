<?php page_title($episode->getTitle().link_to($podcast->getTitle(),$podcast->getUri())) ?>
<h3>Files</h3>
<ul>
  <?php foreach($torrents as $torrent): ?>
      <?php include_partial('torrent', Array('torrent'=>$torrent)); ?>
  <?php endforeach; ?>
</ul>

