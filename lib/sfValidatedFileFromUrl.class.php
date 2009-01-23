<?php
/*
  sfValidatedFileFromURl
  -- constructs a new sfValidatedFile class via a url
*/
class sfValidatedFileFromUrl extends sfValidatedFile
{
  protected $original_url=null;

  public function __construct($url)
  {
    register_shutdown_function(array(&$this, "__destruct"));

    $options=Array(
        CURLOPT_USERAGENT => sfConfig::get('app_version_name').' ('. sfConfig::get('app_version_rev'). ' '
          .sfConfig::get('app_version_comment'). ') '. sfConfig::get('app_version_url') , 
    );


    $original_url=$url;
    $original_name=preg_replace('#^.*/#','',$original_url); // we'll need to do this for redirects fixme

    $b = new disconnectedCurl($url,$options);
    // probably want to have a way to gather statistics here
    try {
        $b->run(null);
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
}
?>
