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

  public function initialize(sfEventDispatcher $dispatcher, $parameters = array(), $attributes = array())
  {
    $ret = parent::initialize($dispatcher,$parameters,$attributes);
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

    return $ret;
  }
 }
