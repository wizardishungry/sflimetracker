<?php

/**
 * Subclass for representing a row from the 'podcast' table.
 *
 * 
 *
 * @package lib.model
 */ 
class Podcast extends BasePodcast
{
  public function __toString()
  {
    return $this->getTitle();
  }

  public function delete($con = null)
  {
    $episodes=$this->getEpisodes();
    foreach($episodes as $episode)
    {
        $episode->delete($con);
    }
    $feeds=$this->getFeeds();
    foreach($feeds as $feed)
    {
        $feed->delete($con);
    }
    return parent::delete($con);
  }

  public function getDefaultFeed($con=null)
  {
    // convenience function until I figure out how to have propel make this correctly
    return $this->getFeed($con);
  }

  public function setDefaultFeed($v,$con=null)
  {
    // convenience function until I figure out how to have propel make this correctly
    return $this->setFeed($v,$con);
  }

}

$columns_map = array('from'   => PodcastPeer::TITLE,
                       'to'     => PodcastPeer::SLUG);
 
sfPropelBehavior::add('Podcast', array('sfPropelActAsSluggableBehavior' => array('columns' => $columns_map, 'separator' => '_', 'permanent' => true)));
