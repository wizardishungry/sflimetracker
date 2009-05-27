  <form action="<?php echo url_for('account/login') ?>" method="POST">
    <?php echo $form['_csrf_token']; ?>
  <div id="password_field">
    <?php echo $form['password']->renderError() ?>
    <?php echo $form['password']->renderLabel() ?>
    <?php echo $form['password'] ?>
  </div>
  
  <div class="links">
    <?php echo link_to('Change',"account/password"); ?>
    &middot;
    <?php echo link_to('I forgot',"http://limecast.com/tracker/reset-password"); ?>
  </div>

  <div id="remember_field">
    <?php echo $form['remember_me']->renderError() ?>
    <?php echo $form['remember_me'] ?>
    <?php echo $form['remember_me']->renderLabel(null, array('id' => 'remember_label')) ?>
  </div>

  <input type="submit" class="submit" value="Sign in" />
</form>
