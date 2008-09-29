<form action="<?php echo url_for('podcast/add') ?>" method="POST" enctype="multipart/form-data">
  <table>
  NB: This field is not currently used.
    <?php echo $form ?>
    <tr>
      <td colspan="2">
        <input type="submit" value="Add Podcast…"/>
      </td>
    </tr>
  </table>
</form>
