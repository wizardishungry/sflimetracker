<?php

/**
 * podcast_feed actions.
 *
 * @package    sflimetracker
 * @subpackage podcast_feed
 */
class podcast_feedActions extends sfActions
{
  public function executeAdd($request)
  {
    
    return $this->executeEdit($request);
  }
  public function executeEdit($request)
  {
    $form=$this->form=new FeedForm(FeedPeer::retrieveByPk($request->getParameter('id')));
    $podcast_feed=$this->podcast_feed=$form->getObject();
    $podcast=$this->podcast=$podcast_feed->getPodcast();
    if ($request->isMethod('post'))
    {
        $form->bindAndSave($request->getPostParameters(),$request->getFiles());
        if($this->form->isValid())
        {
            $podcast_feed=$this->form->save();
            //$this->redirect('podcast/edit?id='.$podcast_feed->getPodcastId());
            $this->redirect($this->getRequest()->getReferer());
        }
        else
            return sfView::ERROR;
    }
  }


  public function executeDelete($request)
  {
    $this->forward404Unless($request->getMethod () == sfRequest::POST);   
    $id=$request->getParameter('id');

    $podcast_feed=FeedPeer::retrieveByPK($id);
    $this->forward404Unless($podcast_feed); 
    $this->getUser()->setFlash('notice','Deleted feed '.$podcast_feed->getTitle());
    $podcast_feed->delete();
    $this->redirect('podcast/edit?id='.$podcast_feed->getPodcastId());
  } 
}
