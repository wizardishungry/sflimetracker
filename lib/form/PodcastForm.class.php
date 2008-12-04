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
        'cover'       => new sfWidgetFormInputFile(), // dummy FIXME
        'title'       => new sfWidgetFormInput(),
        'author'      => new sfWidgetFormInput(), // dummy FIXME
        'name'        => new sfWidgetFormInput(), // dummy FIXME
        'email'       => new sfWidgetFormInput(), // dummy FIXME
        'link'        => new sfWidgetFormInput(), // dummy FIXME
        'slug'        => new sfWidgetFormInput(),
        'description' => new sfWidgetFormTextarea(),
        'itunes_id'   => new sfWidgetFormInput(), // dummy FIXME
        'custom_xml'  => new sfWidgetFormTextarea(), // dummy FIXME
    ));
   $this->setValidators(array(
        'title'       => new sfValidatorString(array('required' => true)),
        'slug'        => new sfValidatorString(array('required' => false)),
        'description' => new sfValidatorString(array('required' => false)),
    ));
    $this->validatorSchema->setOption('allow_extra_fields', true); // remove this eventually FIXME
  }
}
