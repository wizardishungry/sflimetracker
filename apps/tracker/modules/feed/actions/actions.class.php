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

    $format=$request->getParameter('format'); // not "content format" but "delivery method enclosure format"

    $feed->initialize(array(
        'title'       => $podcast->getTitle().
            ($podcast_feed->getTags()=='default'?'':'['.$podcast_feed->getTags().']').
            ($format=='web'?'':" - via $format"),
            'link'        => $link,
//        'authorEmail' => 'herman@example.com',
//        'authorName'  => 'T. Herman Zweibel'
    ));

    $pager=$this->getPager($request);
    $pager->init();


    $result_set=$pager->getResults();

    foreach($result_set as $torrent)
    {
        $torrent->setFeedEnclosure($format);
    }

    $torrent_items = sfFeedPeer::convertObjectsToItems($result_set);
    $feed->addItems($torrent_items);

    $this->feed = $feed;
        
  }

  protected function getPager($request)
  {
    $pager = new sfPropelPager('Torrent', 20);
    $pager->setPeerMethod('doSelectJoinAll');
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
