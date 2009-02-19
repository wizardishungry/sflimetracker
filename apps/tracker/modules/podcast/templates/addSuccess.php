<h1>Add Podcast</h1>
<form action="<?php echo url_for('podcast/add') ?>" method="POST" enctype="multipart/form-data">
  <table>
    <?php include_partial('podcast/podcastform', Array('form'=>$form)); ?>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">
        <input type="submit" value="Add Podcast…"/>
      </td>
    </tr>
  </table>
</form>
