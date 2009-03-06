<?php

class trackerUser extends sfBasicSecurityUser
{

  protected $cookie_name='remember_me';

  public function setPassword($password,$write=true)
  {
    $payload.=self::crypt($password); // nb no salt!!!!
    if($write)
      if(false==file_put_contents($this->getPasswdPath(),$payload))
        throw new sfException("Couldn't write password, check permissions");
    return $payload;
  }

  public function authenticatePassword($password)
  {
    try
    {
      return $this->getCryptedString()===self::crypt($password);
    }
    catch(sfException $e)
    {
      return false;
    }
  }

  public static function crypt($str)
  {
    return base64_encode(sha1($str,true));
  }

  public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array())
  {
    parent::initialize($dispatcher,$storage, $options);
    $request=sfContext::getInstance()->getRequest();

    if(!$this->isAuthenticated())
    {
      $params=Array();
      $params['password']=$request->getCookie($this->cookie_name);
      if($params['password'])
      {
        sfForm::disableCSRFProtection();
        $form = new LoginForm($this);
        $form->bind($params);
        if($form->isValid())
        {
            $this->setAuthenticated(true);
        }
      }
    }
  }
  public function remember($cookie_eraser=false)
  {
    $response=sfContext::getInstance()->getResponse();
    $request=sfContext::getInstance()->getRequest();
    if($request->getCookie($this->cookie_name) && !$cookie_eraser)
    {
      return;
    }

    $path='/'; // should be symfony root fixme

    if($cookie_eraser)
    {
      $value='';
      $expire=null;
    }
    else
    {
      $value=$request->getPostParameter('password');
      $expire=time()+sfConfig::get('app_admin_remember_me_time',3600);
    }
    $response->setCookie($this->cookie_name,$value,$expire,$path,'',false);
  }
}
