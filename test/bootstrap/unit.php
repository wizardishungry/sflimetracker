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



global $sf_root_dir; 

$sf_symfony_lib_dir=$configuration->getSymfonyLibDir();
$sf_root_dir=$configuration->getRootDir();

require_once($sf_symfony_lib_dir.'/autoload/sfSimpleAutoload.class.php');
$autoload = sfSimpleAutoload::getInstance();
$autoload->addDirectory($sf_symfony_lib_dir.'/util');
$autoload->addDirectory($sf_root_dir.'/plugins');
$autoload->register();

$app_config=$configuration->getApplicationConfiguration('tracker','test',true,$sf_root_dir);

$databaseManager = new sfDatabaseManager($app_config);
$databaseManager->loadConfiguration();

sfContext::createInstance($app_config);

function fixture_url($name)
{
//    return "file://localhost$sf_root_dir/test/fixtures/$name"; // fixme, blocked on symfony #4174
    return "http://localhost/sflimetracker/fixtures/$name";
}

