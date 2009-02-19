<form action="<?php echo url_for('torrent/add') ?>" method="POST" enctype="multipart/form-data">
  <table>
    <?php include_partial('torrent/add',Array('form'=>$form)) ?>
  </table>
</form>
