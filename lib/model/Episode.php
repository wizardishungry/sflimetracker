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
}
$columns_map = array('from'   => EpisodePeer::TITLE,
                       'to'     => EpisodePeer::SLUG);
 
sfPropelBehavior::add('Episode', array('sfPropelActAsSluggableBehavior' => array('columns' => $columns_map, 'separator' => '_', 'permanent' => true)));
