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
    $pw= sfConfig::get('mod_account_password', null);;
    if($pw==null)
      throw new sfException('You must set password in modules/account/config/module.yml');

    if($this->getUser()->isAuthenticated())
      $this->redirect('account/logout');
    if($request->getMethod () == sfRequest::POST && !$this->getUser()->isAuthenticated())
    {
      if($request->getParameter('password')==$pw)
      {
        $this->getUser()->setAuthenticated(true);
        $this->redirect('account/logout');
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
      $this->redirect('account/login');
    }
  }
}
