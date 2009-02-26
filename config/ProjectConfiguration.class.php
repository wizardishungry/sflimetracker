<?php
//require_once '/opt/local/lib/php/symfony/autoload/sfCoreAutoload.class.php';
require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
      $this->enableAllPluginsExcept(array('sfDoctrinePlugin'));
  // fix for dumb PEAR
    set_include_path(get_include_path().PATH_SEPARATOR.$this->getRootDir().DIRECTORY_SEPARATOR.'lib');
  }
  public function setRootDir($rootDir)
  {
    parent::setRootDir($rootDir);
    $this->setWebDir($rootDir);
  }
}
