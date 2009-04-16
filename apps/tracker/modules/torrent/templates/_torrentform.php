<tr>
    <td colspan="2">
        <?php echo $form['episode_id'] ?>
        <?php echo $form['feed_id'] ?>
        <?php echo $form['id'] ?>
        <?php echo $form['_csrf_token'] ?>
	<iframe style="display: none;" height="1" width="1" name="<?php echo $iframe;?>" id="<?php echo $iframe;?>"> </iframe>
	<div style="visibility: hidden;" class="progress">
          <div class="indicator">&nbsp;</div>
	</div>
        <div class="percentage">&nbsp;</div>
    </td>
</tr>
<tr>
	<th><?php echo $form['web_url']->renderLabel() ?>:</th>
	<td>
	<?php echo $form['web_url']->renderError() ?>
	<?php echo $form['web_url'] ?>
	</td>
</tr>
  <?php if ($form->hasGlobalErrors()): ?>
    <tr>
      <td colspan="">
        <ul class="error_list">
          <?php foreach ($form->getGlobalErrors() as $name => $error): ?>
            <li><?php echo $name.': '.$error ?></li>
          <?php endforeach; ?>
        </ul>
      </td>
    </tr>
  <?php endif; ?>
