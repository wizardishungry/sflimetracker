<div class="form-wrapper open-form">
  <form action="<?php echo url_for('podcast_feed/edit') ?>" method="POST" enctype="multipart/form-data">
  <table>
<tr>
	<th><?php echo $form['title']->renderLabel() ?></th>
	<td>
		<div class="form-field">
			<?php echo $form['title'] ?>
			<p>Becomes <span class="perm-preview">(empty)</span> in torrent filenames</p>
			<?php echo $form['title']->renderError() ?>
		</div>
		<script type="text/javascript">
		  watchForSlug($$('input#title').first(), $$('.perm-preview').first());
		</script>
		<div class="value">
			<?php echo $form['title']->getValue(); ?>
			<a href="#" class="edit-button">edit</a>
		</div>
	</td>
</tr>
<tr>
	<th><?php echo $form['rss_url']->renderLabel() ?></th>
	<td>
		<div class="form-field">
			<?php echo $form['rss_url'] ?>
			<p>Url for the format's feed</p>
			<?php echo $form['rss_url']->renderError() ?>
		</div>
		<div class="value">
			<?php echo $form['rss_url']->getValue(); ?>
			<a href="#" class="edit-button">edit</a>
		</div>
	</td>
</tr>
    <tr>
    <td>&nbsp;</td>
    <td>
        <input type="submit" value="Save"/>
    </td>
    <td>

    </td>
    </tr>
  </table>
  </form>
</div>

<div class="delete-form-wrapper">
  <?php echo delete_form_for_object($podcast_feed,'podcast_feed/delete'); ?>
</div>
