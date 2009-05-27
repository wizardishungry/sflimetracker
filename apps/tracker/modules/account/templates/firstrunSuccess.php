<div class="form-wrapper login">
  <form action="<?php echo url_for('@first_run') ?>" method="POST">
            <?php echo $form['_csrf_token']; ?>

    <div id="password_field">
            <?php echo $form['password']->renderError() ?>
            Choose Password
            <?php echo $form['password'] ?>
     </div>

     <div id="password_confirm_field">
            Confirm Password
            <?php echo $form['password_again'] ?>
     </div>

     <div id="intent_field">
            <?php echo $form['intent']->renderError() ?>
       <?php echo $form['intent'] ?>
            <label for="intent">
                I will not use <?php echo sfConfig::get('app_version_name') ?> for copyright infringement.
            </label></td>
     </div>

     <div id="remember_field">
            <?php echo $form['remember_me']->renderError() ?>
            <?php echo $form['remember_me'] ?>
       <?php echo $form['remember_me']->renderLabel() ?>
     </div>
        
     <input type="submit" class="submit" value="Sign in" />
  </form>
</div>
