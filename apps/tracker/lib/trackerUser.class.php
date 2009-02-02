<?php

class trackerUser extends sfBasicSecurityUser
{

  protected $cookie_name='remember_me';

  protected static function getName()
  {
    $name=sfConfig::get('app_version_name');
    if(!$name)
      $name='LimeTracker';
    return $name;
  }

  public function setPassword($password,$write=true)
  {
    $payload=self::getName();
    $payload.=':{SHA}'; // for apache
    $payload.=self::crypt($password); // nb no salt!!!!
    $payload.="\n";
    if($write)
      if(false==file_put_contents($this->getPasswdPath(),$payload))
        throw new sfException("Couldn't write password, check permissions");
    return $payload;
  }

  public function canWritePasswd()
  {
    return is_writeable($this->getPasswdPath());
  }

  public function getPasswdPath()
  {
    // need a better way fixme
    //$passwd=sfContext::getInstance()->getConfiguration()->getRootDir().DIRECTORY_SEPARATOR.'.htpasswd';
    $sf_root_dir = realpath(dirname(__FILE__).'/../../..');
    $passwd="$sf_root_dir/.htpasswd";

    if(!file_exists($passwd))
      throw new sfException("Can't find .htpasswd");
    if(!is_readable($passwd))
      throw new sfException("Can't read .htpasswd");
    return $passwd; 
  }
  public function getCryptedString() //  nb is this sha1 without salt
  {
    $contents=file_get_contents($this->getPasswdPath());
    if($contents===false)
      throw new sfException("Couldn't read .htpasswd");
    $lines=explode("\n",$contents);
    if(!count($lines))
      throw new sfException("Invalid .htpasswd");

    $default_user=self::getName(); // should be "LimeTracker"
    foreach($lines as $line)
    {
      List($user,$hash)=explode(':',$line);
      if($user==$default_user)
      {
        $hash=preg_replace('#^{SHA}#','',$hash,1,$count);
        if($count!=1)
          throw new sfException("Password must be added using SHA-1.");
        return $hash;
      }
    }
    throw new sfException("Couldn't retrieve default user");
  }

  public function authenticatePassword($password)
  {
    if(gettype($password)!=='string')
      throw new sfException('password is not string; is '.gettype($password));
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
      sfForm::disableCSRFProtection();
      $form = new LoginForm($this);
      $form->bind($params);
      if($form->isValid())
      {
        $this->setAuthenticated(true);
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
