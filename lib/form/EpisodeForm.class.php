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
        'id' => new sfWidgetFormInputHidden(),
        'created_at'=> new sfWidgetFormDateTime(),
        'length'=> new sfWidgetFormInput(),
        'podcast_id' => new sfWidgetFormInputHidden(),
        'title'=> new sfWidgetFormInput(),
        'slug'=> new sfWidgetFormInput(),
        'description' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
        'id' => new sfValidatorInteger(array('required' => false)),
        'created_at' => new sfValidatorDateTime(array('required' => false)),
        'podcast_id' => new sfValidatorInteger(array('required' => true)),
        'title' => new sfValidatorString(array('required' => true)),
        'slug' => new sfValidatorString(array('required' => false)),
        'length' => new sfValidatorString(array('required' => false)),
        'description' => new sfValidatorString(array('required' => false)),
    ));
    
  }

  public function updateLengthColumn($v)
  {
    if(!is_numeric($v)) {
      $length_array = array_reverse(explode(":", $v));
      $length = (int) $length_array[0];
      if(sizeof($length_array) > 1)
        $length += (int) $length_array[1] * 60;
      if(sizeof($length_array) > 2)
        $length += (int) $length_array[2] * 3600;
      return $length;
    } else {
      return $v;
    }
  }
}
