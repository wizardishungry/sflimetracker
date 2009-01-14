<form action="<?php echo url_for('torrent/add') ?>" method="POST" enctype="multipart/form-data">
  <table>
    <?php echo $form ?>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">
        <input type="submit" value="Add"/>
      </td>
    </tr>
  </table>
</form>
