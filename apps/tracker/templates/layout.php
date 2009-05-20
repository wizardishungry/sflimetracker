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

    <div id="title">
        <div>
            <?php echo link_to(sfConfig::get('app_version_name'), '@homepage'); ?>
        </div> 
    </div>

    <div id="header_wrapper">
      <div id="header" class="clearfix">
        
    <!--     <div class="version">
          <?php echo '<span title="'.sfConfig::get('app_version_comment').
            '" class="version_rev">',sfConfig::get('app_version_rev'),'</span>',
            '; <span class="runtime_version">Symfony ',SYMFONY_VERSION,'</span>';
          ?> 
        </div> -->
        
        <div id="account_bar">      
          <ul class="clearfix">
          <?php if($sf_user->isAuthenticated()) { ?>
            <li class="">
              <?php echo link_to_with_icon('Home', 'house', '@homepage'); ?>
            </li>
            <li class="">
              <?php echo link_to_with_icon('Settings', 'cog', '@settings'); ?>
            </li>
	  <?php } ?>
            <li class="">
              <?php echo link_to_with_icon('Help', 'help', 'http://limecast.com/tracker'); ?>
            </li>
            <li class="signup last">
              <?php
	        if($sf_user->isAuthenticated()) {
                  echo link_to_with_icon('Sign out', 'user', 'account/logout');
		  $logout_form = new LogoutForm();
		  ?>
		  <form style="display:none;" id="logout_form" action="<?php echo url_for('account/logout') ?>" method="POST">
  		    <?php echo $logout_form['_csrf_token']; ?>
		  </form>
		  <script type="text/javascript">
		    $$('.signup a').first().observe('click', function(event) {
   		      event.stop();
		      $('logout_form').submit();
		    });
		  </script>
		  <?php
                } else {
                  echo link_to_with_icon('Login', 'user', 'account/login');
		}
              ?>
            </li>
          </ul>
                      
        </div>
      </div>
    </div>

    <div id="body_wrapper">

        <?php if ($sf_user->hasFlash('notice')): ?>
        <div id="notice"><?php echo $sf_user->getFlash('notice'); ?></div>
        <?php endif; ?>

      <div id="body">
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


</body>
</html>
