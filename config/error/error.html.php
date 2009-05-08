<?php if($error instanceof limeException): ?>
<?php echo $exception; ?>
<?php else: ?>
This exception does not appear to have an associated help page. Bummer.
<?php echo $exception; ?>
<?php endif; ?> 
