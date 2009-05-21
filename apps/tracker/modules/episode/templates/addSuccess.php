<?php page_title('Add Episode') ?>

<div class="form-wrapper open-form">
  <form action="<?php echo url_for('episode/add') ?>" method="POST" enctype="multipart/form-data">
    <table>
      <?php include_partial('episode/episodeform', Array('form'=>$form)); ?>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2">
          <input type="submit" value="Save"/>
        </td>
      </tr>
    </table>

    <?php echo $form['podcast_id'] ?>
    <?php echo $form['id'] ?>
    <?php echo $form['_csrf_token'] ?>

  </form>
</div>
