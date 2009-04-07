document.observe("dom:loaded", function() {
  if($$('div.form-wrapper').size() > 0) {
    // initially hide all containers for tab content
    $$('.form-field').invoke('hide');

    $$('a.edit-button').each(function(button) {
      button.observe('click', function(event) {
        Event.stop(event);
        $$('.form-field').invoke('show');
        $$('div.value').invoke('hide');
        $$('div.form-wrapper').first().addClassName('open-form');
      });
    });

    $$('input.close-form').first().observe('click', function(event) {
      Event.stop(event);
      $$('.form-field').invoke('hide');
      $$('div.value').invoke('show');
      $$('div.form-wrapper').first().removeClassName('open-form');
    });
  }
});