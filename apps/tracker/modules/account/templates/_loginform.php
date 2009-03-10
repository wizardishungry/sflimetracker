<?php
    if(!isset($form))
        $form=new LoginForm($sf_user);
echo $form['_csrf_token'];
?>
<div class="login">
  <form action="<?php echo url_for('account/login') ?>" method="POST">
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
        <tr>
            <td>
            <?php echo $form['legal_mumbo_jumbo']->renderError() ?>
            <?php echo $form['legal_mumbo_jumbo'] ?>
            </td>
            <td><label for="legal_mumbo_jumbo">
                I will not use LimeTracker <!-- replace with constant todo --> for copyright infringement.
            </label></td>
        </tr>

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
