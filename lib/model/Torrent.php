<?php

/**
 * Subclass for representing a row from the 'torrent' table.
 *
 * 
 *
 * @package lib.model
 */

sfLoader::loadHelpers(Array('Asset','Url'));

class Torrent extends BaseTorrent
{

    function __construct($file=null)
    {
        if(!$file)
        {
          return;
        }

        $filename = $file->getOriginalName();
        $extension = $file->getOriginalExtension();
        $this->setTitle($filename);
        $this->setFileName($filename);
        $file->save(sfConfig::get('sf_upload_dir').'/'.$filename);
                    
        $torrent_file=$this->getTorrentPath();

        if(file_exists($torrent_file))
        {
            $this->cleanupFiles();
            throw new sfException("$torrent_file already exists");
        }

        $this->setSize(filesize($file->getSavedName()));

        /*$fi = new finfo(FILEINFO_MIME);
        $mime_type = $fi->file($file->getSavedName());
        $fi->close();*/
        //$mime_type=mime_content_type($file->getSavedName());
        $mime_content_type='text/plain'; // just to have something fixme
        $this->setMimeType($mime_type);
        

        $MakeTorrent = new File_Bittorrent2_MakeTorrent($file->getSavedName());
        $MakeTorrent->setAnnounce(url_for('client/announce',true));
        $MakeTorrent->setComment('TODO');
        $MakeTorrent->setPieceLength(256); // KiB
        $meta = $MakeTorrent->buildTorrent();
        if($meta && file_put_contents($torrent_file,$meta))
        {
            $File_Bittorrent2_Decode = new File_Bittorrent2_Decode();
            $info=$File_Bittorrent2_Decode->decodeFile($torrent_file);

            $this->setInfoHash($info['info_hash']);
            if(!file_exists($torrent_file))
            {
                $this->cleanupFiles();
                throw new sfException("$torrent_file not written");
            }
        }
        else
        {
            $this->cleanupFiles();
            throw new sfException('Unable to generate torrent');
        }
    }

    public function getUrl()
    {
        return _compute_public_path($this->getFileName().'.torrent','uploads','',true);
    }


    public function getFeedEnclosure()
    {
      $e=new sfFeedEnclosure();
      return $e->initialize(Array(
            'url'=>$this->getUrl(),
            'length'=>filesize($this->getTorrentPath()),
            'mimeType'=>'application/x-bittorrent',
        ));
    }

    public function getGuid()
    {
        $components = Array();
        if($podcast=$this->getPodcast())
        {
            $components[]=$podcast->getGuid();
            // insert guid from feed here
        }
        else
        {
            $components[]=$this->getInfoHash();
        }
        return implode('#',$components);
    }

    public function getTorrentPath()
    {
      return $this->getOriginalFilePath().".torrent";
    }

    public function getOriginalFilePath()
    {
      return sfConfig::get('sf_upload_dir').'/'.$this->getFileName();
    }

    public function __destruct()
    {
      try {
        if($this->isNew())
        {
          $this->cleanupFiles();
        }
      }
      catch(Exception $e){}

    }
    protected function cleanupFiles()
    {
      if($this->getFileName())
      {
        @unlink($this->getTorrentPath());
        @unlink($this->getOriginalFilePath());
      }
    }
    public function delete($con = null)
    {
      try {
        $ret=parent::delete($con);
        $this->cleanupFiles();
      }
      catch(Exception $e)
      {
        $this->cleanupFiles(); // make this always happen
        throw $e;
      }
      return $ret;
    }
    public function save($con = null)
    {
      try {
        $ret=parent::save($con);
        if($this->getFileName())
        {
          @unlink($this->getOriginalFilePath());
        }
      }
      catch(Exception $e)
      {
        throw $e;
      }
      return $ret;
    }
}
