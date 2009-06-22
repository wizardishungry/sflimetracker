<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$_test_dir = realpath(dirname(__FILE__).'/..');

require_once(dirname(__FILE__).'/../../config/ProjectConfiguration.class.php');
$configuration = new ProjectConfiguration(realpath($_test_dir.'/..'));
include($configuration->getSymfonyLibDir().'/vendor/lime/lime.php');



$sf_symfony_lib_dir=$configuration->getSymfonyLibDir();
$sf_root_dir=$configuration->getRootDir();

require_once($sf_symfony_lib_dir.'/autoload/sfSimpleAutoload.class.php');
$autoload = sfSimpleAutoload::getInstance();
$autoload->addDirectory($sf_symfony_lib_dir);
$autoload->addDirectory($sf_root_dir.'/plugins');
$autoload->addDirectory($sf_root_dir.'/lib');
$autoload->register();

// Commenting this out because it seems to be breaking stuff right now
$app_config=$configuration->getApplicationConfiguration('tracker','test',true/*,$sf_root_dir*/);

$databaseManager = new sfDatabaseManager($app_config);
//$databaseManager->loadConfiguration();

sfContext::createInstance($app_config,'tracker');

$sessionPath = sfToolkit::getTmpDir().'/sessions_'.rand(11111, 99999);
$storage= new sfSessionTestStorage(Array('session_path'=>$sessionPath));

function fixture_url($name,$file_url=true)
{
    global $sf_root_dir;

    //$file_url=false; // fixme, blocked on symfony #4174 -- uncomment out if unpatched

    if($file_url)
      return "file://localhost$sf_root_dir/test/fixtures/$name";
    else
      return "http://localhost/sflimetracker/fixtures/$name"; // todo determine real install location
}

