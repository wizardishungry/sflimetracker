<div class="form-wrapper open-form">

  <form id="torrent_<?php echo $form->getDefault('feed_id'); ?>" action="<?php echo url_for('torrent/add') ?>" method="POST" enctype="multipart/form-data">
      <table>

      <?php include_partial('torrent/torrentform', Array('form'=>$form)) ?>
      <tr>
          <td>&nbsp;</td>
          <td colspan="2">
          <input type="submit" value="Save"/>
          </td>
      </tr>
      </table>
  </form>
  <script type="text/javascript">
    var add_url = '<?php echo url_for('torrent/add'); ?>';
    var csrf_token = '<?php echo _compute_public_path($form->getDefault('_csrf_token'),'json-cache','json'); ?>';
    $('torrent_<?php echo $form->getDefault('feed_id'); ?>').observe('submit', sideload);
  </script>

</div>
