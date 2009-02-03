<?php
/*
  sfValidatedFileFromURl
  -- constructs a new sfValidatedFile class via a url
*/
class sfValidatedFileFromUrl extends sfValidatedFile
{
  protected $original_url=null;
  protected $b; // curl object

  public function __construct($url,$callback=null)
  {
    register_shutdown_function(array(&$this, "__destruct"));

    $options=Array(
        CURLOPT_USERAGENT => sfConfig::get('app_version_name').' ('. sfConfig::get('app_version_rev'). ' '
          .sfConfig::get('app_version_comment'). ') '. sfConfig::get('app_version_url') , 
    );


    $this->original_url=$url;
    $original_name=preg_replace('#^.*/#','',$this->original_url); // we'll need to do this for redirects fixme

    $this->b = $b = new disconnectedCurl($url,$options);
    // probably want to have a way to gather statistics here
    try {
        $b->run($callback);
    // we should HEAD this first
    }
    catch(Exception $e)
    {
        throw sfException::createFromException($e);
    }
    
    if($b->isRunning()) throw new sfException('still running');

    $mime_type=$b->getHeader('Content-Type'); // of course we shouldn't trust people to have their servers configured right…sigh todo
    $mime_type=preg_replace('/;.*$/','',$mime_type); // remove stuff after the semicolon delimiter "charset=UTF-8" etc

    parent::__construct($original_name,$mime_type,$b->getFile(),filesize($b->getFile()));
  }

  function __destruct()
  {
    if(file_exists($this->tempName))
      unlink($this->tempName);
  }

  public function getFileSha1()
  {
    $v =$this->b->getSha1();
    if(!$v)
        throw new sfException('Unable to retrieve Sha1 from disconnectedCurl instance');
    return $v;
  }

  public function getUrl()
  {
    return $this->original_url;
  }
}
?>
