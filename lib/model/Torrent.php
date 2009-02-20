<?php

/**
 * Subclass for representing a row from the 'torrent' table.
 *
 * 
 *
 * @package lib.model
 */

// todo replace with sfApplicationConfiguration
sfLoader::loadHelpers(Array('Asset','Url'));

class Torrent extends BaseTorrent
{

    function __construct($file=null,$store_original_file=true) // todo -- abstract the validatedfile stuff out
    {
        if($file==null){return;}

        if(! $file instanceof sfValidatedFile) throw new sfException('constructor takes instance of sfValidatedFile');

        $filename = $file->getOriginalName();
        $extension = $file->getOriginalExtension();
        $this->setFileName($filename);

        $this->setSize($file->getSize());
        $this->setMimeType($file->getType());
        $sha1=method_exists($file,'getFileSha1')?$file->getFileSha1():sha1_file($file->getSavedName());
        $this->setFileSha1($sha1);       

        $torrent_file=$this->getTorrentPath();

        if(file_exists($torrent_file))
        {
            $this->cleanupFiles();
            throw new sfException("$torrent_file already exists");
        }

        if($store_original_file)
            $file->save(sfConfig::get('sf_upload_dir').'/'.$filename,0644);

        $MakeTorrent = new File_Bittorrent2_MakeTorrent($file->getSavedName());
        $MakeTorrent->setAnnounce(url_for('client/announce',true));
        $MakeTorrent->setComment('TODO');
        $MakeTorrent->setPieceLength(256); // KiB
        
        $info=Array();
        if($file instanceof sfValidatedFileFromUrl)
        {
            $info['url-list']=Array($file->getUrl());
        }

        $meta = $MakeTorrent->buildTorrent($info);

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

        if($file instanceof sfValidatedFileFromUrl)
        {
            @unlink($file->getSavedName());
            $this->setWebUrl($file->getUrl());
        }
    }

    public function getUrl($torrent=true) // for convenience for now, fixme
    {
        return $this->getUri($torrent);
    }
    public function getUri($torrent=true)
    {
        // todo this needs to be refactored to make Uri and enclosure methods less dependent on call order
        if(isset($this->enclosure))
            return $this->enclosure->getUrl();
        return $this->setFeedEnclosure($torrent?'torrent':'web')->getUrl();
    }

    public function getMagnet()
    {
      // TODO: add web sources to magnets
        return 'magnet:?xt=urn:sha1:'.$this->getFileSha1().
        '&dn='.urlencode($this->getFileName());
    }


    public function getFeedEnclosure()
    {
      if(!isset($this->enclosure))
        return $this->setFeedEnclosure();
      else
        return $this->enclosure;
    }



    public function setFeedEnclosure($enclosure=null)
    {
        if($enclosure instanceof FeedEnclosure)
        {
            $this->enclosure=$enclosure;
            return $enclosure;
        }

        $type=($enclosure==null?'web':$enclosure);

        $params=Array();

        switch($type)
        {
            case 'web':
                $params['url']=_compute_public_path($this->getFileName(),'uploads','',true);
                $params['length']=$this->getSize();
                $params['mimeType']=$this->getMimeType();
                break;
            case 'magnet':
                $params['url']=$this->getMagnet();
                $params['length']=$this->getSize();
                $params['mimeType']=$this->getMimeType();
                break;
            case 'torrent':
                $params['url']=_compute_public_path($this->getFileName().'.torrent','uploads','',true);
                $params['length']=filesize($this->getTorrentPath());
                $params['mimeType']='application/x-bittorrent';
                break;
            default:
                throw new sfException("Unsupported enclosure type $type");
        }

        $this->enclosure =new sfFeedEnclosure();
        return $this->enclosure->initialize($params);

    }

    public function getGuid() //fixme this isn't migration to real feed guid
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
    public function delete(PropelPDO $con = null)
    {
      try {
        $ret=parent::delete($con);
        $this->cleanupFiles();
        if($this->getFileName())
        {
          @unlink($this->getOriginalFilePath());
        }
      }
      catch(Exception $e)
      {
        $this->cleanupFiles(); // make this always happen
        throw $e;
      }
      return $ret;
    }

    public function getClients($criteria= null, PropelPDO $con = null)
    {
      if(! $criteria instanceof Criteria)
      {
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(ClientPeer::BYTES_LEFT);
        $criteria->addAscendingOrderByColumn(ClientPeer::BYTES_UPLOADED);
        $criteria->addDescendingOrderByColumn(ClientPeer::UPDATED_AT);
        // naive
      }
      return parent::getClients($criteria,$con);
    }
    public function reap($try_to_do_global=true)
    {
      return ClientPeer::reap($this->getClients(),$try_to_do_global);
    }

    public function getSize()
    {
        // todo remote files need to have this information cached
        // NB: this will look negative if >= 2**31 use sprintf 
        if($this->getWebUrl()!='')
            return parent::getSize();
        else
            return filesize($this->getOriginalFilePath());
    }

    public function getTitle() // convenience method for sfFeed2Plugin
    {
        return $this->getEpisode()->getTitle();
    }
}
