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

<div id="document">
  <div id="layout">

    <div id="header_wrapper">
      <div id="header">
        
        <div id="title">
            <span>
              <?php echo image_tag('icons/lime_sm'),' ',sfConfig::get('app_version_name'); ?>
            </span> 
        </div>
        
        <div class="version">
          <?php echo '<span title="'.sfConfig::get('app_version_comment').
            '" class="version_rev">',sfConfig::get('app_version_rev'),'</span>',
            '; <span class="runtime_version">Symfony ',SYMFONY_VERSION,'</span>';
          ?>
        </div>
        
        <div id="account_bar">      
          <ul>
            <li class="">
              <?php echo link_to_with_icon('Home', 'house', '@homepage'); ?>
            </li>
            <li class="">
              <?php echo link_to_with_icon('Settings', 'cog', '@homepage'); ?>
            </li>
            <li class="">
              <?php echo link_to_with_icon('Help', 'help', 'http://limecast.com/tracker'); ?>
            </li>
            <li class="signup">
              <?php
                if($sf_user->isAuthenticated())
                  echo link_to_with_icon('Logout', 'user', 'account/logout');
                else
                  echo link_to_with_icon('Login', 'user', 'account/login');
              ?>
            </li>
          </ul>
                      
                  </div>
      </div>
    </div>
    <div id="body_wrapper">
      <div id="body">
        
        <!-- New Content -->
        <div id="full_width">
          
<div class="content">
  <?php echo $sf_content ?>
</div>

          </div>
        </div>

        <div style="clear: both;">&nbsp;</div>

      </div>
    </div>
  </div>
</div>


</body>
</html>
