<?php 
    $traces  = sfException::getTraces($exception,'plain' );
?>
<h2 id="message"><?php echo str_replace("\n", '<br />', htmlspecialchars($exception->getMessage(), ENT_QUOTES, sfConfig::get('sf_charset', 'UTF-8'))) ?></h2>
<?php if($exception instanceof limeException):
    $url=$exception->getUrl();
?>
Please visit <a href="<?php echo $url; ?>"><?php echo $url ?></a> for more information.
<?php else: ?>
This exception does not appear to have an associated help page. Bummer.
<?php endif; ?> 
<h2>stack trace</h2>
<ul><li><?php echo implode('</li><li>', $traces) ?></li></ul>
<p id="footer">
symfony v.<?php echo SYMFONY_VERSION ?> - php <?php echo PHP_VERSION ?><br />
</p>

<?php if(! $exception instanceof sfException): ?>
<pre>
<?php echo $exception; ?>
</pre>
<?php endif; ?>
