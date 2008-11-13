<?php
/**
 * torrent actions.
 *
 * @package    sflimetracker
 * @subpackage torrent
 */
class torrentActions extends sfActions
{
  public function executeReap($request)
  {
    $id=$request->getParameter('id');
    $torrent=TorrentPeer::retrieveByPK($id);
    $torrent->reap(3);
  }
  public function executeDetails($request)
  {
  }
  public function executeUpload($request)
  {
    $this->form=new TorrentForm();
    if ($request->isMethod('post'))
    {
        $this->form->bind($request->getPostParameters(),$request->getFiles());
        if ( $this->form->isValid())
        {
            $torrent=new Torrent($this->form->getValue('file'));
            $torrent->setPodcastId($request->getParameter('podcast_id'));
            $torrent->save();
            $this->redirect('podcast/view?id='.$request->getParameter('podcast_id'));
        } 
        else
          return sfView::ERROR;
    }
      $this->form->setDefaults(Array(
            'podcast_id'=>$request->getParameter('podcast_id')
      ),Array());
  }
}
