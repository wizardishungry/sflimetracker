<?php
class LoginForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'password'    => new sfWidgetFormInputPassword(),
    ));
    $this->setValidators(array(
      'password'    =>   new sfValidatorRegex(array(
        'pattern' => '/^'.preg_quote(sfConfig::get('mod_account_password')).'$/'
      ))
    ));
  }
 }
