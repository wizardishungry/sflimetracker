<tr>
    <td colspan="2">
        <?php echo $form['podcast_id'] ?>
        <?php echo $form['id'] ?>
        <?php echo $form['_csrf_token'] ?>
        <iframe height="120" width="100%" name="<?php echo $iframe;?>"> </iframe>
    </td>
</tr>
<tr>
	<th><?php echo $form['created_at']->renderLabel() ?>:</th>
	<td>
	<?php echo $form['created_at']->renderError() ?>
	<?php echo $form['created_at'] ?>
	</td>
</tr>
<tr>
	<th><?php echo $form['title']->renderLabel() ?>:</th>
	<td>
	<?php echo $form['title']->renderError() ?>
	<?php echo $form['title'] ?>
	</td>
</tr>
<tr id="slug_row">
	<th><?php echo $form['slug']->renderLabel() ?>:</th>
	<td>
	<?php echo $form['slug']->renderError() ?>
	<?php echo $form['slug'] ?>
	</td>
</tr>
<tr>
	<th><?php echo $form['length']->renderLabel() ?>:</th>
	<td>
	<?php echo $form['length']->renderError() ?>
	<?php echo $form['length'] ?>
	</td>
</tr>
<tr>
	<th><?php echo $form['description']->renderLabel() ?>:</th>
	<td>
	<?php echo $form['description']->renderError() ?>
	<?php echo $form['description'] ?>
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

