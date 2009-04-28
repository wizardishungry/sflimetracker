<?php
class TrackerForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(Array(
        'active' => new sfWidgetFormSelectRadio(array('choices' => array(1 => 'yes', 0 => 'no')))
    ));
    $this->setValidators(Array(
        'active' => new sfValidatorInteger(array('required' => true)),
    ));
    $this->setDefaults(Array(
        'active' => SettingPeer::retrieveByKey('tracker_active')
    ));
  }
}
