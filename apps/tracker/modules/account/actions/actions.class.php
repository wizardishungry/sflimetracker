<?php

/**
 * account actions.
 *
 * @package    sflimetracker
 * @subpackage account
 */
class accountActions extends sfActions
{


  public function executeError404($request)
  {
  }


  public function executeDbinfo($request)
  {
  }

  public function executeFirstrun($request)
  {
    if($this->getUser()->isAuthenticated())
      $this->getUser()->setAuthenticated(false);

    
    if($this->getUser()->getCryptedString())
        $this->redirect('@homepage');
    

    $form = $this->form = new FirstRunForm();
    if($request->getMethod () == sfRequest::POST)
    {
        $params=$request->getPostParameters();

        $form->bind($params);
        
        if($form->isValid())
        {
            $this->getUser()->setPassword($form->getValue('password'));
            return $this->performLogin($request,false);
        }
        else
        {
            return sfView::ERROR;
        }
    }
  }

  public function executeLogin($request)
  {
    
    $user = $this->getUser();
    $form = $this->form = new LoginForm($user);

    if($user->getCryptedString()==null)
        $this->redirect('@first_run');

    if($user->isAuthenticated())
      $this->redirect('@homepage');

    if($request->getMethod () == sfRequest::POST)
    {
      $params=$request->getPostParameters();

      $form->bind($params);
      
      if($form->isValid())
      {
        return $this->performLogin($request);
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
        return $this->redirect('account/settings');

    $form = $this->form = new RestoreForm();
    $form->bind($request->getPostParameters(),$request->getFiles());

    if(!$form->isValid())
        return $this->redirect('account/settings');

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

  public function executeBlam($request)
  {
    if($request->getMethod () != sfRequest::POST)
        return;

    $form = $this->form = new sfForm();
    $form->bind($request->getPostParameters());

    if(!$form->isValid())
        return sfView::ERROR;


    $data=$this->data=new sfPropelData();
    try {
        $root=sfContext::getInstance()->getConfiguration()->getRootDir();
        $data->setDeleteCurrentData(true);
        $data->loadData($root.'/data/fixtures'); // delete all data from tables mentioned in the yamls
        sfToolkit::clearDirectory($root.'uploads');
    }
    catch(sfException $e)
    {
        return sfView::ERROR;
    }
    $user=$this->getUser();
    $user->setFlash('notice','Database has been wiped and torrents deleted.');
    $user->remember(true); // nom nom nom cookies all gone
    $user->setAuthenticated(false);
    $this->redirect('@root');
  }

  public function executeTracker($request)
  {
    if($request->getMethod () != sfRequest::POST)
        return;

    $form = $this->form = new TrackerForm();
    $form->bind($request->getPostParameters());
    $user=$this->getUser();

    if(!$form->isValid())
        return sfView::ERROR;


    $active=$form->getValue('active');
    SettingPeer::setByKey('tracker_active',$active);

    $user->setFlash('notice','BitTorrent tracker '.($active?'on':'off'));
    $this->redirect('@settings');
  }

    public function performLogin($request,$allow_ref_redir=true)
    {
        $form=$this->form;
        $user=$this->getUser();
        $user->setAuthenticated(true);

        if($form->getValue('remember_me'))
        {
            $user->remember();
        }

        if($request->getReferer() && $allow_ref_redir)
        {
            $this->redirect($request->getReferer());
        }
        else
        {
            $this->redirect('@homepage');
        }
    }
}
