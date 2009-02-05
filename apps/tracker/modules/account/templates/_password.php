<form action="<?php echo url_for('account/password') ?>" method="POST">
  <table>
    <?php
      echo $form;
    ?>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" value="Change password" />
      <?php
        echo button_to('Wait, I remember!',"account/login")
      ?></td>
    </tr>
  </table>
</form>
