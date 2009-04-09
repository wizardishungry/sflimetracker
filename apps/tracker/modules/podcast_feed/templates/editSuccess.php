<h1>Editing format for podcast <?php echo link_to($podcast->getTitle(),'podcast/edit?id='.$podcast->getId()) ?></h1>

<div class="form-wrapper">
  <form action="<?php echo url_for('podcast_feed/edit') ?>" method="POST" enctype="multipart/form-data">
  <table>
    <?php echo $form ?>
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

<?php echo delete_form_for_object($podcast_feed,'podcast_feed/delete'); ?>
