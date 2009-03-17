<?php
class LoginForm extends sfForm
{

  protected $user;
  protected $intent;

  public function __construct(sfUser $user,$intent=false,$defaults = array(), $options = array(), $CSRFSecret = null)
  {
    $this->user=$user;
    $this->intent=$intent;
    parent::__construct($defaults,$options,$CSRFSecret);
  }
  public function configure()
  {
    $widgets=array(
      'password'    => new sfWidgetFormInputPassword(),
      'remember_me' => new sfWidgetFormInputCheckbox());
    if(!$this->intent) $widgets['intent'] = new sfWidgetFormInputCheckbox();
    $this->setWidgets($widgets);

    $validators=array(
      'password'    =>   new sfValidatorCallback(array(
        'required' => true,
        'callback' => Array($this,'passwordCallback'),
        'arguments' => Array($this->getValue('password'))
      )),
      'remember_me' =>  new sfValidatorPass(Array('required'=>false)),
    );
    if(!$this->intent) $validators['intent'] = new sfValidatorChoice(array('choices' =>Array('on')));
    $this->setValidators($validators);
  }

  public function passwordCallback($validator,$password)
  {
    if(!$this->user->authenticatePassword($password))
    {
      throw new sfValidatorError($validator, $validator->getMessage('invalid'));
    }
  }

 }
