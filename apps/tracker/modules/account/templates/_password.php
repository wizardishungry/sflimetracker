<form action="<?php echo url_for('account/password') ?>" method="POST">
  <table>
    <?php
      echo $form;
    ?>
    <tr>
      <td><input type="submit" value="Change password" /></td>
      <td><?php
        echo button_to('Wait, I remember!',"account/login")
      ?></td>
    </tr>
  </table>
</form>
