<div class="login">
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
          echo button_to('I forgot my password!',"account/password")
        ?></td>
      </tr>
    </table>
  </form>
</div>
