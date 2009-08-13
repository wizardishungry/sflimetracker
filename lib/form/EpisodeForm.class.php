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
        'created_at'=> new sfWidgetFormInput(),
        'length'=> new sfWidgetFormDuration(),
        'podcast_id' => new sfWidgetFormInputHidden(),
        'title'=> new sfWidgetFormInput(),
        'slug'=> new sfWidgetFormInput(),
        'description' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
        'id' => new sfValidatorInteger(array('required' => false)),
        'created_at' => new sfValidatorDateTime(array('required' => true),
          Array('invalid'=>'Invalid date','required'=>'Please enter a date')),
        'podcast_id' => new sfValidatorInteger(array('required' => true)),
        'title' => new sfValidatorString(array('required' => true)),
        'slug' => new sfValidatorString(array('required' => false)),
        'length' => new sfValidatorRegex(array('pattern' => '/^\d+(:\d+)?(:\d+)?$/'),Array('invalid'=>'Invalid duration')),
        'description' => new sfValidatorString(array('required' => false)),
    ));
    
  }

  public function updateLengthColumn($v)
  {
    if(is_string($v)) {
      @List($s,$m,$h)= array_reverse(explode(":", $v));
      return @$s+60*(@$m+60*@$h);
    } else {
      return $v;
    }
  }

  public function updateCreatedAtColumn($v)
  {
    if(is_string($v)) {
      return date('Y-m-d H:i:s', strtotime($v));
    } else {
      return $v;
    }
  }
}
