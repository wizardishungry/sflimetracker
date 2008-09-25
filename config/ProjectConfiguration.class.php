<?php
//require_once '/opt/local/lib/php/symfony/autoload/sfCoreAutoload.class.php';
require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    // fix for dumb PEAR
    set_include_path(get_include_path().PATH_SEPARATOR.$this->getRootDir().DIRECTORY_SEPARATOR.'lib');
  }
}
