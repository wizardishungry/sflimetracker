<?php

/**
 * Subclass for representing a row from the 'torrent' table.
 *
 * 
 *
 * @package lib.model
 */

sfApplicationConfiguration::loadHelpers(Array('Asset','Url'));

class Torrent extends BaseTorrent
{

    function setFile($file,$store_original_file=true) // todo -- abstract the validatedfile stuff out
    {
        if(! $file  instanceof sfValidatedFile)
        {
            if(is_string($file))
                return parent::setFile($file);
        }
        $filename = $this->formatFilename($file->getOriginalName()).$file->getOriginalExtension();
        $extension = $file->getOriginalExtension();
        parent::setFile($filename);
        // fixme this isn't setting the whole path

        $this->setSize($file->getSize());
        $this->setMimeType($file->getType());
        $sha1=method_exists($file,'getFileSha1')?$file->getFileSha1():sha1_file($file->getTempName());
        $this->setFileSha1($sha1);       

        $torrent_file=$this->getTorrentPath();

        if(file_exists($torrent_file))
        {
            $this->cleanupFiles();
            throw new limeException('torrent',"$torrent_file already exists");
            parent::setFile(null);
        }

        if($store_original_file)
        {
            $file->save($this->getOriginalFilePath(),0644);
        }
        else
        {
            
        }
        // NB:  formatFilename should probably put the real filename on the file
        //      but we've factored this out

        
        $MakeTorrent = new File_Bittorrent2_MakeTorrent($file->isSaved()?$file->getSavedName():$file->getTempName());
        $MakeTorrent->setAnnounce(url_for('client/announce',true));
        $MakeTorrent->setComment('TODO');
        $MakeTorrent->setPieceLength(256); // KiBw
        
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
                throw new limeException('torrent',"$torrent_file not written");
            }
        }
        else
        {
            $this->cleanupFiles();
            throw new limeException('torrent','Unable to generate torrent');
        }

        if($file instanceof sfValidatedFileFromUrl)
        {
            $this->setWebUrl($file->getUrl());
        }
        else
        {
            $this->setWebUrl(_compute_public_path($this->getFile(),'uploads','',true));
        }
    }

    public function getUrl($torrent=null) // for convenience for now, fixme
    {
        return $this->getUri($torrent);
    }
    public function getUri($torrent=null)
    {
        // todo this needs to be refactored to make Uri and enclosure methods less dependent on call order
        if($torrent==null && isset($this->enclosure))
            return $this->enclosure->getUrl();
        return $this->setFeedEnclosure($torrent?'torrent':'web')->getUrl();
    }

    public function getMagnet()
    {
        return 'magnet:?xt=urn:sha1:'.$this->getFileSha1().
        '&dn='.urlencode($this->getFile()).
        '&as='.urlencode($this->getWebUrl()).
        '&xl='.$this->getSize();
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
                $params['url']=$this->getWebUrl();
                // fixme this should return webloc not local path REGRESSION
                $params['length']=$this->getSize();
                $params['mimeType']=$this->getMimeType();
                break;
            case 'magnet':
                $params['url']=$this->getMagnet();
                $params['length']=$this->getSize();
                $params['mimeType']=$this->getMimeType();
                break;
            case 'torrent':
                $params['url']=_compute_public_path($this->getFile().'.torrent','uploads','',true);
                $params['length']=filesize($this->getTorrentPath());
                $params['mimeType']='application/x-bittorrent';
                break;
            default:
                throw new limeException('enclosure',"Unsupported enclosure type $type");
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
      return sfConfig::get('sf_upload_dir').'/'.$this->getFile();
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
      if($this->getFile())
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
      }
      catch(Exception $e)
      {
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
            return $this->getOriginalFilePath()?filesize($this->getOriginalFilePath()):0;
    }

    public function getTitle() // convenience method for sfFeed2Plugin
    {
        return $this->getEpisodeId()?$this->getEpisode()->getTitle():'';
    }


    public function getDescription() // convenience method for sfFeed2Plugin
    {
        return $this->getEpisodeId()?$this->getEpisode()->getDescription():'';
    }


    public function getPodcast()
    {
        if($this->getEpisodeId())
            return $this->getEpisode()->getPodcast();
        else
            return null;
    }

    public function formatFileName($filename,$fmt_string=null)
    {
        if($fmt_string!=null)
        {
            throw new limeException('not-implemented',"Format strings are not implemented, thus overloading the format is neither");
        }

        $parts=Array();

        if($podcast=$this->getPodcast())
            $parts[]=$podcast->getTitle();

        if($episode=$this->getEpisode())
            $parts[]=$episode->getTitle();

        if($feed=$this->getFeed())
            $parts[]=$feed->getTitle();

        if(!count($parts))
            $parts[] = $filename;

        return strtr(implode('-',$parts),' /','__');
    }

}
