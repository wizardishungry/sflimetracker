<?php 
class myWebRequest extends sfWebRequest
{
	public function getRemoteAddress()
	{
		$pathArray = $this->getPathInfoArray();
		
		return $pathArray['REMOTE_ADDR'];
	}
}
