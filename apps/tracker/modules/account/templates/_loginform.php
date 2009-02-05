<div class="login">
  <form action="<?php echo url_for('account/login') ?>" method="POST">
    <table>
      <?php
        if(!isset($form))
          $form=new LoginForm($sf_user);
        echo $form;
      ?>
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
