<?php echo $form['id'] ?>
<?php echo $form['_csrf_token'] ?>
<tr>
	<th><?php echo $form['title']->renderLabel() ?>:</th>
	<td>
	<?php echo $form['title']->renderError() ?>
	<?php echo $form['title'] ?>
	</td>
</tr>
<tr>
	<th><?php echo $form['slug']->renderLabel() ?>:</th>
	<td>
	<?php echo $form['slug']->renderError() ?>
	<?php echo $form['slug'] ?>
	</td>
</tr>
<tr>
	<th><?php echo $form['author']->renderLabel() ?>:</th>
	<td>
	<?php echo $form['author']->renderError() ?>
	<?php echo $form['author'] ?>
	</td>
</tr>
<tr>
	<th><?php echo $form['name']->renderLabel() ?>:</th>
	<td>
	<?php echo $form['name']->renderError() ?>
	<?php echo $form['name'] ?>
	</td>
</tr>
<tr>
	<th><?php echo $form['email']->renderLabel() ?>:</th>
	<td>
	<?php echo $form['email']->renderError() ?>
	<?php echo $form['email'] ?>
	</td>
</tr>
<tr>
	<th><?php echo $form['link']->renderLabel() ?>:</th>
	<td>
	<?php echo $form['link']->renderError() ?>
	<?php echo $form['link'] ?>
	</td>
</tr>
<tr>
	<th><?php echo $form['itunes_id']->renderLabel() ?>:</th>
	<td>
	<?php echo $form['itunes_id']->renderError() ?>
	<?php echo $form['itunes_id'] ?>
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

