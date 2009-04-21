<h1 class="title page"><?php echo sfConfig::get('app_version_name') ?></h1>
<h4 class="version" title="<?php echo sfConfig::get('app_version_comment') ?>">
version <?php echo sfConfig::get('app_version_rev') ?>
</h4>
<h5 class="url">
<?php echo link_to(sfConfig::get('app_version_url'),sfConfig::get('app_version_url')); ?>
</h5>
