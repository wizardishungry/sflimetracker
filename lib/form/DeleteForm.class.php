<?php

/**
 * Delete form. For generic validation of deletion (essentially xss protection)
 *
 * @package    form
 * @subpackage Delete 
 */
class DeleteForm extends sfForm 
{
  public function configure()
  {
    $this->setWidgets(Array(
        'id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
        'id' => new sfValidatorInteger(array('required' => true)),
    ));
  }
}
