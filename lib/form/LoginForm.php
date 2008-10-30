<?php
class LoginForm extends sfForm
{

  protected $user;

  public function __construct(sfUser $user)
  {
    $this->user=$user;
    parent::__construct();
  }
  public function configure()
  {
    $this->setWidgets(array(
      'password'    => new sfWidgetFormInputPassword(),
      'remember_me' => new sfWidgetFormInputCheckbox(),
    ));
    $this->setValidators(array(
      'password'    =>   new sfValidatorCallback(array(
        'required' => true,
        'callback' => Array($this,'passwordCallback'),
        'arguments' => Array($this->getValue('password'))
      )),
      'remember_me' =>  new sfValidatorPass(Array('required'=>false)),
    ));
  }

  public function passwordCallback($validator,$password)
  {
    if(!$this->user->authenticatePassword($password))
    {
      throw new sfValidatorError($validator, $validator->getMessage('invalid'));
    }
  }

 }
