<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>

    <?php include_title() ?>

    <?php include_slot('feed') ?>
    <link rel="icon" type="image/png" href="<?php echo image_path('icons/lime_sm') ?>"/>
  </head>

  <body class="<?php
    echo "module_",$sf_context->getModuleName();
    echo " action_",$sf_context->getActionName();
  ?>">
    
    <div class="version">
      <?php echo '<span title="'.sfConfig::get('app_version_comment').
                 '" class="version_rev">',sfConfig::get('app_version_rev'),'</span>',
                 '; <span class="runtime_version">Symfony ',SYMFONY_VERSION,'</span>';
      ?>
      &middot;
      <a href="http://limecast.com/tracker">http://limecast.com/tracker</a>
    </div>

    <div id="towers-wrapper">
      <div id="towers"></div>
    </div>

    <div id="login">
      <h1>LimeTracker</h1>

      <?php echo $sf_content ?>
    </div>
  </body>
</html>