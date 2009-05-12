<?php
class PasswordForm extends sfForm
{
  protected $user;

  public function __construct(sfUser $user)
  {
    $this->user=$user;
    parent::__construct();
  }

  public function configure()
  {

    $widgets=array(
      'current_password'=> new sfWidgetFormInputPassword(),
      'password'        => new sfWidgetFormInputPassword(),
      'password_again'  => new sfWidgetFormInputPassword(),
    );

    $vals=array(
      'current_password'    =>   new sfValidatorCallback(array(
          'required' => true,
          'callback' => Array($this,'currentPasswordCallback'),
          'arguments' => Array($this->getValue('current_password'))
      ),Array(
        'invalid' => 'Incorrect password',
        'required' => 'Please enter your password'
      )),
      'password' => new sfValidatorRegex(array(
        'pattern' => '/^.+$/'
      ),Array(
         'invalid'=>'Please choose a password',
         'required'=>'Please choose a password',
      )),
      'password_again' => new sfValidatorString(Array(
            'required'=>true
        ),Array(
         'required'=>'Please enter password again',
        ))
    );

    $this->setWidgets($widgets);
    $this->setValidators($vals);

    $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare(
        'password', sfValidatorSchemaCompare::EQUAL, 'password_again',
        Array(),
        Array('invalid'=>"Passwords don't match")
    ));
  }

  public function currentPasswordCallback($validator,$password)
  {
    if(!$this->user->authenticatePassword($password))
    {
      throw new sfValidatorError($validator, $validator->getMessage('invalid'));
    }
  }

}
