document.observe("dom:loaded", function() {
  if($$('div.form-wrapper').size() > 0) {
    // If we're dealing with an add form
    if($$('div.form-wrapper').first().hasClassName('add')) {
      return;
    }

    // Make the edit buttons 'open' the form
    $$('a.edit-button').each(function(button) {
      button.observe('click', function(event) {
        Event.stop(event);
        $$('div.form-wrapper').first().addClassName('open-form');
      });
    });

    // Close button closes the form
    if($$('input.close-form').size() > 0) {
      $$('input.close-form').first().observe('click', function(event) {
        Event.stop(event);
        $$('div.form-wrapper').first().removeClassName('open-form');
      });
    }

    // Add confirm message to a form
    $$('form.delete-form a.delete-button').each(function(button) {
      button.observe('click', function(click) {
	Event.stop(click);
	click.element().up().getElementsByClassName('confirm')[0].toggle();
	return false;
      });
    });

    // Confirm deletion
    $$('a.confirm-yes').each(function(link) {
      link.observe('click', function(click) {
	click.element().up().up().submit();
      });
    });

    // Deny deletion
    $$('a.confirm-no').each(function(link) {
      link.observe('click', function(click) {
	Event.stop(click);
	click.element().up().hide();
      });
    });

    $('add-format-open').observe('click', function(click) {
      Event.stop(click);
      this.hide();
      $('add-format').show();
    });
    $$('.add-file-open').each(function(link) {
      link.observe('click', function(click) {
	Event.stop(click);
	this.hide();
	$(this.up().getElementsByClassName('add-file')[0]).show();
      });
    });
  }
});