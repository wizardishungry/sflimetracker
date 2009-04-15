<?php

/**
 * account actions.
 *
 * @package    sflimetracker
 * @subpackage account
 */
class accountActions extends sfActions
{

  public function executeLogin($request)
  {
    
    $user = $this->getUser();
    $this->intent = $intent = SettingPeer::retrieveByKey('intent');
    $form = $this->form = new LoginForm($user,$intent);

    if($user->isAuthenticated())
      $this->redirect('@homepage');

    if($request->getMethod () == sfRequest::POST)
    {
      $params=$request->getPostParameters();

      $form->bind($params);
      
      if($form->isValid())
      {
        $this->getUser()->setAuthenticated(true);
        if(!$intent)
             SettingPeer::setByKey('intent',true);

        if($form->getValue('remember_me'))
        {
          $user->remember();
        }

        if($request->getReferer())
        {
          $this->redirect($request->getReferer());
        }
        else
        {
          $this->redirect('@homepage');
        }

      }
      else
      {
        $user->setFlash('notice','Oops, you probably entered your password wrong!');
        return sfView::ERROR;
      }
    }
  }
 
  public function executeLogout($request)
  {
    $user=$this->getUser();
    $this->form = $form = new LogoutForm();
    if($request->getMethod() == sfRequest::POST)
    {
        $form->bind($request->getPostParameters());
        if($form->isValid() && $user->isAuthenticated())
        {
            $user->remember(true); // also known as "forget"
            $user->setAuthenticated(false);
            $this->redirect('@root');
            // todo add flash
        }
    }
  }
  public function executePassword($request)
  {
    $user=$this->getUser();
    $this->form = $form = new PasswordForm($user);
    if($request->getMethod () == sfRequest::POST)
    {
      $form->bind($request->getPostParameters());
      if($form->isValid())
      {
        $user->setPassword($form->getValue('password'));
        $user->setAuthenticated(false);
        $user->setFlash('notice','Password changed');
        return $this->redirect('@homepage');
      }
    }
  }

  public function executeSettings($request)
  {
    $response=$this->getResponse();
  }

  public function executeDump($request)
  {
    if($request->getMethod () != sfRequest::POST)
        return sfView::ERROR;

    $form = new sfForm();
    $form->bind($request->getPostParameters());
    if(!$form->isValid())
        return sfView::ERROR;

    $root=sfContext::getInstance()->getConfiguration()->getRootDir();
    $path=$this->path="$root/data/dumpdata.txt"; // left forever here as a convenience; change this?
    $data=$this->data=new sfPropelData();

    try {
        $data->dumpData($path,Array('setting','podcast','episode','feed','torrent'));
    }
    catch(Exception $e)
    {
        return sfView::ERROR;
    }
    $response=$this->getResponse();
    $response->setContentType('text/yaml');
    $this->setLayout(false);
    return;
  }
  public function executeRestore($request)
  {
    if($request->getMethod () != sfRequest::POST)
        return sfView::ERROR;

    $form = $this->form = new RestoreForm();
    $form->bind($request->getPostParameters(),$request->getFiles());

    if(!$form->isValid())
        return sfView::ERROR;

    $data=$this->data=new sfPropelData();
    try {
        $data->dumpData($form->getValue('file'));
    }
    catch(sfException $e)
    {
        return sfView::ERROR;
    }
    $user->setFlash('notice','Database restored from disk');
  }
}
