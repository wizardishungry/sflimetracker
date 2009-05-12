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
  public static function reap($set,$try_to_do_multi=true)
  {
    $items=Array();

    if(is_array($set))
    {
      if(count($set) >= sfConfig::get('app_reap_single_clients_min') ) 
      {
        $items=self::prepareReap($set);
      }
    }
    
    // test if we should do a multiple client reap 
    if($try_to_do_multi) // the "3" value is a way to short circuit this
      $do_multi= $try_to_do_multi==3 ||time()%sfConfig::get('app_reap_multi_time_mod')==0;

    if($do_multi)
    {
      $criteria = new Criteria();
      $criteria->setLimit(sfConfig::get('app_reap_multi_clients_max'));
      $criteria->addAscendingOrderByColumn(ClientPeer::UPDATED_AT);
      $criteria->add(ClientPeer::UPDATED_AT,time()-sfConfig::get('app_reap_client_age_max'),Criteria::LESS_EQUAL);
      // fixme this should be refined more -- perhaps seeds should be even less likely to be reaped etc?
      $all = ClientPeer::doSelect($criteria);
      $items=array_merge(self::prepareReap($all),$items);
    }

    if(/*true ||*/ count($items)>sfConfig::get('app_reap_single_kills_min') || $do_multi)
    {
      if(count($items)) // putting this here in case i short circuit the outer if clause 
      {
        self::doDelete($items); // does do delete actually call the delete method on each?
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
      if($counts[$id]-count($items)>sfConfig::get('app_reap_single_remain_min')) // making sure there's enough items left over
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
        throw new limeException('missing-torrent','Torrent not found');

      $client = new Client();
      $client->setTorrent($torrent);
      $client->setTrackerId(sha1(uniqid(),true));
    }

    return $client;
  }
}
