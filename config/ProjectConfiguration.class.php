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
        $classes=Array('pdo');
        $use_sqlite = is_writeable($this->getRootDir().DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'tracker.db');

        if($use_sqlite)
        {
            $classes[]='pdo_sqlite';
            $classes[]='sqlite';
        }
        else
        {
            $classes[]='pdo_mysql';
            $classes[]='mysql';
        }

        $this->loadSharedObjects($classes);
        // this could really use to be made more elegant but if you're
        // installing on a webhost without PDO setup right, this
        // is the least of your problems
    }

    $this->enableAllPluginsExcept(file_exists(dirname(__FILE__).'/../lib/vendor/symfony/lib/plugins/sfDoctrinePlugin')?
        array('sfDoctrinePlugin'):array());
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
        throw new limeException('load-shared-objects',"Couldn't dl(); dl not defined");

    if(!ini_get('enable_dl'))
        throw new limeException('load-shared-objects',"Couldn't dl(); ini_get reports enable_dl disabled in php.ini");

    foreach($objects as $o)
    {
        if(!dl($o.'.so'))
            throw new limeException('load-shared-objects',"Couldn't dl(\"$o.so\")");
    }
  }
}
