<?php 
class myWebRequest extends sfWebRequest
{

	public function getRemoteAddress()
	{
		$pathArray = $this->getPathInfoArray();
		
		return $pathArray['REMOTE_ADDR'];
	}

  public function setTimeLimit($seconds)
  {
    // nb: this is the amount of time starting NOW
    // zero = ∞
    $restore = set_time_limit($seconds);
  }
  public function isConnectionAborted()
  {
    return connection_aborted();
  }
  public function setIgnoreUserAbort($setting)
  {
    return ignore_user_abort($setting);
  }
}
