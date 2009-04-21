<?php page_title('Delete Everything and Start Over'); ?>
<form  action='<?php echo url_for('account/blam') ?>' method="POST">
    <?php echo new sfForm(); ?>
    <input type='submit' value='Yes, I want to delete everything'>
</form>
