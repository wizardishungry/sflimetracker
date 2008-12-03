<?php

/**
 * episode actions.
 *
 * @package    sflimetracker
 * @subpackage episode
 */
class episodeActions extends sfActions
{

  public function executeAdd($request)
  {
    $this->form=new EpisodeForm();
    if ($request->isMethod('post'))
    {
        $this->form->bind($request->getPostParameters());
        if($this->form->isValid())
        {
            $episode=$this->form->save();
            $this->redirect('episode/view?id='.$episode->getId());
        } 
        else
          return sfView::ERROR;
    }

    $this->form->setDefaults(Array(
          'podcast_id'=>$request->getParameter('podcast_id')
    ),Array());
  }

  public function executeView($request)
  {
    $id=$request->getParameter('id');
    $this->episode=EpisodePeer::retrieveByPK($id);
    $this->forward404Unless($this->episode); 
    $this->torrents=$this->episode->getTorrents();
  }
}
