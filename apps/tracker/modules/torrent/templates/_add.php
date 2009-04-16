<?php
    $iframe="torrent_iframe_".$form->getDefault('feed_id');
?>
<div class="form-wrapper open-form">
  <form id="torrent_<?php echo $form->getDefault('feed_id'); ?>" target="<?php echo $iframe;
  ?>" action="<?php echo url_for('torrent/add') ?>" method="POST" enctype="multipart/form-data">
      <table>

      <?php include_partial('torrent/torrentform', Array('form'=>$form,'iframe'=>$iframe)) ?>
      <tr>
          <td>&nbsp;</td>
          <td colspan="2">
          <input type="submit" onclick="$('<?php echo $iframe; ?>').show();" value="add"/>
          </td>
      </tr>
      </table>
  </form>
  <script type="text/javascript">
    $('torrent_<?php echo $form->getDefault('feed_id'); ?>').observe('submit', function(submit) {
        Event.stop(submit);
	var form = $(Event.element(submit));
	var progress = $(form.getElementsByClassName('progress')[0]);
	var indicator = progress.childElements().first();
        var percentage = $(form.getElementsByClassName('percentage')[0]);

  	new Ajax.Request('<?php echo url_for('torrent/add'); ?>', { method: 'post', postBody: Form.serializeElements(form.getInputs()) });

        progress.style.visibility = "";

	new PeriodicalExecuter(function(pe) {
	    new Ajax.Request('<?php echo url_for('/json-cache/progress.json'); ?>', {
	      method: 'get',
	      onSuccess: function(transport) {
		  var data = transport.responseText.evalJSON();
		  indicator.style.width = ""+parseInt(data.percent)+"%";
	  	  percentage.update(""+parseInt(data.percent)+"%, "+parseInt(data.finished/1024)+"KB / "+parseInt(data.total/1024)+"KB");
		  if(parseInt(data.percent) == 100) { window.location.reload(); }
	      }
	    });
	}, 0.3);
    });
  </script>

</div>
