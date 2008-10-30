<?php include_partial('heading') ?>
<?php
if(!isset($payload)):
  include_partial('password', Array('form'=>$form) );
else: ?>
  <p>
  We couldn't write your new password because 
  <?php if($sf_user->isAuthenticated()): ?>
  of a system error -- <?php echo ''.$exception ?>
  <?php else: ?>
  you are not logged in.
  <?php endif; ?>
  To complete changing your password please replace the contents of the file
  <tt class="filename"><?php echo $sf_user->getPasswdPath() ?></tt> on the server with
  <pre class="snippet"><?php echo $payload ?></pre> and
  <?php echo link_to('login','account/login'); ?> with your new password.
  </p>
<?php endif;
