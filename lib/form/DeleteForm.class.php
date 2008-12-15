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
    $this->validatorSchema->setOption('allow_extra_fields', true);
  }
}
