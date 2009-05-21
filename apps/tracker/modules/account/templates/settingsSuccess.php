<?php page_title('Settings') ?>

<h3>BitTorrent Tracker</h3>

<div class="settings_section">
    Announce URL 
    <code><?php echo url_for('client/announce',true); ?></code>

    Scrape URL 
    <code><?php echo url_for('client/scrape',true); ?></code>

  <form action='<?php echo url_for('account/tracker') ?>' method="POST" enctype="multipart/form-data">
    <?php echo new TrackerForm(); ?>
    <input type='submit' value='Save'>
  </form>
</div>

<h3>Database</h3>

<?php
    $db = sfContext::getInstance()->getDatabaseManager()->getDatabase('propel'); // symfony uses "default" by default!
    $db_params=$db->getParameterHolder()->getAll(); 

    $dsn=$db_params['dsn'];
?>
<div class="settings_section">
  <code><?php echo $dsn; ?></code>

    <form  action='<?php echo url_for('account/dump') ?>' method="POST">
        <?php echo new sfForm(); ?>
        <input type='submit' value='Export Data'>
    </form>

    <form action='<?php echo url_for('account/restore') ?>' method="POST" enctype="multipart/form-data">
        <?php echo new RestoreForm(); ?>
        <input type='submit' value='Import Data'>
    </form>
</div>

<h3>Setup</h3>

<div class="settings_section">
  <ul>
    <li><?php echo link_to('Change password','account/password') ?></li>
    <li><?php echo link_to('Delete everything and start over','account/blam') ?></li>
  </ul>
</div>

<h3>Diagnostics</h3>
<?php $url_prefix = 'http://'. $sf_request->getHost().$sf_request->getRelativeUrlRoot().'/'; ?>
<div class="settings_section">
  <ul>
    <li>
        Database
        <?php 
            $url=url_for('account/dbinfo',true);
            echo link_to($url,$url);
        ?>
    </li>
    <li>
        Environment
        <?php
            $url=$url_prefix.'limetracker-assert.php';
            echo link_to($url,$url);
        ?>
    </li>
    <li>
        PHPinfo
        <?php
            $url.='?phpinfo=sure';
            echo link_to($url,$url);
        ?>
    </li>
  </ul>
</div>
