<?php
include(dirname(__FILE__).'/../bootstrap/unit.php');
require_once("$sf_root_dir/apps/tracker/lib/myUser.class.php");
sfContext::createInstance($app_config,'tracker');

$name = sfConfig::get('app_version_name');

$t = new lime_test(5, new lime_output_color());
$t->isa_ok($user=new myUser(new sfEventDispatcher(), new sfSessionTestStorage()),'myUser','new myUser()');
$t->isa_ok($user->getCryptedString(),'string','getCryptedString()');
if(file_get_contents("$sf_root_dir/.htpasswd")!=file_get_contents("$sf_root_dir/.htpasswd.dist"))
{
  $t->skip("Skipping tests -- cp .htpasswd.dist .htpasswd to have them run",1);
}
else
{
  $t->is($user->getCryptedString(),myUser::crypt($name),"getCryptedString() == sha1('$name')");
  $t->ok($user->authenticatePassword($name),"authenticatePassword() works with correct password");
  $t->ok(!$user->authenticatePassword($name.'hurble durble'),"authenticatePassword() fails with incorrect password");
}
