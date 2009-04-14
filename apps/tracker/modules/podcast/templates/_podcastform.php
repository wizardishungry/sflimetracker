<tr id="title_row">
	<th><?php echo $form['title']->renderLabel() ?></th>
	<td>
		<div class="form-field">
			<?php echo $form['title'] ?>
			<p>Podcast title, like: The Show</p>
			<?php echo $form['title']->renderError() ?>
		</div>
		<div class="value">
			<?php echo $form['title']->getValue() ?>
			<a href="#" class="edit-button">edit</a>
		</div>
	</td>
</tr>
<tr id="slug_row">
	<th><?php echo $form['slug']->renderLabel() ?></th>
	<td>
		<div class="form-field">
			<?php echo $form['slug'] ?>
			<p>Used in urls. example of url here todo</p>
			<?php echo $form['slug']->renderError() ?>
		</div>
		<div class="value">
			<?php echo $form['slug']->getValue() ?>
			<a href="#" class="edit-button">edit</a>
		</div>
	</td>
</tr>
<tr id="image_row">
	<th><?php echo $form['image_url']->renderLabel() ?></th>
	<td>
		<div class="form-field">
			<?php echo $form['image_url']->renderError() ?>
			<?php echo $form['image_url'] ?>
			<p>Url to an image </p>
		</div>
		<div class="value">
			<?php if($form['image_url']->getValue())
                echo image_tag($form['image_url']->getValue()),'<br>' ?>
			<?php $form['image_url']->getValue() ?>
			<a href="#" class="edit-button">edit</a>
		</div>
	</td>
</tr>
<tr id="author_row">
	<th><?php echo $form['author']->renderLabel() ?></th>
	<td>
		<div class="form-field">
			<?php echo $form['author'] ?>
			<p>Public author credit, like: The Show Staff </p>
			<?php echo $form['author']->renderError() ?>

		</div>
		<div class="value">
			<?php echo $form['author']->getValue() ?>
			<a href="#" class="edit-button">edit</a>
		</div>
	</td>
</tr>
<tr id="name_row">
	<th><?php echo $form['name']->renderLabel() ?></th>
	<td>
		<div class="form-field">
			<?php echo $form['name'] ?>
			<p>Your real name, put in RSS feeds but not shown in iTunes</p>
			<?php echo $form['name']->renderError() ?>
		</div>
		<div class="value">
			<?php echo $form['name']->getValue() ?>
			<a href="#" class="edit-button">edit</a>
		</div>
	</td>
</tr>
<tr id="email_row">
	<th><?php echo $form['email']->renderLabel() ?></th>
	<td>
		<div class="form-field">
			<?php echo $form['email'] ?>
			<p>Podcast contact email address, put in RSS feeds but not shown in iTunes</p>
			<?php echo $form['email']->renderError() ?>
		</div>
		<div class="value">
			<?php echo $form['email']->getValue() ?>
			<a href="#" class="edit-button">edit</a>
		</div>
	</td>
</tr>
<tr id="link_row">
	<th><?php echo $form['link']->renderLabel() ?></th>
	<td>
		<div class="form-field">
			<?php echo $form['link'] ?>
			<p>This contains an optional url that your podcast will link back to.</p>
			<?php echo $form['link']->renderError() ?>
		</div>
		<div class="value">
			<?php echo $form['link']->getValue() ?>
			<a href="#" class="edit-button">edit</a>
		</div>
	</td>
</tr>
<tr id="itunes_id_row">
	<th><?php echo $form['itunes_id']->renderLabel() ?></th>
	<td>
		<div class="form-field">
			<?php echo $form['itunes_id'] ?>
			<p>Once your podcast is in iTunes, enter your ID number here, like: 123456789</p>
			<?php echo $form['itunes_id']->renderError() ?>
		</div>
		<div class="value">
			<?php echo $form['itunes_id']->getValue() ?>
			<a href="#" class="edit-button">edit</a>
		</div>
	</td>
</tr>
<tr id="description_row">
	<th><?php echo $form['description']->renderLabel() ?></th>
	<td>
		<div class="form-field">
			<?php echo $form['description'] ?>
			<?php echo $form['description']->renderError() ?>
		</div>
		<div class="value">
			<?php echo $form['description']->getValue() ?>
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

