<?php

class trackerUser extends sfBasicSecurityUser
{

  protected $cookie_name='remember_me';
  protected $settings;

  public function setPassword($password)
  {
    if($password!==null)
        $payload=self::crypt($password); // nb no salt!!!!
    SettingPeer::setByKey('password',$payload);
    
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
    $this->checkHtaccess();
    $this->performTests();

    $request=sfContext::getInstance()->getRequest();

    if(!$this->isAuthenticated())
    {
      if($request->getPostParameter('password')=='' && $request->getCookie($this->cookie_name)!=''
            && $request->getMethod () != sfRequest::POST
        )
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

  public function checkHtaccess()
  {
    $root=sfContext::getInstance()->getConfiguration()->getRootDir();
    $path=$root.'/.htaccess';
    if( !file_exists($path) )
    {
        throw new limeException('htaccess', 'Your .htaccess file is missing');
    }    
    if( !is_readable($path) )
    {
        throw new limeException('htaccess', 'Your .htaccess file is unreadable');
    }    
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
        throw new limeException('permissions', 'You have one or more unwritable paths that must be writable -- '. implode(' ',$bad));
    }
  }

  public function checkDatabase()
  {
    $settings=$this->getSettings();
    foreach($this->getSettingsKeys() as $key)
    {
        if(!array_key_exists($key,$settings))
            throw new limeException('missing-setting',"Required setting '$key' not found in database");
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
    return Array('password','test_sideload');
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
        SettingPeer::setByKey('test_sideload',true);
    }
  }
  public function testSideload()
  {
    if(!function_exists('curl_init'))
        throw new limeException('curl','CURL support is not compiled/loaded in PHP.');

    $request = sfContext::getInstance()->getRequest();
    $url = 'http://'. $request->getHost().$request->getRelativeUrlRoot().'/'.'robots.txt'; // fixme
    $b = new disconnectedCurl($url);
    try {
        $b->run();
        return true;
    }
    catch(Exception $e)
    {
        throw limeException::createFromException($e,'sideload');
    }
  }
  public function resetPasswordCheck()
  {
    $root=sfContext::getInstance()->getConfiguration()->getRootDir();
    $path="$root/data/resetpassword.txt";
    $do_flash=$this->getCryptedString()!=null;

    if(!file_exists($path))
    {
        $this->setPassword(null);
        if(!touch($path))
            throw new limeException('permissions',"$path isn't writable!");
        if($do_flash) // the tracker doesn't ship with the resetpassword.txt
            $this->setFlash('notice', 'Password reset');
    }
  }
}
