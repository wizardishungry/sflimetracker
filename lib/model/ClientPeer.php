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
  public static function reap($set,$try_to_do_global=true)
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
    if($try_to_do_global)
      $do_global= $try_to_do_global==3 ||time()%100==0; // fixme make this more sane

    if($do_global)
    {
      $criteria = new Criteria();
      $criteria->setLimit(4000); // a fairly large number magic number fixme
      $criteria->addAscendingOrderByColumn(ClientPeer::UPDATED_AT);
      // fixme this should be refined more
      $all = ClientPeer::doSelect($criteria);
      $items=array_merge(self::prepareReap($all),$items);
    }

    if(/*true ||*/ count($items)>999 || $do_global) // fixme magic
    {
      if(count($items)) // just putting this here in case i short circuit the outer if clause 
      {
        self::doDelete($items);
      }
    }


    // todo: deleted items should be removed from set

    return $set;
  }
  
  public static function prepareReap($clients)
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
      $criteria=new Criteria();
      $criteria->add(TorrentPeer::INFO_HASH,$params['info_hash']);
      $torrent = TorrentPeer::doSelectOne($criteria);
      if($torrent==null)
        throw new sfException('Torrent not found');

      $client = new Client();
      $client->setTorrent($torrent);
      $client->setTrackerId(sha1(uniqid(),true));
    }

    return $client;
  }
}
