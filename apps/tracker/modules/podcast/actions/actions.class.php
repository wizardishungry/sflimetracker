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
    return $this->executeEdit($request,true);
  }

  public function executeEdit($request,$new=false)
  {
    $this->form=new PodcastForm($new?null:PodcastPeer::retrieveByPk($request->getParameter('id')));
    $this->podcast=$this->form->getObject();
    $this->episodes=$this->podcast->getEpisodes();
    $this->feeds=$this->podcast->getFeeds();
    $this->podcast_feed_form=new FeedForm();
    $this->podcast_feed_form->setDefaults(Array(
        'podcast_id'=>$this->podcast->getId(),
    ),Array());

    if($request->isMethod('post'))
    {
        $this->form->bind($request->getPostParameters(),Array()); // FIXME bind to real files array
        if($this->form->isValid())
        {
            $podcast=$this->form->save();
            if($new)
            {
                $feed=new Feed(); // add a sensible default feed
                $feed->setTags('default');
                $feed->setSlug('default');
                $podcast->addFeed($feed);
                $feed->save();
                $podcast->setDefaultFeed($feed);
                $podcast->save();
            }
            $this->redirect($podcast->getUri());
        }
    }
  }

  public function executeView($request)
  {
    $this->podcast=$podcast=CommonBehavior::retrieveBySlug('PodcastPeer',$request->getParameter('slug'));
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
