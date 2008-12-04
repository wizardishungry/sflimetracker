<?php

/**
 * Episode form.
 *
 * @package    form
 * @subpackage episode
 */
class EpisodeForm extends BaseEpisodeForm
{
  public function configure()
  {
    $this->setWidgets(Array(
        'created_at'=> new sfWidgetFormDateTime(),
        'length'=> new sfWidgetFormTime(Array('with_seconds'=>true,'hours'=>range(0,100))),
        'podcast_id' => new sfWidgetFormInputHidden(),
        'title'=> new sfWidgetFormInput(),
        'slug'=> new sfWidgetFormInput(),
        'description' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
        'created_at' => new sfValidatorDateTime(array('required' => false)),
        'podcast_id' => new sfValidatorInteger(array('required' => true)),
        'title' => new sfValidatorString(array('required' => true)),
        'slug' => new sfValidatorString(array('required' => false)),
        'length' => new sfValidatorTime(array('required' => false)),
        'description' => new sfValidatorString(array('required' => false)),
    ));
    
  }
}
