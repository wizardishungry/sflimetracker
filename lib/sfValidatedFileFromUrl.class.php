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

    /*
    $b=new sfWebBrowser(array(
        'user_agent' => sfConfig::get('app_version_name').' ('. sfConfig::get('app_version_rev'). ' '
          .sfConfig::get('app_version_comment'). ') '. sfConfig::get('app_version_url') , 
        'timeout'    => 15
    )); // refactor this into our own browser wrapper class todo
    */



    $original_url=$url;
    $original_name=preg_replace('#^.*/#','',$original_url); // we'll need to do this for redirects fixme

    $b = new disconnectedCurl($url);
    // probably want to have a way to gather statistics here
    $b=run();
    // we should HEAD this first -- giant files will not work well with this method

    //$mime_type=$b->getResponseHeader('Content-Type'); // of course we shouldn't trust people to have their servers configured right…sigh todo
    //$mime_type=preg_replace('/;.*$/','',$mime_type); // remove stuff after the semicolon delimiter "charset=UTF-8" etc

    parent::__construct($original_name,$mime_type,$b->getName(),filesize($b->getName()));
  }

  function __destruct()
  {
    if(file_exists($this->tempName))
      unlink($this->tempName);
  }
}
?>
