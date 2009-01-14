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

  public function executeAdd($request)
  {
    $this->form=new TorrentForm();

    // setup defaults
    $this->form->setDefaults(Array(
        'episode_id'=>$request->getParameter('episode_id'),
        'feed_id'=>$request->getParameter('feed_id')
    ),Array());

    if(!$request->isMethod('post'))
    {
        // Should be a POST;
        return;
    }

    $this->form->bind($request->getPostParameters(),$request->getFiles());
    if(!$this->form->isValid())
    {
        return sfView::ERROR;
    }
    // is this an upload
    $files=$request->getFiles();
    if(isset($files['file']))
        return $this->doUpload($request);

    if($request->hasParameter('web_url'))
        return $this->doAddByUrl($request);

    throw new sfException('Form valdation passed but somehow we do not have anything to do with it');
  }

  protected function doAddByUrl($request)
  {
    throw new sfException('NOT IMPLEMENTED');
  }

  protected function doUpload($request)
  {
    $torrent=new Torrent($this->form->getValue('file'));
    $torrent->setEpisodeId($request->getParameter('episode_id'));
    $torrent->setFeedId($request->getParameter('feed_id'));
    $torrent->save();
    $this->redirect($torrent->getEpisode()->getUri());
  }

  public function executeDelete($request)
  {
    $this->forward404Unless($request->getMethod () == sfRequest::POST); 
    $id=$request->getParameter('id');

    $torrent=TorrentPeer::retrieveByPK($id);
    $this->forward404Unless($torrent); 
    $this->getUser()->setFlash('notice','Deleted torrent '.$torrent->getFileName());
    $torrent->delete();
    $this->redirect($torrent->getEpisode()->getUri());
  }
}
