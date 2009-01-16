<?php

/**
 * podcast_feed actions.
 *
 * @package    sflimetracker
 * @subpackage podcast_feed
 */
class podcast_feedActions extends sfActions
{
  public function executeAdd($request)
  {
    $this->form=new FeedForm();
    if ($request->isMethod('post'))
    {
        $this->form->bind($request->getPostParameters(),$request->getFiles());
        if($this->form->isValid())
        {
            $feed=$this->form->save();
            $this->redirect($feed->getPodcast()->getUri());
        }
    }
  }
}
