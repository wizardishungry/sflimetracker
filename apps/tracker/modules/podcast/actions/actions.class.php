<?php
/**
 * podcast actions.
 *
 * @package    sflimetracker
 * @subpackage podcast
 */
class podcastActions extends sfActions
{

  public function executeAdd($request)
  {
    $this->form=new PodcastForm();
    if ($request->isMethod('post'))
    {
        $this->form->bind($request->getPostParameters());
        if($this->form->isValid())
        {
            $podcast=$this->form->save();
            //$podcast->fetch();
            $this->redirect('podcast/view?id='.$podcast->getId());
        }
    }
  }
  public function executeView($request)
  {
    $id=$request->getParameter('id');
    $this->podcast=PodcastPeer::retrieveByPK($id);
    $this->forward404Unless($this->podcast); 
    $this->episodes=$this->podcast->getEpisodes();
  }

  public function executeList()
  {
    $c = new Criteria();
    $c->addAscendingOrderByColumn(PodcastPeer::TITLE);
    $c->addAscendingOrderByColumn(PodcastPeer::UPDATED_AT);
    $c->addAscendingOrderByColumn(PodcastPeer::CREATED_AT);
    $this->podcasts=PodcastPeer::doSelect($c);
  }
}
