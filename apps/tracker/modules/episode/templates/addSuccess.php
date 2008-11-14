<form action="<?php echo url_for('episode/add') ?>" method="POST" enctype="multipart/form-data">
  <table>
    <?php echo $form ?>
    <tr>
      <td colspan="2">
        <input type="submit" value="add"/>
      </td>
    </tr>
  </table>
</form>
