<?php
//require_once '/opt/local/lib/php/symfony/autoload/sfCoreAutoload.class.php';
require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{

  public function setup()
  {

    if(!class_exists('PDO',false))
    {
        $this->loadSharedObjects(Array('pdo','pdo_sqlite','sqlite')); // todo support mysql
    }

      $this->enableAllPluginsExcept(array('sfDoctrinePlugin'));
  // fix for dumb PEAR
    set_include_path(get_include_path().PATH_SEPARATOR.$this->getRootDir().DIRECTORY_SEPARATOR.'lib');
  }

  public function setRootDir($rootDir)
  {
    parent::setRootDir($rootDir);
    $this->setWebDir($rootDir);
  }

  public function loadSharedObjects($objects)
  {
    if(!function_exists('dl'))
        throw new sfException("Couldn't dl(); dl not defined");

    if(!ini_get('enable_dl'))
        throw new sfException("Couldn't dl(); ini_get reports enable_dl disabled in php.ini");

    foreach($objects as $o)
    {
        if(!dl($o.'.so'))
            throw new sfException("Couldn't dl(\"$o.so\")");
    }
  }
}
