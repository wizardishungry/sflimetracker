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

    parent::initialize($dispatcher,$storage, $options);

    $this->checkPermissions();
    $this->resetPasswordCheck(); // here?
    $this->checkDatabase();
    $this->performTests();

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
        //'data/resetpassword.txt', //implict from" data"
        'log',
				'json-cache',
        'uploads',
    );


    $root=sfContext::getInstance()->getConfiguration()->getRootDir();
    $is_ok=true;
    $bad=Array();
    foreach($paths as $o_path)
    {
        $path=$root.'/'.$o_path;
        if(!is_writable($path) || !is_readable($path) )
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
        if(!array_key_exists($key,$settings))
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

    if(!$settings['test_sideload'])
    {
        $settings['test_sideload']=$this->testSideload();
        if(!SettingPeer::setByKey('test_sideload',true))
            throw new sfException("Couldn't save to database");
    }
  }
  public function testSideload()
  {

    $request = sfContext::getInstance()->getRequest();
    $url = 'http://'. $request->getHost().$request->getRelativeUrlRoot().'/'.'robots.txt'; // fixme
    $b = new disconnectedCurl($url);
    try {
        $b->run();
        return true;
    }
    catch(Exception $e)
    {
        throw sfException::createFromException($e);
    }
    catch(sfException $e)
    {
        $e2 = new sfException("Couldn't sideload");
        $e2->setWrappedException($e);
        throw $e2;
    }
  }
  public function resetPasswordCheck()
  {
    $root=sfContext::getInstance()->getConfiguration()->getRootDir();
    $path="$root/data/resetpassword.txt";

    if(!file_exists($path))
    {
        $this->setPassword('LimeTracker'); // todo should use constant
        if(!touch($path))
            throw new sfException("$path isn't writable!");
        $this->setFlash('notice', 'Password reset…'); // todo make sure this displays
    }
  }
}
