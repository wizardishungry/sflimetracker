<tr class="progress-iframe" style="display: none;">
    <td colspan="2">
        <iframe height="120" width="100%" name="<?php echo $iframe;?>"> </iframe>
    </td>
</tr>
<tr>
	<th><?php echo $form['created_at']->renderLabel() ?></th>
	<td>
		<div class="form-field">
			<?php echo $form['created_at']->renderError() ?>
			<?php echo $form['created_at'] ?>
			<p>Creation date, like: 2008-12-25 13:34:00</p>
		</div>
		<div class="value">
			<?php echo $form['created_at']->getValue(); ?>
			<a href="#" class="edit-button">edit</a>
		</div>
	</td>
</tr>
<tr>
	<th><?php echo $form['title']->renderLabel() ?></th>
	<td>
		<div class="form-field">
			<?php echo $form['title']->renderError() ?>
			<?php echo $form['title'] ?>
			<p>Title of the episode</p>
		</div>
		<div class="value">
			<?php echo $form['title']->getValue(); ?>
			<a href="#" class="edit-button">edit</a>
		</div>
	</td>
</tr>
<tr>
	<th><?php echo $form['slug']->renderLabel() ?></th>
	<td>
		<div class="form-field">
			<?php echo $form['slug']->renderError() ?>
			<?php echo $form['slug'] ?>
			<p>Used in the episode address</p>
		</div>
		<div class="value">
			<?php echo $form['slug']->getValue(); ?>
			<a href="#" class="edit-button">edit</a>
		</div>
	</td>
</tr>
<tr>
	<th><?php echo $form['length']->renderLabel() ?></th>
	<td>
		<div class="form-field">
			<?php echo $form['length']->renderError() ?>
			<?php echo $form['length'] ?>
			<p>Duration of the episode, like: 25:14 (which equals 25 minutes, 14 seconds)</p>
		</div>
		<div class="value">
			<?php echo $form->getObject()->getFormattedLength(); ?>
			<a href="#" class="edit-button">edit</a>
		</div>
	</td>
</tr>
<tr>
	<th><?php echo $form['description']->renderLabel() ?></th>
	<td>
		<div class="form-field">
			<?php echo $form['description']->renderError() ?>
			<?php echo $form['description'] ?>
		</div>
		<div class="value">
			<?php echo $form['description']->getValue(); ?>
			<a href="#" class="edit-button">edit</a>
		</div>
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

