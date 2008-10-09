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
  public function executeLogin($request)
  {
    
    $this->form = new LoginForm();

    if($this->getUser()->isAuthenticated())
      $this->redirect('@homepage');
    if($request->getMethod () == sfRequest::POST && !$this->getUser()->isAuthenticated())
    {
      $this->form->bind($request->getPostParameters());
      
      if($this->form->isValid())
      {
        $this->getUser()->setAuthenticated(true);
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
    if($request->getMethod () == sfRequest::POST && $this->getUser()->isAuthenticated())
    {
      $this->getUser()->setAuthenticated(false);
      $this->redirect('@homepage');
    }
  }
}
