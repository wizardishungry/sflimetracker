<?php

/**
 * episode actions.
 *
 * @package    sflimetracker
 * @subpackage episode
 */
class episodeActions extends sfActions
{

  public function executeAdd($request)
  {
    $this->form=new EpisodeForm();
    if ($request->isMethod('post'))
    {
        $this->form->bind($request->getPostParameters());
        if($this->form->isValid())
        {
            $episode=$this->form->save();
            // redirect to default feed
            $this->redirect($episode->getUri());
        } 
        else
          return;
    }

    $this->form->setDefaults(Array(
          'podcast_id'=>$request->getParameter('podcast_id'),
    ),Array());
  }

  public function executeEdit($request)
  {
    $this->form=new EpisodeForm(EpisodePeer::retrieveByPk($request->getParameter('id')));
    if ($request->isMethod('post'))
    {
        $this->form->bind($request->getPostParameters());
        if($this->form->isValid())
        {
            $episode=$this->form->save();
            $this->redirect($episode->getUri());
        } 
        else
          return sfView::ERROR;
    }
  }

  public function executeView($request)
  {
    $slug=$request->getParameter('slug');
    $podcast_slug=$request->getParameter('podcast_slug');

    $this->podcast=$podcast=CommonBehavior::retrieveBySlug('PodcastPeer',$podcast_slug);
    $this->forward404Unless($podcast); 

    $c = new Criteria();
    $c->add(EpisodePeer::PODCAST_ID,$podcast->getId());
    $this->episode=$episode=CommonBehavior::retrieveBySlug('EpisodePeer',$slug,$c);
    $this->forward404Unless($episode); 


    $this->torrents=$torrents=$episode->getTorrentsJoinFeed();
    $this->feeds=$feeds=$podcast->getFeeds();

    $has_feeds=Array();
    foreach($torrents as $torrent)
    {
      $has_feeds[]=$torrent->getFeed();
    }
    $this->missing_feeds=array_diff($feeds,$has_feeds);

    $this->form=new EpisodeForm($episode);
  }

  public function executeDelete($request)
  {
    $this->forward404Unless($request->getMethod () == sfRequest::POST); 
    $id=$request->getParameter('id');

    $this->episode=$episode=EpisodePeer::retrieveByPK($id);
    $this->forward404Unless($episode); 
    $this->getUser()->setFlash('notice','Deleted episode'.$episode->getTitle());
    $episode->delete();
    $this->redirect($episode->getPodcast()->getUri());
  }
}
