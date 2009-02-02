<?php 
class myWebRequest extends sfWebRequest
{

    protected $cookie_name='remember_me';

	public function getRemoteAddress()
	{
		$pathArray = $this->getPathInfoArray();
		
		return $pathArray['REMOTE_ADDR'];
	}

  public function setTimeLimit($seconds)
  {
    // nb: this is the amount of time starting NOW
    // zero = ∞
    $restore = set_time_limit($seconds);
  }
  public function isConnectionAborted()
  {
    return connection_aborted();
  }
  public function setIgnoreUserAbort($setting)
  {
    return ignore_user_abort($setting);
  }

  // FIXME I probably really need to move the code under here to trackerUser

  public function initialize(sfEventDispatcher $dispatcher, $parameters = array(), $attributes = array(), $options = array())
  {
    parent::initialize($dispatcher, $parameters, $attributes, $options);

    if(!$ret) return false;

    if(!$this->getUser()->isAuthenticated())
    {
      $params=Array();
      $params['password']=$request->getCookie($this->cookie_name);
      sfForm::disableCSRFProtection();
      $form = new LoginForm($this);
      $form->bind($params);
      if($form->isValid())
      {
        $this->getUser()->setAuthenticated(true);
      }
    }
  }
  public function remember($cookie_eraser=false)
  {
    $response=sfContext::getInstance()->getResponse();
    if($this->getCookie($this->cookie_name) && !$cookie_eraser)
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
      $value=$this->getPostParameter('password');
      $expire=time()+sfConfig::get('app_admin_remember_me_time',3600);
    }
    $response->setCookie($this->cookie_name,$value,$expire,$path,'',false);
  }
 }
