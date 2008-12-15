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
        $params=$request->getPostParameters();
        $files=$request->getFiles();
        if(isset($files['file']))
          $params['file']=$files['file'];
        $this->form->bind($request->getPostParameters(),$request->getFiles());
        if ( $this->form->isValid())
        {
            $torrent=new Torrent($this->form->getValue('file'));
            $torrent->setEpisodeId($request->getParameter('episode_id'));
            $torrent->setFeedId($request->getParameter('feed_id'));
            $torrent->save();
            $this->redirect('episode/view?id='.$request->getParameter('episode_id'));
        } 
        else
          return sfView::ERROR;
    }
      $this->form->setDefaults(Array(
            'episode_id'=>$request->getParameter('episode_id'),
            'feed_id'=>$request->getParameter('feed_id')
      ),Array());
  }
  public function executeDelete($request)
  {
    $id=$request->getParameter('id');

    $torrent=TorrentPeer::retrieveByPK($id);
    $this->forward404Unless($torrent); 
    $this->getUser()->setFlash('notice','Deleted torrent '.$torrent->getFileName());
    $torrent->delete();
    $this->redirect('episode/view?id='.$torrent->getEpisodeId());
  }
}
