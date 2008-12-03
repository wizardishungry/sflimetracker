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
}
