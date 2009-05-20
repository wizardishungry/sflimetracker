<?php page_title('New Podcast') ?>
<div class="form-wrapper open-form">
  <form action="<?php echo url_for('podcast/add') ?>" method="POST" enctype="multipart/form-data">
    <table>
      <?php include_partial('podcast/podcastform', Array('form'=>$form)); ?>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2">
          <input type="submit" value="Save"/>
        </td>
      </tr>
    </table>

    <?php echo $form['id'] ?>
    <?php echo $form['_csrf_token'] ?>
  
  </form>
</div>
