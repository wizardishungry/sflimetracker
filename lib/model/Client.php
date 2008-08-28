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
  public function updateWithParameters($params)
  {
    $this->setPeerId($params['peer_id']);
    $this->setIp($params['ip']);
    $this->setClientKey($params['key']);
    $this->setBytesUploaded($params['uploaded']);
    $this->setPort($params['port']);
    $this->setBytesLeft($params['left']);
    $this->setBytesDownloaded($params['downloaded']);
  }

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
