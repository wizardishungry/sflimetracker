<div class="login">
  <div class="heading">
    <h1 class="app_name"><?php echo sfConfig::get('app_version_name') ?></h1>
    <h4 class="version" title="<?php echo sfConfig::get('app_version_comment') ?>">
      version <?php echo sfConfig::get('app_version_rev') ?>
    </h4>
    <p>Please enter your password to continue</p>
  </div>
  <form action="<?php echo url_for('account/login') ?>" method="POST">
    <table>
      <?php
        if(!isset($form))
          $form=new LoginForm($sf_user);
        echo $form;
      ?>
      <tr>
        <td><input type="submit" value="Login" /></td>
        <td><?php
          echo button_to_function('I forgot my password!',
          "alert('Replace this with a jQuery box that tells you how to reset pw todo!')")
        ?></td>
      </tr>
    </table>
  </form>
</div>
