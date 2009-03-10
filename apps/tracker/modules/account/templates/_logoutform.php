<form action="<?php echo url_for('account/logout') ?>" method="POST">
  <?php echo $form['_csrf_token']; ?>
  <input type="submit" value="Logout"/>
</form>
