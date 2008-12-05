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

    if($request->hasParameter('id'))
    {
      $id=$request->getParameter('id');
      $podcast_feed=FeedPeer::retrieveByPK($id);
      if(!$podcast_feed)
        $this->forward404();
      $link='feed/view?id='.$id;
    }

    $feed->initialize(array(
        'title'       => $podcast_feed->getPodcast()->getTitle(),
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
}
