<tr id="title_row">
	<th><?php echo $form['title']->renderLabel() ?></th>
    <td>
		<div>
			<?php echo $form['title'] ?>
			<?php echo $form['title']->renderError() ?>
		</div>
	</td>
</tr>
<?php echo $form['_csrf_token']; ?>
<?php echo $form['podcast_id']; ?>
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

