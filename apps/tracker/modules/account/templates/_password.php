<form action="<?php echo url_for('account/password') ?>" method="POST">
  <table>
    <tr>
        <th>Current password</th>
        <td>
        <?php echo $form['_csrf_token'] ?>
        <?php echo $form['current_password']->renderError() ?>
        <?php echo $form['current_password'] ?>
        </td>
    </tr>
    <tr>
        <th>New password</th>
        <td>
        <?php echo $form['password']->renderError() ?>
        <?php echo $form['password'] ?>
        </td>
    </tr>
    <tr>
        <th>Confirm new password</th>
        <td>
        <?php echo $form['password_again']->renderError() ?>
        <?php echo $form['password_again'] ?>
        </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" value="Save" />
      <?php
        echo button_to('Cancel',"account/login")
      ?></td>
    </tr>
  </table>
</form>
