<div class="login">
  <form action="<?php echo url_for('account/login') ?>" method="POST">
    <?php echo $form['_csrf_token']; ?>
    <table>
        <tr>
            <th><?php echo $form['password']->renderLabel() ?>:</th>
            <td>
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

        <?php if(!$intent): ?>
        <tr>
            <td>
            <?php echo $form['intent']->renderError() ?>
            <?php echo $form['intent'] ?>
            </td>
            <td><label for="intent">
                I will not use <?php echo sfConfig::get('app_version_name') ?> for copyright infringement.
            </label></td>
        </tr>
        <?php endif; ?>

      <tr>
        <td>&nbsp;</td>
        <td><?php
          echo link_to('Change',"account/password"),
          ' ',
          link_to('I forget',"http://limecast.com/tracker");
        ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" value="Sign in" /></td>
      </tr>
    </table>
  </form>
</div>
