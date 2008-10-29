<?php
include(dirname(__FILE__).'/../bootstrap/unit.php');
require_once("$sf_root_dir/apps/tracker/lib/myUser.class.php");
sfContext::createInstance($app_config,'tracker');

$t = new lime_test(2, new lime_output_color());
$t->isa_ok($user=new myUser(new sfEventDispatcher(), new sfSessionTestStorage()),'myUser','new myUser()');
$t->isa_ok($user->getCryptedString(),'string','getCryptedString()');
