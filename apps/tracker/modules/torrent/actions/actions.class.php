<?php
/**
 * torrent actions.
 *
 * @package    sflimetracker
 * @subpackage torrent
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
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
            $torrent->save();
            $this->redirect('episode/view?id='.$request->getParameter('episode_id'));
        } 
        else
          return sfView::ERROR;
    }
      $this->form->setDefaults(Array(
            'episode_id'=>$request->getParameter('episode_id')
      ),Array());
  }
}
