<?php
    $iframe="torrent_iframe_".$form->getValue('feed_id');
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
        You should be able to upload up to about 
        <?php
            $bytes=file_upload_max_size(); 
            echo "<span title='$bytes bytes'>",pretty_size($bytes),'</span>.';
        ?>
        </td>
    </tr>
    </table>
</form>
