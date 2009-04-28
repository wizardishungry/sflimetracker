<?php page_title('Settings') ?>

<h3>BitTorrent Tracker</h3>

<form action='<?php echo url_for('account/tracker') ?>' method="POST" enctype="multipart/form-data">
    <?php echo new TrackerForm(); ?>
    <input type='submit' value='Save'>
</form>

<h3>Database</h3>

<?php
    $db = sfContext::getInstance()->getDatabaseManager()->getDatabase('propel'); // symfony uses "default" by default!
    $db_params=$db->getParameterHolder()->getAll(); 

    $dsn=$db_params['dsn'];
?>

<p>
    <blockquote>
        <code><?php echo $dsn; ?></code>
    </blockquote>
</p>


<ul>
    <li>
    <form  action='<?php echo url_for('account/dump') ?>' method="POST">
        <?php echo new sfForm(); ?>
        <input type='submit' value='Export Data'>
    </form>
    </li>
    <li>
    <form action='<?php echo url_for('account/restore') ?>' method="POST" enctype="multipart/form-data">
        <?php echo new RestoreForm(); ?>
        <input type='submit' value='Import Data'>
    </form>
    </li>
</ul>

<h3>Setup</h3>

<ul>
    <li><?php echo link_to('Change password','account/password') ?></li>
    <li><?php echo link_to('Delete everything and start over','account/blam') ?></li>
</ul>

<h3>Diagnostics</h3>
<?php $url_prefix = 'http://'. $sf_request->getHost().$sf_request->getRelativeUrlRoot().'/'; ?>
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
