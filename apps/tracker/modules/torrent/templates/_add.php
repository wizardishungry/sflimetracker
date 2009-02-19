<?php
    $iframe="torrent_iframe_".$form->getDefault('feed_id');
?>
<form target="<?php echo $iframe;
?>" action="<?php echo url_for('torrent/add') ?>" method="POST" enctype="multipart/form-data">
    <table>

    <?php include_partial('torrent/torrentform', Array('form'=>$form,'iframe'=>$iframe)) ?>
    <tr>
        <td>&nbsp;</td>
        <td colspan="2">
        <input type="submit" value="add"/>
        </td>
    </tr>
    </table>
</form>
