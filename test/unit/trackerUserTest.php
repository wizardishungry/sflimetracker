<?php
include(dirname(__FILE__).'/../bootstrap/unit.php');
require_once("$sf_root_dir/apps/tracker/lib/trackerUser.class.php");

$name = sfConfig::get('app_version_name');

$t = new lime_test(5, new lime_output_color());
$t->isa_ok($user=new trackerUser(new sfEventDispatcher(), $storage),'trackerUser','new trackerUser()');
$t->isa_ok($user->getCryptedString(),'string','getCryptedString()');
$t->is($user->getCryptedString(),trackerUser::crypt($name),"getCryptedString() == sha1('$name')");
$t->ok($user->authenticatePassword($name),"authenticatePassword() works with correct password");
$t->ok(!$user->authenticatePassword($name.'hurble durble'),"authenticatePassword() fails with incorrect password");
