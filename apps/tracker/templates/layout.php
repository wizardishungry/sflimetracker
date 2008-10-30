<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<?php include_http_metas() ?>
<?php include_metas() ?>

<?php include_title() ?>

<?php include_slot('feed') ?>

</head>
<body class="<?php
  echo "module_",$sf_context->getModuleName();
  echo " action_",$sf_context->getActionName();
?>">

<?php if ($sf_user->hasFlash('notice')): ?>
  <div class="notice"><?php echo $sf_user->getFlash('notice') ?></div>
<?php endif; ?>


<div class="content">
  <?php echo $sf_content ?>
</div>

<?php if($sf_context->getModuleName()!='account'): ?>
  <div class="footer loginlink">
    <?php if($sf_user->isAuthenticated()): ?>
      <?php include_partial('account/logoutform') ?>
    <?php else:?>
      <?php echo link_to('Login','account/login') ?>
    <?php endif; ?>
  </div>
  <div class="footer version">
    <?php
    echo link_to(sfConfig::get('app_version_name'),sfConfig::get('app_version_url'),
                Array('class'=>'version_name','title'=>sfConfig::get('app_version_comment'))),
                ' <span class="version_rev">',sfConfig::get('app_version_rev'),'</span>',
                '; <span class="runtime_version">Symfony ',SYMFONY_VERSION,'</span>';
    ?>
  </div>
<?php endif ?>
</body>
</html>
