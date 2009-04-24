<li>
<!--    <?php echo $torrent->getFile(), '(', $torrent->getMimeType(), ') ', pretty_size($torrent->getSize()) ?> -->
    <ul>
    <li><?php echo $torrent->getFeed()->getTitle(); ?>
    <li>Web <?php echo link_to($torrent->getUrl(false),$torrent->getUrl(false)); ?>
    <li>Torrent <?php echo link_to($torrent->getUrl(true),$torrent->getUrl(true)) ?>
    <li>Magnet <?php echo '<a href="',$torrent->getMagnet(),'">',$torrent->getFileSha1(),'</a>'; ?> </li>
    <?php if(@$delete): ?><li><?php echo delete_form_for_object($torrent); ?></li><?php endif; ?>
    </ul>
</li>
