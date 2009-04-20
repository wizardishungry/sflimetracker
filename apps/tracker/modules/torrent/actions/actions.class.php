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
        return sfView::ERROR;
    }

    $params=$request->getPostParameters();
    $this->form->bind($params);

    if(!$this->form->isValid())
    {
        return sfView::ERROR;
    }

    $is_replace=false;

    if(isset($params['web_url']))
    {
        if(function_exists("apache_setenv")) // could be running under fastcgi or in non-apache env
            apache_setenv('no-gzip', 1);
        ini_set('zlib.output_compression', 0);
        $file=new sfValidatedFileFromUrl($params['web_url'],Array($this,'progress'));
        $is_replace=true;
    }

    $obj=$this->form->save();
    try {
        $obj->setFile($file,false);
        $obj->save();
    }
    catch(sfException $sfe)
    {
        //todo setflash $sfe
        $this->form->getObject()->delete();
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
        static $start_time;
        static $calls;
        static $skipped;
        static $last_partial;
        static $path;
        static $route;

        $now = microtime(true);
        @$calls++;

        if(!@$start_time)
        {
            $start_time=$now;
            $root=sfContext::getInstance()->getConfiguration()->getRootDir();
            $path="$root/json-cache/".$this->form->getDefault('_csrf_token').".json";

            $fxn=create_function('',"
                sleep(15); // a few seconds for ajax requests
                unlink('$path');
            ");
            register_shutdown_function($fxn);
        }

        $interval = $start_time-$now>60?3.0:.5; // slower updates after 60 seconds

        if(!$done && @$time && $now-$time<$interval)
        {
            @$skipped++;
            return;
        }

        $info=$o->getInfo();

        if($info['download_content_length'])
            $percent=$info['size_download']/$info['download_content_length'] *100.0;
            else
            $percent=0;
        $percent=round($percent,2); // 2 significant digits -- ie "31.33%"

        $data = "{percent: '".$percent."', finished: '".$info['size_download']."', total: '".$info['download_content_length']."'}";

        $fp = fopen($path, "w");
        fwrite($fp, $data);
        fclose($fp);

        $time=$now;
    }
}
