<h1>Tracker Settings</h1>

<p>
    More settings
</p>

<h3>Database Connectivity</h3>

<?php include_partial('databaseinfo') ?>
<ul>
    <li>
    <form  action='<?php echo url_for('account/dump') ?>' method="POST">
        <?php echo new sfForm(); ?>
        <input type='submit' value='Dump'>
    </form>
    </li>
    <li>
    <form  action='<?php echo url_for('account/restore') ?>' method="POST" enctype="multipart/form-data">
        <?php echo new RestoreForm(); ?>
        <input type='submit' value='Restore'>
    </form>
    </li>
</ul>
