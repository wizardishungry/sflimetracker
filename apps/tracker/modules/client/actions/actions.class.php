<?php
/**
 * client actions.
 *
 * @package    sflimetracker
 * @subpackage client
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class clientActions extends sfActions
{
  protected $encoder;
  
  protected $response_ok=Array(
          'peers'=>Array(),
          'complete'=>1,
          'incomplete'=>1,
          'interval'=>6,
          'tracker id'=>'',
  );

  public function doError($reason='undefined error')
  {

    $error=Array('failure reason'=>$reason);
    $text=$this->encoder->encode($error);
   //   return sfView::ERROR;
    sfConfig::set('sf_web_debug', false);
    return $this->renderText($text);
  }


  public function executeScrape($request)
  {
    return sfView::NONE; // at least don't error out
  }

  public function executeAnnounce($request)
  {
    $this->encoder= new File_Bittorrent2_Encode();
    try{
      $response=$this->response_ok;

      $this->form=new ClientForm();
      $this->form->bind($request->getGetParameters());

      if(!$this->form->isBound())
        return $this->doError('form validation failed'); // append reason todo

      $params=$request->getGetParameters();
      $params['info_hash']=unpack('H*',$params['info_hash']);
      $params['info_hash']=$params['info_hash'][1];
      $params['peer_id']=$params['peer_id'];

      if(!isset($params['ip']))
        $params['ip']=$request->getRemoteAddress();

      $client=ClientPeer::retrieveByParameters($params);

      $client->updateWithParameters($params);
      
      $client->save();

      $torrent=$client->getTorrent();
      $clients=ClientPeer::reap($torrent->getClients());

      if($params['compact'])
        $response['peers']='';
      
      foreach($clients as $peer_client)
      {
        if($client->getId()!=$peer_client->getId() && !$peer_client->isDeleted())
        {
          if($params['compact'])
            $response['peers'].=$client->getDict(true);
          else
            $response['peers'][]=$client->getDict();
        }
      }

      $response['tracker id']=$client->getTrackerId();


   //  return sfView::ERROR;
      sfConfig::set('sf_web_debug', false);
      //return $this->renderText(print_r($response,true));
      $output=$this->encoder->encode($response);

      return $this->renderText($output);
    }
    catch(Exception $e)
    {
      return $this->doError($e.'');
    }
  }
}
