<div class="form-wrapper login">
  <form action="<?php echo url_for('account/login') ?>" method="POST">
    <table>
        <tr>
            <th><?php echo $form['password']->renderLabel() ?>:</th>
            <td>
            <?php echo $form['_csrf_token']; ?>
            <?php echo $form['password']->renderError() ?>
            <?php echo $form['password'] ?>
            </td>
        </tr>
        <tr>
            <td>
            <?php echo $form['remember_me']->renderError() ?>
            <?php echo $form['remember_me'] ?>
            </td>
            <td><?php echo $form['remember_me']->renderLabel() ?></td>
        </tr>

        <tr>
            <td>
            <?php echo $form['intent'] ?>
            </td>
            <td>
            <?php echo $form['intent']->renderError() ?>
            <label for="intent">
                I will not use <?php echo sfConfig::get('app_version_name') ?> for copyright infringement.
            </label></td>
        </tr>

      <tr>
        <td>&nbsp;</td>
        <td><?php
          echo link_to('Change',"account/password"),
          ' ',
          link_to('I forget',"http://limecast.com/tracker/reset-password");
        ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" value="Sign in" /></td>
      </tr>
    </table>
  </form>
</div>
