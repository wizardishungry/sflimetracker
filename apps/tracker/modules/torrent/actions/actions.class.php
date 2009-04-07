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
        // fixme throw a proper error
        return;
    }

    $params=$request->getPostParameters();
    $is_replace=false;

    if(isset($params['web_url']))
    {
        if(function_exists("apache_setenv")) // could be running under fastcgi or in non-apache env
            apache_setenv('no-gzip', 1);
        ini_set('zlib.output_compression', 0);
        ini_set('implicit_flush', 1);
        header('Content-type: multipart/x-mixed-replace;boundary="rn9012"');
        $file=new sfValidatedFileFromUrl($params['web_url'],Array($this,'progress'));
        $is_replace=true;
    }

    $this->form->bind($params);
    $obj=$this->form->save();

    if(!$this->form->isValid())
    {
        return sfView::ERROR;
    }

    try {
        $obj->setFile($file,false);
        $obj->save();
    }
    catch(sfException $sfe)
    {
        //todo setflash $sfe
        $this->form->getObject()->delete();
        echo $sfe;exit;
        return sfView::ERROR; 
    }



    if(!$is_replace)
        $this->redirect('episode/edit?id='.$torrent->getEpisodeId());
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
    $this->getUser()->setFlash('notice','Deleted torrent '.$torrent->getFile());
    $torrent->delete();
    $this->redirect('episode/edit?id='.$torrent->getEpisodeId());
  }

    public function progress($o,$done)
    {
        static $time;
        static $calls;
        static $skipped;
        $now = microtime(true);
        @$calls++;
        if(!$done && @$time && $now-$time<.500)
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
