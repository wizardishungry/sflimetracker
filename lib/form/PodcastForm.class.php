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
        'title'       => new sfWidgetFormInput(),
        'slug'        => new sfWidgetFormInput(),
        'description' => new sfWidgetFormTextarea(),
    ));
   $this->setValidators(array(
        'title'       => new sfValidatorString(array('required' => true)),
        'slug'        => new sfValidatorString(array('required' => false)),
        'description' => new sfValidatorString(array('required' => false)),
    ));
  }
}
