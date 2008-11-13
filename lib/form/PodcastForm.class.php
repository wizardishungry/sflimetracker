<?php

/**
 * Podcast form.
 *
 * @package    form
 * @subpackage Podcast
 */
class PodcastForm extends BasePodcastForm
{
  public function configure()
  {
    $this->setWidgets(Array(
        'feed_url'  => new sfWidgetFormInput(),
    ));
   $this->setValidators(array(
        'feed_url'  => new sfValidatorUrl(array('required' => false)),
    ));
  }
}
