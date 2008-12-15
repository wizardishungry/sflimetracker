<h2><?php echo 'Episode "', $episode->getTitle(),'" - ',link_to($podcast->getTitle(),'podcast/view?id='.$podcast->getId()) ?></h2>

<?php if($sf_user->isAuthenticated()): ?>
<form action="<?php echo url_for('episode/edit') ?>" method="POST" enctype="multipart/form-data">
    <table>
      <?php echo $form ?>
      <tr>
        <td>&nbsp;</td>
        <td>
          <input type="submit" value="Save"/>
        </td>
        <td>
          <input type="submit" value="Remove"/> <?php /* fixme todo */ ?>
        </td>
      </tr>
    </table>
  </form>
<?php endif; ?>

<ul>
  <?php foreach($torrents as $torrent): ?>
    <li>
      <?php echo $torrent->getFileName() ?>
      <ul>
        <li>Format: <?php echo $torrent->getFeed()->getTags(); ?>
        <li>Address: <?php echo link_to($torrent->getUrl(false),$torrent->getUrl(false)); ?>
        <li>Torrent: <?php echo link_to($torrent->getUrl(),$torrent->getUrl()) ?>
        <li>Hash: <?php echo link_to($torrent->getFileSha1(),$torrent->getMagnet()); ?> </li>
        <?php if($sf_user->isAuthenticated()): ?>
          <li><?php echo link_to('Remove','torrent/delete?id='.$torrent->getId()); ?></li>
        <?php endif; ?>
      </ul>
    </li>
  <?php endforeach; ?>
</ul>

<?php if($missing_feeds): ?>
  We do not have files for the following formats:
<?php endif; ?>
<ul>
<?php if($missing_feeds): ?>
  <?php foreach($missing_feeds as $feed):
    $form=new TorrentForm();
    $form->setDefaults(Array(
      'episode_id'=>$episode->getId(),
      'feed_id'=>$feed->getId(),
      ),Array());
  ?>
    <li>
      <?php echo $feed ?>
      <?php if($sf_user->isAuthenticated()): ?>
        <?php // echo link_to('add file','torrent/upload?feed_id='.$feed->getId().'&episode_id='.$episode->getId()); ?>
        <form action="<?php echo url_for('torrent/upload') ?>" method="POST" enctype="multipart/form-data">
          <table>
            <?php echo $form ?>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">
                <input type="submit" value="upload"/>
              </td>
            </tr>
          </table>
        </form>
      <?php endif; ?>
    </li>
  <?php endforeach; ?>
<?php endif; ?>
<?php if($sf_user->isAuthenticated()): ?>
  <li><?php echo link_to_with_icon('Add New Format…', "add", '@homepage');?></li>
<?php endif; ?>
</ul>
