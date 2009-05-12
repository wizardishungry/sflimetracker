<?php
/**
 * client actions.
 *
 * @package    sflimetracker
 * @subpackage client
 */
class clientActions extends sfActions
{
  protected $encoder;
  
  protected $response_ok=Array(
          'peers'=>Array(),
          'complete'=>1,
          'incomplete'=>1,
          'interval'=>60,
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


  public function timeout($request)
  {
    if(!$request->isConnectionAborted())
      $this->doError('timed out'); // will this even flush
  }

  public function executeAnnounce($request)
  {

    register_shutdown_function(array($this, "timeout"),$request);

    $this->encoder= new File_Bittorrent2_Encode();
    try{

      if(!SettingPeer::retrieveByKey'tracker_active']))
        throw new limeException('turn-on-tracker','Tracker not active');

      $response=$this->response_ok;

      $this->form=new ClientForm();
      $this->form->bind($request->getGetParameters());

      if(!$this->form->isValid())
      {
        return $this->doError(implode (';\n', $form->getValidatorSchema()->getMessages()) );
      }

      $params=$request->getGetParameters();
      $params['info_hash']=unpack('H*',$params['info_hash']);
      $params['info_hash']=$params['info_hash'][1];
      $params['peer_id']=$params['peer_id'];

      $request->setTimeLimit(10);
      $my_client=ClientPeer::retrieveByParameters($params);

      $request->setIgnoreUserAbort(true); // semi-transactional now

      $my_client->updateWithParameters($params,$request);
      
      
      if(!$my_client->isDeleted())
      {
        $my_client->save();
        $torrent=$my_client->getTorrent();
        $clients=ClientPeer::reap($torrent->getClients());
        $request->setIgnoreUserAbort(false);
        $request->setTimeLimit(20);

        if(!isset($params['compact'])) $params['compact']=TRUE;

        if($params['compact'])
          $response['peers']='';

        $complete=0;
        $incomplete=0;

        foreach($clients as $peer_client)
        {
          if($my_client->getId()!=$peer_client->getId() && !$peer_client->isDeleted())
          {
            if($peer_client->isComplete())
              $complete++;
            else
              $incomplete++;
            if($params['compact'])
              $response['peers'].=$peer_client->getDict(true);
            else
              $response['peers'][]=$peer_client->getDict();
          }
        }
      }
      else
      {
        $request->setIgnoreUserAbort(false);
        $request->setTimeLimit(20);
        unset($response['peers']); // no need to send a stopping peer a list of peers
      }

      $response['tracker id']=$my_client->getTrackerId();
      $response['complete']=$complete;
      $response['incomplete']=$incomplete;



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
