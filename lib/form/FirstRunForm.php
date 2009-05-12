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
      ),
      Array(
         'invalid'=>'Please choose a password',
         'required'=>'Please choose a password',
      )),
      'password_again' => new sfValidatorString(Array('required'=>true),Array('required'=>' ')),
      'intent' => new sfValidatorChoice(array('choices' =>Array('on')),array('invalid'=>'Please state your intent')),
      'remember_me' =>  new sfValidatorPass(Array('required'=>false)),
    );

    $this->setWidgets($widgets);
    $this->setValidators($vals);

    $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare(
        'password', sfValidatorSchemaCompare::EQUAL, 'password_again',
        Array(),
        Array('invalid'=>"Passwords don't match")
    ));
  }
}
