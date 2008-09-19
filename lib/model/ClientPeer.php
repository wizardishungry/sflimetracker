<?php

/**
 * Subclass for performing query and update operations on the 'client' table.
 *
 * 
 *
 * @package lib.model
 */ 
class ClientPeer extends BaseClientPeer
{
  public static function reap($set)
  {
    $items=Array();
    if(is_array($set))
    {
      if(count($set) > 42 ) // fixme magic number
      {
        $items=self::prepareReap($set);
      }
    }
    // test if we should do a global reap 
    $do_global=time()%100==0; // fixme make this sane
    if($do_global)
    {
      $items=array_merge(self::prepareReap($all),$items);
    }
    if(count($items)>999 || $do_global) // fixme magic
    {
      return self::doDelete($items);
    }
    return null;
  }
  
  public static function prepareReap($client)
  {
    $result=Array();
    $counts=Array();
    foreach($clients as $client)
    {
      $id=$client->getTorrentId();
      if(!isset($result[$id]))
      {
        $result[$id]=Array();
        $counts[$id]=0;
      }
      $counts[$id]++;
      if($client->isCruft())
      {
        $result[$id][]=$client->getId();
      }
    }
    $ret=Array();
    foreach($result as $id=>$items)
    {
      if($counts[$id]-count($items)>77) // fixme magic -- making sure there's enough items left over
        $ret=array_merge($ret,$items);
    }
    return $ret;
  }

  public static function retrieveByParameters($params)
  {
    $c = new Criteria();
    
    $c->add(TorrentPeer::INFO_HASH,$params['info_hash']);
    
    $c->add(ClientPeer::PEER_ID,Client::safe_set($params['peer_id']));

    if(isset($params['key']))
      $c->add(ClientPeer::CLIENT_KEY,Client::safe_set($params['key']));
    else
      $c->add(ClientPeer::CLIENT_KEY,null,Criteria::ISNULL);

    if(isset($params['tracker id']))
      $c->add(ClientPeer::TRACKER_ID,Client::safe_set($params['tracker id']));

    $c->addJoin(TorrentPeer::ID,ClientPeer::TORRENT_ID);
    
    $client=self::doSelectOne($c);

    if(!$client)
    {
      $torrent = TorrentPeer::doSelectOne(new Criteria(TorrentPeer::INFO_HASH,$params['info_hash']));
      if($torrent==null)
        throw new sfException('Torrent not found');

      $client = new Client();
      $client->setTorrent($torrent);
      $client->setTrackerId(sha1(uniqid(),true));
    }

    return $client;
  }
}
