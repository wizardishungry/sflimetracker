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
    $feed = new sfRssDomFeed();

    $link = '@homepage';

    if($request->hasParameter('slug') && $request->hasParameter('podcast_slug'))
    {
      $podcast=CommonBehavior::retrieveBySlug('PodcastPeer',$request->getParameter('podcast_slug'));

      $c = new Criteria();
      if(!$podcast)
        $this->forward404();

      $link=$podcast->getLink()?$podcast->getLink():$podcast->getUri();

      $podcast_feed=CommonBehavior::retrieveBySlug('FeedPeer',$request->getParameter('slug'),$c);

    }

    $format=$request->getParameter('format'); // not "content format" but "delivery method enclosure format"

    $feed->initialize(array(
        'title'       => $podcast->getTitle(),
        'link'        => $link,
        'generator'   => sfConfig::get('app_version_url'),
        'authorEmail' => $podcast->getEmail(),
        'authorName'  => $podcast->getAuthor(),
        'description' => $podcast->getDescription(),
        'image'       => new sfFeedImage(array(
            "image"  => $podcast->getImageUrl(),
            //"imageX" => (int)$feedXml->channel[0]->image->width,
            //"imageY" => (int)$feedXml->channel[0]->image->height,
            //"link"   => (string)$feedXml->channel[0]->image->link,
            //"title"  => (string)$feedXml->channel[0]->image->title
        )),
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
