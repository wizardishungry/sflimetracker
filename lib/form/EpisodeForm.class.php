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
        'podcast_id' => new sfWidgetFormInputHidden(),
        'title'=> new sfWidgetFormInput(),
        'description' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
        'podcast_id' => new sfValidatorInteger(array('required' => false)),
        'title' => new sfValidatorString(array('required' => true)),
        'description' => new sfValidatorString(array('required' => false)),
    ));
    
  }
}
