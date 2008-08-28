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
