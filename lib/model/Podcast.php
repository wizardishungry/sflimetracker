<?php

/**
 * Subclass for representing a row from the 'podcast' table.
 *
 * 
 *
 * @package lib.model
 */ 
class Podcast extends BasePodcast
{

    protected $has_validation_run;
    protected $browser;
    protected $cache_dir;

    function __construct()
    {
        $this->has_validation_run=!$this->isNew(); // has validation of upstream feed run in current session
        $this->browser=null;
        $this->unserialize();
    }

    public function setFeedUrl($url,$validate=true)
    {
        $old_url=$this->getFeedUrl();
        $old_hash=$this->getFeedUrlHash();
        try {
            parent::setFeedUrl($url);
            if($url)
              $h=sha1($url);
            else
              $h=null;
            $this->setFeedUrlHash($h);
            if($url&&$validate)
            {
                $this->fetch(true,false);
                $this->has_validation_run=true;
            }
        }
        catch(Exception $e)
        {
            if($validate)
            {
                $this->setFeedUrl($old_url,false); // rollback
                $this->setFeedUrlHash($old_hash);
            }
            throw $e;
        }
    }


    public function save($con=null)
    {
        if($this->getFeedUrl() && !$this->has_validation_run)
            throw new sfException('Cannot save podcast with unvalidated feed');

        return parent::save($con);
    }

    public function getLastFetched($format = 'Y-m-d H:i:s')
    {
      if($this->getFeedUrl())
      {
        $this->last_fetched=0+@$this->last_fetched;
        if($file=$this->getStore())
        {
          $this->last_fetched=max($this->last_fetched, @filemtime($file));
        }
      }
      else
      {
        $this->last_fetched=0;
      }
      return parent::getLastFetched($format);
    }

    public function setLastFetched($v)
    {
        parent::setLastFetched($v);
        if($file=$this->getStore())
            touch($file,$v);
    }

    public function fetch($force=false,$fallback=true)
    {
        // force means *must* do request
        // fallback means only throw if we can't unserialize()
        // stale means the cache is stale

        if(!$this->getFeedUrl())
          throw new sfException("Can't fetch() on a local podcast");

        $lifetime = sfConfig::get('app_feed_interval',300);
        $stale=time()-$this->getLastFetched()>$lifetime;

        
        $cached_response=$this->unserialize(); // better unserialization strategy?


        // fixme cleanup
        //echo "force=$force fallback=$fallback stale=$stale cached_response=",$cached_response!=null,"\n";


        if($cached_response && !$force && !$stale)
        {
            return $this->browser2feed($cached_response);
        }

        $b=$this->getBrowser(array(
            'user_agent' => sfConfig::get('app_version_name').' ('. sfConfig::get('app_version_rev'). ' '
              .sfConfig::get('app_version_comment'). ') '. sfConfig::get('app_version_url') , 
            'timeout'    => 15
        )); // refactor this into our own browser wrapper class todo

        $b->get($this->getFeedUrl()); 

        if($b->responseIsError()||!$xml=$this->browser2feed($b))
        {
            $this->setLastFetched(time()); // called because serialize isn't

            if(!$fallback)
            {
                if($b->responseIsError)
                    throw new sfException('request for url returned an error -- '.
                        $b->getResponseCode(). ' - '.$b->getResponseMessage()
                    );
                else
                    throw new sfException('problem parsing feed');
            }
            else
            {   
                /*warn?*/
            }
            return $this->browser2feed($cached_response);
        }

        $this->serialize($b);

        return $xml;
    }

    protected function browser2feed(sfWebBrowser $b)
    {
        if($b==null)
            throw new sfException("Can't make feed from nothing");
        try {
            return sfFeedPeer::createFromXml($b->getResponseText(),$this->getFeedUrl());
        }
        catch(Exception $e) {}
        return null;
    }

    protected function getBrowser($args=array())
    {
        $this->browser = new sfWebBrowser($args);
        return $this->browser;
    }

    protected function serialize($browser)
    {
        if($browser)
        {
            if($file=$this->getStore())
            {
                file_put_contents($file,serialize($browser),LOCK_EX|FILE_TEXT);
            }
        }
        return $browser;
    }

    protected function unserialize()
    {
        if(!$this->browser)
        {
            if($this->getFeedUrl()&&$file=$this->getStore())
            {
                if(file_exists($file) && $contents=file_get_contents($file,FILE_TEXT))
                {
                    if($browser=unserialize($contents))
                        $this->browser=$browser;
                }
            }
        }
        return $this->browser;
    }

    protected function getStorePath()
    {
        if(is_null($this->cache_dir))
        {
            $c=sfContext::getInstance()->getConfiguration();
            $this->cache_dir = $c->getRootDir().DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'tracker'.DIRECTORY_SEPARATOR
            .$c->getEnvironment().DIRECTORY_SEPARATOR.'feed';
        }
        if(!is_dir($this->cache_dir))
        {
            if(!mkdir($this->cache_dir,0777,TRUE)) // 3rd argument makes it recursive as `mkdirhier`
                return false;
        }
        return $this->cache_dir;
    }
    protected function getStore()
    {
        if(!$this->getFeedUrl())
            throw new sfException('non feed-backed podcasts cannot have an store');
        if(!$cache_dir=$this->getStorePath())
            return null;
        return $cache_dir.DIRECTORY_SEPARATOR.$this->getFeedUrlHash();
    }
}
