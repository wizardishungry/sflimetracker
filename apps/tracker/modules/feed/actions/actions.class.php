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
    $feed = new sfRss201PodcastFeed();

    $link = '@homepage';

    if($request->hasParameter('slug') && $request->hasParameter('podcast_slug'))
    {
      $podcast=CommonBehavior::retrieveBySlug('PodcastPeer',$request->getParameter('podcast_slug'));

      $c = new Criteria();
      $c->add(EpisodePeer::PODCAST_ID,$podcast->getId());
      if(!$podcast)
        $this->forward404();

      $link=$podcast->getLink()?$podcast->getLink():$podcast->getUri();

      $podcast_feed=CommonBehavior::retrieveBySlug('FeedPeer',$request->getParameter('slug'),$c);

    }

    $format=$request->getParameter('format'); // not "content format" but "delivery method enclosure format"

    $feed->initialize(array(
        'title'       => $podcast->getTitle(),
        'link'=>$link,
        'authorEmail' => $podcast->getEmail(),
        'authorName'  => $podcast->getAuthor(),
        'description' => $podcast->getDescription(),
    ));

    $pager=$this->getPager($podcast_feed->getId());
    $pager->init();


    $result_set=$pager->getResults();

    foreach($result_set as $torrent)
    {
        $torrent->setFeedEnclosure($format);
    }

    $torrent_items = sfFeedPeer::convertObjectsToItems($result_set);
    
    foreach($torrent_items as $item)
    {
    }

    $feed->addItems($torrent_items);

    $this->feed = $feed;
        
  }

  protected function getPager($feed_id)
  {
    $pager = new sfPropelPager('Torrent', 20);
    $pager->setPeerMethod('doSelectJoinAll');
    $c = new Criteria();
    $c->addAscendingOrderByColumn(TorrentPeer::UPDATED_AT);
    $c->addAscendingOrderByColumn(TorrentPeer::CREATED_AT);
    // todo exclude future

    if($feed_id)
      $c->add(TorrentPeer::FEED_ID, $feed_id);

    $pager->setCriteria($c);
    $pager->setPage(1);
    return $pager;
  }

}
