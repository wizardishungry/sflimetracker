<li>
    <?php echo $torrent->getFileName(), '(', $torrent->getMimeType(), ') ', pretty_size($torrent->getSize()) ?>
    <ul>
    <li>Format: <?php echo $torrent->getFeed()->getTitle(); ?>
    <li>Address: <?php echo link_to($torrent->getUrl(false),$torrent->getUrl(false)); ?>
    <li>Torrent: <?php echo link_to($torrent->getUrl(true),$torrent->getUrl(true)) ?>
    <li>Hash: <?php echo '<a href="',$torrent->getMagnet(),'">',$torrent->getFileSha1(),'</a>'; ?> </li>
    <?php if(@$delete): ?><li><?php echo delete_form_for_object($torrent); ?></li><?php endif; ?>
    </ul>
</li>
