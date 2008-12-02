<?php
/*
 * This file is part of the sfFeed2Plugin package.
 * 
 * (c) 2008 François Zaninotto <francois.zaninotto@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Autofind the first available app environment
$sf_root_dir = realpath(dirname(__FILE__).'/../../../');
$apps_dir = glob($sf_root_dir.'/apps/*', GLOB_ONLYDIR);
$app = substr($apps_dir[0], 
              strrpos($apps_dir[0], DIRECTORY_SEPARATOR) + 1, 
              strlen($apps_dir[0]));
if (!$app)
{
  throw new Exception('No app has been detected in this project');
}

// -- path to the symfony project where the plugin resides
$sf_path = dirname(__FILE__).'/../../..';

// bootstrap
include($sf_path . '/test/bootstrap/functional.php');
require_once(sfConfig::get('sf_symfony_lib_dir').'/vendor/lime/lime.php'); 

$h = new lime_harness(new lime_output_color());
$h->base_dir = dirname(__FILE__);

// register all tests
$finder = sfFinder::type('file')->ignore_version_control()->follow_link()->name('*Test.php');
$h->register($finder->in($h->base_dir));

$h->run();
