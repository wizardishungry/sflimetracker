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

<div id="document">
  <div id="layout">

    <div id="header_wrapper">
      <div id="header">
        
        <div id="title">
            <span>
              <?php echo link_to_with_icon(sfConfig::get('app_version_name'), 'lime_sm', 'http://limecast.com/tracker'); ?>
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
                      

<div class="quick_signin global palette top_bar" style="display: none;">
  <div class="titlebar">
          <a href="#" class="close">Exit</a>
      </div>
  <div class="message">
    <?php if ($sf_user->hasFlash('notice')): ?>
      <?php echo $sf_user->getFlash('notice') ?>
    <?php endif; ?>
  </div>
                      
  <div class="contents">
    <form action="/session" method="post"><div style="margin: 0pt; padding: 0pt;"><input name="authenticity_token" value="479d51aeb685fb93d2d43cff20e8d2bf8543b228" type="hidden"></div>      <div class="field">
        <label for="login_global">User</label>
        <div>
          <input id="login_global" class="text login" name="user[login]" type="text">
        </div>
      </div>
      <div class="field">
        <label for="password_global">Pass</label>
        <div>
          <input id="password_global" class="text password" name="user[password]" type="password">
        </div>
      </div>
      <div class="field sign_up" style="display: none;">
        <label for="email_global">Email</label>
        <div>
          <input id="email_global" class="text email" name="user[email]" type="text">
        </div>
      </div>

      <div class="response_container">
        <a href="http://limecast.com/forgot">I forgot my password</a>      </div>

      <div class="controls">
        <input class="button signup_button" id="signup_global" name="commit" value="Sign Up" type="submit">
        <input class="button signin_button" id="signin_global" name="commit" value="Sign In" type="submit">
      </div>
    </form>  </div>
</div>
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
