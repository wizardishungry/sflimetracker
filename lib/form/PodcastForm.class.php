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
        'id' => new sfWidgetFormInputHidden(),
        'title'       => new sfWidgetFormInput(),
        'slug'        => new sfWidgetFormInput(),
        'author'      => new sfWidgetFormInput(),
        'email'       => new sfWidgetFormInput(),
        'link'        => new sfWidgetFormInput(),
        'description' => new sfWidgetFormTextarea(),
        'itunes_id'   => new sfWidgetFormInput(),
        'image_url'   => new sfWidgetFormInput(),
    ));
   $this->setValidators(array(
        'id'          => new sfValidatorInteger(array('required' => false)),
        'title'       => new sfValidatorString(array('required' => true)),
        'slug'        => new sfValidatorString(array('required' => false)),
        'author'      => new sfValidatorString(array('required' => false)),
        'email'       => new sfValidatorString(array('required' => false)),
        'link'        => new sfValidatorString(array('required' => false)),
        'description' => new sfValidatorString(array('required' => false)),
        'itunes_id'   => new sfValidatorString(array('required' => false)),
        'image_url'   => new sfValidatorString(array('required' => false)),
    ));
  }
}
