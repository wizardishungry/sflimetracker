<div class="form-wrapper open-form">

  <form id="torrent_<?php echo $form->getDefault('feed_id'); ?>" action="<?php echo url_for('torrent/add') ?>" method="POST" enctype="multipart/form-data">
      <table>

      <?php if(isset($feed)): ?>
      <tr>
          <td>&nbsp;</td>
          <td colspan="2">
            <h4><?php echo $feed ?></h4>
          </td>
      </tr>
      <?php endif; ?>

      <?php include_partial('torrent/torrentform', Array('form'=>$form)) ?>
      <tr>
          <td>&nbsp;</td>
          <td colspan="2">
          <input type="submit" value="Save"/>
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
	    new Ajax.Request('<?php echo _compute_public_path($form->getDefault('_csrf_token'),'json-cache','json'); ?>', {
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
