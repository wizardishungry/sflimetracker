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
