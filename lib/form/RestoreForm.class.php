<?php
class RestoreForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array('file'=>new sfWidgetFormInputFile));
    $this->setValidators(array('file'=>new sfValidatorFile(Array('required'=>true))));
  }
 }
