<?php if(!$sf_user->isAuthenticated()): ?>
  <p>
    You cannot change your password right now because you aren't logged in.
    However, you can enter your new password here to get instructions on how to change it.
  </p>
<?php endif; ?>
<form action="<?php echo url_for('account/password') ?>" method="POST">
  <table>
    <?php
      echo $form;
    ?>
    <tr>
      <td><input type="submit" value="Change password" /></td>
      <td><?php
        echo button_to('Wait, I remember!',"account/login")
      ?></td>
    </tr>
  </table>
</form>
