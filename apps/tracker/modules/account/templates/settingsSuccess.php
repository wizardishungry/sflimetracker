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
</ul>
