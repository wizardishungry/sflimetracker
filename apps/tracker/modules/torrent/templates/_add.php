<?php
    $iframe="torrent_iframe_".$form->getDefault('feed_id');
?>
<form target="<?php echo $iframe;
?>" action="<?php echo url_for('torrent/add') ?>" method="POST" enctype="multipart/form-data">
    <table>
    <tr>
        <td>
        </td>
        <td>
            <iframe height="120" width="100%" name="<?php echo $iframe;?>"> </iframe>
        </td>
    </tr>
    <?php echo $form ?>
    <tr>
        <td>&nbsp;</td>
        <td colspan="2">
        <input type="submit" value="add"/>
        </td>
    </tr>
    </table>
</form>
