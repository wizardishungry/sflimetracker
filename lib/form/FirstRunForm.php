<?php
class FirstRunForm extends sfForm
{
  public function configure()
  {

    $widgets=array(
      'password'        => new sfWidgetFormInputPassword(),
      'password_again'  => new sfWidgetFormInputPassword(),
      'intent'          => new sfWidgetFormInputCheckbox(),
      'remember_me'     => new sfWidgetFormInputCheckbox(),
    );

    $vals=array(
      'password' => new sfValidatorRegex(array(
        'pattern' => '/^.+$/'
      )),
      'password_again' => new sfValidatorString(Array('required'=>true)),
      'intent' => new sfValidatorChoice(array('choices' =>Array('on'))),
      'remember_me' =>  new sfValidatorPass(Array('required'=>false)),
    );

    $this->setWidgets($widgets);
    $this->setValidators($vals);

    $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('password', sfValidatorSchemaCompare::EQUAL, 'password_again'));
  }
}
