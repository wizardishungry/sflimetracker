<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<?php include_http_metas() ?>
<?php include_metas() ?>

<?php include_title() ?>

<?php include_slot('feed') ?>

</head>
<body>

<?php echo $sf_content ?>
<hr>
<?php if($sf_user->isAuthenticated()): ?>
  <?php include_partial('account/logoutform') ?>
<?php else:?>
  <?php echo link_to('Login','account/login') ?>
<?php endif; ?>
 |
<?php
echo link_to(sfConfig::get('app_version_name'),sfConfig::get('app_version_url'),
            Array('title'=>sfConfig::get('app_version_comment'))),
            ' ',sfConfig::get('app_version_rev'),
            '; Symfony ',SYMFONY_VERSION;
?>
</body>
</html>
