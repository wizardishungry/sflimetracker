<?php page_title('Set Password and Login','h2') ?>
<div class="form-wrapper login">
  <form action="<?php echo url_for('@first_run') ?>" method="POST">
    <table>
        <tr>
            <th><?php echo $form['password']->renderLabel() ?></th>
            <td>
            <?php echo $form['_csrf_token']; ?>
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
            <td>
            <?php echo $form['remember_me']->renderError() ?>
            <?php echo $form['remember_me'] ?>
            </td>
            <td><?php echo $form['remember_me']->renderLabel() ?></td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" value="Sign In" /></td>
      </tr>
    </table>
  </form>
</div>
