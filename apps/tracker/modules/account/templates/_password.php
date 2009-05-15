<form action="<?php echo url_for('account/password') ?>" method="POST">
  <table>
    <tr>
        <th><?php echo $form['current_password']->renderLabel() ?></th>
        <td>
        <?php echo $form['_csrf_token'] ?>
        <?php echo $form['current_password']->renderError() ?>
        <?php echo $form['current_password'] ?>
        </td>
    </tr>
    <tr>
        <th><?php echo $form['password']->renderLabel() ?></th>
        <td>
        <?php echo $form['password']->renderError() ?>
        <?php echo $form['password'] ?>
        </td>
    </tr>
    <tr>
        <th><?php echo $form['password_again']->renderLabel() ?></th>
        <td>
        <?php echo $form['password_again']->renderError() ?>
        <?php echo $form['password_again'] ?>
        </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" value="Change password" />
      <?php
        echo button_to('Cancel',"account/login")
      ?></td>
    </tr>
  </table>
</form>
