<tr>
	<th><?php echo $form['title']->renderLabel() ?>:</th>
	<td>
    <?php echo $form['id'] ?>
    <?php echo $form['_csrf_token'] ?>  
	<?php echo $form['title']->renderError() ?>
	<?php echo $form['title'] ?>
	<p>
        Podcast title, like: The Show	    
	</p>
</td>
</tr>
<tr id="slug_row">
	<th><?php echo $form['slug']->renderLabel() ?>:</th>
	<td>
	<?php echo $form['slug']->renderError() ?>
	<?php echo $form['slug'] ?>
	<p>
		Used in urls. example of url here todo
	</p>
</td>
</tr>
<tr>
	<th><?php echo $form['author']->renderLabel() ?>:</th>
	<td>
	<?php echo $form['author']->renderError() ?>
	<?php echo $form['author'] ?>
	<p>
        Public author credit, like: The Show Staff
	</p>
</td>
</tr>
<tr>
	<th><?php echo $form['name']->renderLabel() ?>:</th>
	<td>
	<?php echo $form['name']->renderError() ?>
	<?php echo $form['name'] ?>
	<p>
        Your real name, put in RSS feeds but not shown in iTunes
	</p>
</td>
</tr>
<tr>
	<th><?php echo $form['email']->renderLabel() ?>:</th>
	<td>
	<?php echo $form['email']->renderError() ?>
	<?php echo $form['email'] ?>
	<p>
        Podcast contact email address, put in RSS feeds but not shown in iTunes
    </p>
</td>
</tr>
<tr>
	<th><?php echo $form['link']->renderLabel() ?>:</th>
	<td>
	<?php echo $form['link']->renderError() ?>
	<?php echo $form['link'] ?>
	<p>
		This contains an optional url that your podcast will link back to.
	</p>
</td>
</tr>
<tr>
	<th><?php echo $form['itunes_id']->renderLabel() ?>:</th>
	<td>
	<?php echo $form['itunes_id']->renderError() ?>
	<?php echo $form['itunes_id'] ?>
	<p>
        Once your podcast is in iTunes, enter your ID number here, like: 123456789
	</p>
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

