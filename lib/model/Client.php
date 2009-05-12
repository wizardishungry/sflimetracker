<?php

/**
 * Subclass for representing a row from the 'client' table.
 *
 * 
 *
 * @package lib.model
 */ 
class Client extends BaseClient
{

  const PACK_METHOD='H*';

  public function getDict($binary=false)
  {
    if($binary) // only works with ipv4 I think
    {
      return pack('Nn',ip2long($this->getIp()),$this->getPort());
    }
    return Array(
      'peer id'=>$this->getPeerId(),
      'ip'=>$this->getIp(),
      'port'=>$this->getPort(),
    );
  }
  public function updateWithParameters($params,$request)
  {
    $this->setPeerId($params['peer_id']);
    $this->setClientKey($params['key']);
    $this->setBytesUploaded($params['uploaded']);
    $this->setPort($params['port']);
    $this->setBytesLeft($params['left']);
    $this->setBytesDownloaded($params['downloaded']);
    $torrent=$this->getTorrent(); // make sure this is joined in initial query

    if(isset($params['ip']))
      $this->setIp($params['ip']);
    if(!isset($params['ip']) ||$this->isNatIp())
    {
      $this->setIp($request->getRemoteAddress());
    }

    if(isset($params['event']))
    {
      switch($params['event'])
      {
        case 'started':
            if($this->getBytesLeft()==0) $torrent->setSeeders($torrent->getSeeders()+1);
            $torrent->setPeers($torrent->getPeers()+1);
            // may not be as atomic as we like
          break;
        case 'stopped':
            if($this->getBytesLeft()==0) $torrent->setSeeders($torrent->getSeeders()-1);
            $torrent->setPeers($torrent->getPeers()-1);
            $this->delete();
          break;
        case 'completed':
            $torrent->setSeeders($torrent->getSeeders()+1);
            $torrent->setDownloads($torrent->getDownloads()+1);
          break;
        default:
          throw new limeException('torrent-protocol','Unknown event, '.$params['event']);
      }
    }
  }

  public function isCruft()
  {
    if(time()-$this->getUpdatedAt(null)>=sfConfig::get('app_client_age_max'))
      return true;
    // fixme more?
    return false;
  }

  public function isNatIp()
  {
    $nat_masks=Array('192.168.0.0/16', '172.16.0.0/12','10.0.0.0/8');
    $ip_long=ip2long($this->getIp());
    
    if($ip_long===FALSE)
    {
      return FALSE; // this is either an ipv6 address or a host name (we hope!)
    }
    

    foreach($nat_masks as $mask)
    {
      $ip_arr = explode('/', $mask);
      $network_long = ip2long($ip_arr[0]);
      $x = ip2long($ip_arr[1]);
      $mask =  long2ip($x) == $ip_arr[1] ? $x : 0xffffffff << (32 - $ip_arr[1]);
      if(($ip_long & $mask) == ($network_long & $mask))
        return TRUE;
    }
    return false;
  }

  public function isComplete()
  {
    return $this->getBytesLeft()==0;
  }

/*
  ##############################################################################
        really boring stuff goes below
  ##############################################################################
*/

  public static function safe_set($str)
  {
    $a = unpack(self::PACK_METHOD, $str);
    if(isset($a[1]))
      return $a[1];
    else
      return null;
  }

  public static function safe_get($str)
  {
    return pack(self::PACK_METHOD, $str);
  }

  public function getClientKey()
  {
    return self::safe_get(parent::getClientKey());
  }
  public function setClientKey($v)
  {
    return parent::setClientKey(self::safe_set($v));
  }


  public function getTrackerId()
  {
    return self::safe_get(parent::getTrackerId());
  }
  public function setTrackerId($v)
  {
    return parent::setTrackerId(self::safe_set($v));
  }

  public function getPeerId()
  {
    return self::safe_get(parent::getPeerId());
  }
  public function setPeerId($v)
  {
    return parent::setPeerId(self::safe_set($v));
  }
}
