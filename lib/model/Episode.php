<?php

/**
 * Subclass for representing a row from the 'episode' table.
 *
 * 
 *
 * @package lib.model
 */ 
class Episode extends BaseEpisode
{
  public function __toString()
  {
    return $this->getTitle();
  }

  public function getUri()
  {
    return '@episode?slug='.$this->getSlug().'&podcast_slug='.$this->getPodcast()->getSlug();
  }

  public function getFormattedLength() {
    $hours = (int) ($this->getLength() / 3600);
    $minutes = (int) ($this->getLength() % 3600 / 60);
    $seconds = (int) ($this->getLength() % 60);

    if($seconds < 10)
      $seconds = "0".$seconds;

    if($hours > 0) {
      if($minutes < 10)
        $minutes = "0".$minutes;
      return $hours.":".$minutes.":".$seconds;
    } else {
      return $minutes.":".$seconds;
    }
  }

  public function delete(PropelPDO $con = null)
  {
    $torrents=$this->getTorrents();
    foreach($torrents as $torrent)
    {
        $torrent->delete($con);
    }
    return parent::delete($con);
  }
}
$columns_map = array('from'   => EpisodePeer::TITLE,
                       'to'     => EpisodePeer::SLUG);
 
sfPropelBehavior::add('Episode', array('sfPropelActAsSluggableBehavior' => array('scope' => Array(EpisodePeer::PODCAST_ID), 'columns' => $columns_map, 'separator' => '_', 'permanent' => true)));
