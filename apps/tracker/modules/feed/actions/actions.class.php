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

    $feed->initialize(array(
        'title'       => 'sfLimeTracker says to put a title here',
        'link'        => '@homepage',
        'authorEmail' => 'jwilliams@limewire.com',
        'authorName'  => 'Jon Williams'
    ));

    $pager=$this->getPager();
    $pager->init();


    $torrent_items = sfFeedPeer::convertObjectsToItems($pager->getResults(),Array(
        
        
    ));
    $feed->addItems($torrent_items);

    $this->feed = $feed;
        
  }

  protected function getPager()
  {
    $pager = new sfPropelPager('Torrent', 20);
    $c = new Criteria();
    $c->addAscendingOrderByColumn(TorrentPeer::UPDATED_AT);
    $c->addAscendingOrderByColumn(TorrentPeer::CREATED_AT);
    $pager->setCriteria($c);
    $pager->setPage(1);
    return $pager;
  }
}
