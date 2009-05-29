function sideload(submit) {
  Event.stop(submit);
  var form = $(Event.element(submit));
  var progress = $(form.getElementsByClassName('progress')[0]);
  $(form.getElementsByClassName('progress-container')[0]).show();
  var indicator = progress.childElements().first();
  var percentage = $(form.getElementsByClassName('percentage')[0]);

  new Ajax.Request(add_url, { method: 'post', postBody: Form.serializeElements(form.getInputs()) });

  progress.style.visibility = "";

  new PeriodicalExecuter(function(pe) {
    var curDate = new Date().getTime();
    new Ajax.Request(csrf_token+'?'+curDate, {
      method: 'get',
      onSuccess: function(transport) {
	var data = transport.responseText.evalJSON();
	indicator.style.width = ""+parseInt(data.percent)+"%";
	percentage.update(""+parseInt(data.percent)+"%, "+parseInt(data.finished/1024).toLocaleString()+"KB / "+
        parseInt(data.total/1024).toLocaleString()+"KB");
	if(parseInt(data.percent) >= 100) { window.location.reload(); }
      }
    });
  }, 0.3);
};
