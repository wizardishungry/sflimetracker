<?php

// fix for dumb PEAR
// perhaps this should be globalized
set_include_path(get_include_path().PATH_SEPARATOR.sfContext::getInstance()->
    getConfiguration()->getRootDir().DIRECTORY_SEPARATOR.'lib');

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
  public function executeDetails($request)
  {
  }
  public function executeUpload($request)
  {
    $this->form=new TorrentForm();
    if ($request->isMethod('post'))
    {
        $bind=Array('file'=>$request->getParameter('file'));
        if($request->getParameter('podcast_id')!=0)
          $bind['podcast_id']=$request->getParameter('podcast_id');
        $this->form->bind($bind,$request->getFiles());
        if ( $this->form->isBound())
        {
            $torrent=new Torrent($this->form->getValue('file'));
            $torrent->setPodcastId($request->getParameter('podcast_id'));
            $torrent->save();
            $this->redirect('podcast/view?id='.$request->getParameter('podcast_id'));
        } 
        else throw new sfException('Form failed validation');
    }
      $this->form->setDefaults(Array(
            'podcast_id'=>$request->getParameter('podcast_id')
      ),Array());
  }
}
