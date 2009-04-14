<?php

/**
 * Feed form.
 *
 * @package    form
 * @subpackage feed
 */
class FeedForm extends BaseFeedForm
{
  public function configure()
  {
    $this->setWidgets(Array(
        'id' => new sfWidgetFormInputHidden(),
        'podcast_id' => new sfWidgetFormInputHidden(),
        'title' => new sfWidgetFormInput(),
        'slug'=> new sfWidgetFormInput(),
        'rss_url'=> new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
        'id' => new sfValidatorInteger(array('required' => false)),
        'podcast_id' => new sfValidatorInteger(array('required' => true)),
        'title' => new sfValidatorString(array('required' => true)),
        'slug' => new sfValidatorString(array('required' => false)),
        'rss_url' => new sfValidatorUrl(Array('required' => false)),
    ));
    
  }
}
