<?php

/**
 * account actions.
 *
 * @package    sflimetracker
 * @subpackage account
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class accountActions extends sfActions
{
  protected $cookie_name='remember_me';

  public function executeLogin($request)
  {
    
    $user = $this->getUser();

    if($user->isAuthenticated())
      $this->redirect('@homepage');
    if(($request->getMethod () == sfRequest::POST||$request->getCookie($this->cookie_name))
      && !$this->getUser()->isAuthenticated())
    {
      $params=$request->getPostParameters();

      if($request->getCookie($this->cookie_name)&&!isset($params['password']))
      {
       $params['password']=$request->getCookie($this->cookie_name);
       sfForm::disableCSRFProtection();
      }

      $form = $this->form = new LoginForm($this->getUser());
      $form->bind($params);
      
      if($form->isValid())
      {
        $this->getUser()->setAuthenticated(true);
        if($form->getValue('remember_me'))
        {
          $this->remember();
        }
        if($request->getReferer())
        {
          $this->redirect($request->getReferer());
        }
        {
          $this->redirect('@homepage');
        }
      }
      else
      {
        return sfView::ERROR;
      }
    }
  }
 
  public function executeLogout($request)
  {
    $user=$this->getUser();
    if($request->getMethod () == sfRequest::POST && $user->isAuthenticated())
    {
      $this->remember(true); // also known as "forget"
      $user->setAuthenticated(false);
      $this->redirect('@homepage');
    }
  }
  public function executePassword($request)
  {
    $user=$this->getUser();
    $payload=null;
    $this->form = $form = new PasswordForm($user);
    if($request->getMethod () == sfRequest::POST)
    {
      $form->bind($request->getPostParameters());
      if($form->isValid())
      {

        $can_write=$user->canWritePasswd();
        $payload=$user->setPassword($form->getValue('password'),$can_write);
        if($can_write)
        {
          $user->setAuthenticated(false);
          $user->setFlash('notice','Password changed');
          return $this->redirect('@homepage');
        }

        $this->payload=$payload;
        if(isset($e))
          $this->exception=$e;
      }
    }
  }
  protected function remember($cookie_eraser=false)
  {
    $response=$this->getResponse();
    $request=$this->getRequest();
    if($request->getCookie($this->cookie_name) && !$cookie_eraser)
    {
      return;
    }

    $path='/';

    if($cookie_eraser)
    {
      $value='';
      $expire=null;
    }
    else
    {
      $value=$request->getPostParameter('password');
      $expire=time()+sfConfig::get('app_admin_remember_me_time');
    }
    $response->setCookie($this->cookie_name,$value,$expire,$path,'',false);
  }
}
