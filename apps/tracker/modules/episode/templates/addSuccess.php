<form action="<?php echo url_for('episode/add') ?>" method="POST" enctype="multipart/form-data">
  <table>
    <?php include_partial('episode/episodeform', Array('form'=>$form)); ?>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">
        <input type="submit" value="add"/>
      </td>
    </tr>
  </table>
</form>
