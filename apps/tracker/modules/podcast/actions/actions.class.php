<?php
/**
 * podcast actions.
 *
 * @package    sflimetracker
 * @subpackage podcast
 */
class podcastActions extends sfActions
{

  public function executeAdd($request)
  {
    $this->form=new PodcastForm();
    if ($request->isMethod('post'))
    {
        $this->form->bind($request->getPostParameters(),Array()); // FIXME bind to real files array
        if($this->form->isValid())
        {
            $podcast=$this->form->save();
            $feed=new Feed(); // add a sensible default feed
            $feed->setTags('default');
            $feed->setSlug('default');
            $podcast->addFeed($feed);
            $feed->save();
            $podcast->setDefaultFeed($feed);
            $podcast->save();
            $this->redirect('podcast/view?id='.$podcast->getId());
        }
    }
  }

  public function executeEdit($request)
  {
    $this->form=new PodcastForm(PodcastPeer::retrieveByPk($request->getParameter('id')));
    if ($request->isMethod('post'))
    {
        $this->form->bind($request->getPostParameters(),Array()); // FIXME bind to real files array
        if($this->form->isValid())
        {
            $podcast=$this->form->save();
            $podcast->save();
            $this->redirect('podcast/view?id='.$podcast->getId());
        }
    }
  }

  public function executeView($request)
  {
    $id=$request->getParameter('id');
    $this->podcast=$podcast=PodcastPeer::retrieveByPK($id);
    $this->forward404Unless($this->podcast); 
    $this->form=new PodcastForm($podcast);
    $this->episodes=$this->podcast->getEpisodes();
    $this->feeds=$this->podcast->getFeeds();
  }

  public function executeList()
  {
    $c = new Criteria();
    $c->addAscendingOrderByColumn(PodcastPeer::TITLE);
    $c->addAscendingOrderByColumn(PodcastPeer::UPDATED_AT);
    $c->addAscendingOrderByColumn(PodcastPeer::CREATED_AT);
    $this->podcasts=PodcastPeer::doSelect($c);
  }

  public function executeDelete($request)
  {
    $this->forward404Unless($request->getMethod () == sfRequest::POST); 
    $id=$request->getParameter('id');

    $podcast=PodcastPeer::retrieveByPK($id);
    $this->forward404Unless($podcast); 
    $this->getUser()->setFlash('notice','Deleted podcast '.$podcast->getTitle());
    $podcast->delete();
    $this->redirect('@homepage');
  }
}
