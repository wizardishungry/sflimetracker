<form action="<?php echo url_for('account/login') ?>" method="POST">
  <?php
    if(!isset($form))
      $form=new LoginForm();
    echo $form;
  ?>
  <input type="submit" value="login" />
</form>

