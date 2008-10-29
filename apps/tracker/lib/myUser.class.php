<?php

class myUser extends sfBasicSecurityUser
{
  public function setPassword($password)
  {
  }

  public function verifyPassword($password)
  {
  }

  protected function getPasswdPath()
  {
    $passwd=sfContext::getInstance()->getConfiguration()->getRootDir().DIRECTORY_SEPARATOR.'.htpasswd';
    if(!file_exists($passwd))
      throw new sfException("Can't find .htpasswd");
    if(!is_readable($passwd))
      throw new sfException("Can't read .htpasswd");
    return $passwd; 
  }
  public function getCryptedString() //  nb is this sha1 without salt
  {
    $contents=file_get_contents($this->getPasswdPath());
    if($contents===false)
      throw new sfException("Couldn't read .htpasswd");
    $lines=explode("\n",$contents);
    if(!count($lines))
      throw new sfException("Invalid .htpasswd");

    $default_user=sfConfig::get('app_version_name'); // should be "LimeTracker"
    foreach($lines as $line)
    {
      List($user,$hash)=explode(':',$line);
      if($user==$default_user)
      {
        $hash=preg_replace('#^{SHA}#','',$hash,1,$count);
        if($count!=1)
          throw new sfException("Password must be added using SHA-1.");
        return $hash;
      }
    }
    throw new sfException("Couldn't retrieve default user");
  }
}
