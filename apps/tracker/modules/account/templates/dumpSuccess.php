# As a convenience to you, a copy of this data has been placed on the server at <?php echo $path ?>
#<?php echo sfConfig::get('app_version_name'), ' ', sfConfig::get('app_version_rev');?>

<?php
    readfile($path);
?>
