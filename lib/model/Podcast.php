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
