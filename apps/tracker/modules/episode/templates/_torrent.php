<div class="torrent_file">
    <!--    <?php echo $torrent->getFile(), '(', $torrent->getMimeType(), ') ', pretty_size($torrent->getSize()) ?> -->
    
    <?php echo $torrent->getFeed()->getTitle(); ?>
    
    <ul class="links">
      <div class="divider">&nbsp;</div>
      <li>
         <span>Web </span>
         <?php echo link_to($torrent->getUrl(false),$torrent->getUrl(false)); ?>
      </li>
      <li>
         <span>Torrent </span>
         <?php echo link_to($torrent->getUrl(true),$torrent->getUrl(true)) ?>
      </li>
      <li class="clearfix">
         <span>Magnet </span>
         <?php echo '<a href="',$torrent->getMagnet(),'">',$torrent->getFileSha1(),'</a>'; ?> 
      </li>
    </ul>
    
    <?php if(@$delete): ?><?php echo delete_form_for_object($torrent); ?><?php endif; ?>
</div>
