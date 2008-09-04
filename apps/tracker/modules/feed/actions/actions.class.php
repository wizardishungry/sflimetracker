<?php

/**
 * feed actions.
 *
 * @package    sflimetracker
 * @subpackage feed
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class feedActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeFeed($request)
  {
    $feed = new sfRss201Feed();

    $title = 'sfLimeTracker says to put a title here fixme';
    $link = '@homepage';

    if($request->hasParameter('id'))
    {
      $id=$request->getParameter('id');
      $podcast=PodcastPeer::retrieveByPK($id);
      if(!$podcast)
        $this->forward404();
      $title=$podcast->getTitle();
      $link='podcast/view?id='.$id;
    }

    $feed->initialize(array(
        'title'       => $title,
        'link'        => $link,
        'authorEmail' => 'herman@example.com',
        'authorName'  => 'T. Herman Zweibel'
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
      $c->add(TorrentPeer::PODCAST_ID, $request->getParameter('id'));

    $pager->setCriteria($c);
    $pager->setPage(1);
    return $pager;
  }
}
