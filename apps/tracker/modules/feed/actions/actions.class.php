<?php

/**
 * feed actions.
 *
 * @package    sflimetracker
 * @subpackage feed
 */
class feedActions extends sfActions
{

  public function executeFeed($request)
  {
    $feed = new sfRss201Feed();

    $link = '@homepage';

    if($request->hasParameter('slug') && $request->hasParameter('podcast_slug'))
    {
      $podcast=CommonBehavior::retrieveBySlug('PodcastPeer',$request->getParameter('podcast_slug'));

      $c = new Criteria();
      $c->add(EpisodePeer::PODCAST_ID,$podcast->getId());

      $podcast_feed=CommonBehavior::retrieveBySlug('FeedPeer',$request->getParameter('slug'),$c);

      if(!$podcast_feed)
        $this->forward404();

      $link=$podcast->getUri();
    }

    $feed->initialize(array(
        'title'       => $podcast->getTitle(),
        'link'        => $link,
//        'authorEmail' => 'herman@example.com',
//        'authorName'  => 'T. Herman Zweibel'
    ));

    $pager=$this->getPager($request);
    $pager->init();


    $torrent_items = sfFeedPeer::convertObjectsToItems($pager->getResults(),Array(
        
        
    ));
    $feed->addItems($torrent_items);

    $this->feed = $feed;
        
  }

  protected function getPager($request)
  {
    $pager = new sfPropelPager('Torrent', 20);
    $c = new Criteria();
    $c->addAscendingOrderByColumn(TorrentPeer::UPDATED_AT);
    $c->addAscendingOrderByColumn(TorrentPeer::CREATED_AT);

    if($request->hasParameter('id'))
      $c->add(TorrentPeer::FEED_ID, $request->getParameter('id'));

    $pager->setCriteria($c);
    $pager->setPage(1);
    return $pager;
  }

  public function executeDelete($request)
  {
    $this->forward404Unless($request->getMethod () == sfRequest::POST);   
    $id=$request->getParameter('id');

    $feed=FeedPeer::retrieveByPK($id);
    $this->forward404Unless($feed); 
    $this->getUser()->setFlash('notice','Deleted feed '.$feed->getTitle());
    $feed->delete();
    $this->redirect($feed->getPodcast()->getUri());
  } 
}
