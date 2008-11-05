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
        )),
      'password' => new sfValidatorRegex(array(
        'pattern' => '/^.+$/'
      )),
      'password_again' => new sfValidatorString(Array('required'=>true)),
    );

    if(!$this->user->isAuthenticated())
    {
      unset($widgets['current_password']);
      unset($vals['current_password']);
    }

    $this->setWidgets($widgets);
    $this->setValidators($vals);

    $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('password', sfValidatorSchemaCompare::EQUAL, 'password_again'));
  }

  public function currentPasswordCallback($validator,$password)
  {
    if(!$this->user->authenticatePassword($password))
    {
      throw new sfValidatorError($validator, $validator->getMessage('invalid'));
    }
  }

}
