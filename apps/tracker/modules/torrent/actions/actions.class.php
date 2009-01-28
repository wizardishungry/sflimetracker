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
    $request=$this->getRequest();
    $request->setTimeLimit(0); // ∞
    $request->setIgnoreUserAbort(TRUE);

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

    // is there an upload
    $file=$this->form->getValue('file');

    $is_replace=false;

    if($request->getParameter('web_url')!='') // it is blank but exists
    {
        if($file)
            throw new sfException("Either add by file or by upload not both.");
        @apache_setenv('no-gzip', 1);
        @ini_set('zlib.output_compression', 0);
        @ini_set('implicit_flush', 1);
        header('Content-type: multipart/x-mixed-replace;boundary="rn9012"');
        $file=new sfValidatedFileFromUrl($request->getParameter('web_url'),Array($this,'progress'));
        $is_replace=true;
    }

    $torrent=new Torrent($file); // we should try using the sfForm object we used earlier todo
    $torrent->setEpisodeId($request->getParameter('episode_id'));
    $torrent->setFeedId($request->getParameter('feed_id'));
    if($file instanceof sfValidatedFileFromUrl)
    {
        @unlink($file->getSavedName());
        $torrent->setWebUrl($request->getParameter('web_url'));
    }
    $torrent->save();


    if(!$is_replace)
        $this->redirect($torrent->getEpisode()->getUri());
    else
    {
        exit;
        return sfView::NONE;
    }

   // throw new sfException('Form valdation passed but somehow we do not have anything to do with it');
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

    public function progress($o,$done)
    {
        static $time;
        static $calls;
        static $skipped;
        $now = microtime(true);
        @$calls++;
        if(!$done && isset($time) && $now-$time<.500)
        {
            @$skipped++;
            return;
        }

        $info=$o->getInfo();

        echo "--rn9012\n";
        echo "Content-Type: text/html\n\n";
        echo $this->getPartial('torrent/progress',Array('info'=>$info,'done'=>$done,'skipped'=>$skipped,'calls'=>$calls));
        flush();
        ob_flush();
        $time=$now;
    }
}
