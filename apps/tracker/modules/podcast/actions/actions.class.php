<?php
/**
 * podcast actions.
 *
 * @package    sflimetracker
 * @subpackage podcast
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class podcastActions extends sfActions
{
 /**
  * Executes add action
  *
  * @param sfRequest $request A request object
  */
  public function executeAdd($request)
  {
    $this->form=new PodcastForm();
    if ($request->isMethod('post'))
    {
        $this->form->bind($request->getGetParameters());
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
    $this->torrents=$this->podcast->getTorrents();
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
