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

    $b=new sfWebBrowser(array(
        'user_agent' => sfConfig::get('app_version_name').' ('. sfConfig::get('app_version_rev'). ' '
          .sfConfig::get('app_version_comment'). ') '. sfConfig::get('app_version_url') , 
        'timeout'    => 15
    )); // refactor this into our own browser wrapper class todo

    $this->tempName=tempnam(sys_get_temp_dir(),sfConfig::get('app_version_name')); // is there a symfony way of getting tmp dir
    $original_url=$url;
    $original_name=preg_replace('#^.*/#','',$original_url);

    // probably want to have a way to gather statistics here
    $b->get($url); // we should HEAD this first -- giant files will not work well with this method

    $mime_type=$b->getResponseHeader('Content-Type'); // of course we shouldn't trust people to have their servers configured right…sigh todo
    $mime_type=preg_replace('/;.*$/','',$mime_type); // remove stuff after the semicolon delimiter "charset=UTF-8" etc

    if(!file_put_contents($this->tempName,$b->getResponseText(),LOCK_EX)) // as above, we don't wanna to do strings with huge files
      throw new sfException("Couldn't write out temp file");
    parent::__construct($original_name,$mime_type,$this->tempName,filesize($this->tempName));
  }

  function __destruct()
  {
    if(file_exists($this->tempName))
      unlink($this->tempName);
  }
}
?>
