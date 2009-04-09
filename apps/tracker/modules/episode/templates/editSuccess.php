<?php use_helper('ApacheConfig'); ?>
<h2><?php echo 'Episode "', $episode->getTitle(),'" - ',
    link_to($podcast->getTitle(),'podcast/edit?id='.$podcast->getId()) ?></h2>

<div class="delete-form-wrapper">
  <?php echo delete_form_for_object($episode); ?>
</div>
<div class="form-wrapper">
  <form action="<?php echo url_for('episode/edit') ?>" method="POST" enctype="multipart/form-data">
    <table>
      <?php include_partial('episode/episodeform', Array('form'=>$form)); ?>
      <tr class="form-field">
        <td>&nbsp;</td>
        <td>
          <input type="submit" value="Save" class="save-button" />
          <input type="submit" class="close-form" value="Cancel" />
        </td>
      </tr>
    </table>

    <?php echo $form['podcast_id'] ?>
    <?php echo $form['id'] ?>
    <?php echo $form['_csrf_token'] ?>

  </form>
</div>

<h3>Files</h3>
<ul>
  <?php foreach($torrents as $torrent): ?>
      <?php include_partial('torrent', Array('torrent'=>$torrent,'delete'=>true)); ?>
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
      <?php include_partial('torrent/add',Array('form'=>$form)); ?>
    </li>
  <?php endforeach; ?>
<?php endif; ?>
</ul>
