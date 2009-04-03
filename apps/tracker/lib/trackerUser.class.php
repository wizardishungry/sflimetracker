<?php

class trackerUser extends sfBasicSecurityUser
{

  protected $cookie_name='remember_me';
  protected $settings;

  public function setPassword($password)
  {
    $payload=self::crypt($password); // nb no salt!!!!
      if(!SettingPeer::setByKey('password',$payload))
        throw new sfException("Couldn't write password, check database permissions and connect info");
    
    $this->getSettings(); // make sure this is initialized
    $this->settings['password']=$payload;

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

  public function getCryptedString()
  {
    $settings=$this->getSettings();
    return $settings['password'];
  }

  public static function crypt($str)
  {
    return base64_encode(sha1($str,true));
  }

  public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array())
  {

    $this->checkPermissions();
    $this->checkDatabase();
    $this->performTests();

    parent::initialize($dispatcher,$storage, $options);
    $request=sfContext::getInstance()->getRequest();

    if(!$this->isAuthenticated())
    {
      if($request->getPostParameter('password')=='' && $request->getCookie($this->cookie_name))
      {
        $params=Array();
        $params['password']=$request->getCookie($this->cookie_name);
        $form = new LoginForm($this,true,array(),array(),false); // no csrf
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

  public function checkPermissions()
  {
    // fixme array is hardcoded
    $paths=Array(
        'cache',
        'data',
        'data/tracker.db',
        'log',
        'uploads',
    );


    $root=sfContext::getInstance()->getConfiguration()->getRootDir();
    $is_ok=true;
    $bad=Array();
    foreach($paths as $o_path)
    {
        $path=$root.'/'.$o_path;
        if(!is_writable($path))
        {
            $bad[]=$o_path;
            $is_ok=false;
        }
    }
    if(!$is_ok)
    {
        throw new sfException('You have one or more unwritable paths that must be writable -- '. implode(' ',$bad));
    }
  }

  public function checkDatabase()
  {
    $settings=$this->getSettings();
    foreach($this->getSettingsKeys() as $key)
    {
        if(!isset($settings[$key]))
            throw new sfException("Required setting '$key' not found in database");
    }
  }
  
  public function getSettings()
  {
    if(!isset($this->settings))
        $this->settings=SettingPeer::retrieveByKeys($this->getSettingsKeys());
    return $this->settings;
  }

  public function getSettingsKeys()
  {
    return Array('password','intent','test_sideload');
  }

  protected function flushSettingsCache()
  {
    unset($this->settings);
  }

  public function performTests()
  {
    $settings=$this->getSettings();

    if($settings['test_sideload']==null)
        $settings['test_sideload']=$this->testSideload(); 
  }
  public function testSideload()
  {
    // todo implement
  }
}
