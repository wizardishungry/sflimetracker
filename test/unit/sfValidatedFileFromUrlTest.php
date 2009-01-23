<?php

include(dirname(__FILE__).'/../bootstrap/unit.php');


$t = new lime_test(4, new lime_output_color());

$web_fixtures=file_exists("$sf_root_dir/web/fixtures");
if($web_fixtures)
{
  $t->diag('Trying to use web fixtures…');
  $url=fixture_url('1upShow-rss2.xml',false);
  if(!file_get_contents($url))
  {
    $t->diag("No web fixtures found at ");
  }
  else
  {
    $t->diag('Great, web fixtures are in place');
  }
}
else
{
  $t->diag("web/fixtures does not exist so we're going to use file:// fixtures");
  $t->diag("ln -s ../test/fixtures web");
}

$url=fixture_url('1upShow-rss2.xml',!$web_fixtures);
$t->diag("Fixture url is $url");

$t->isa_ok($file=new sfValidatedFileFromUrl($url),'sfValidatedFileFromUrl','new sfValidatedFileFromUrl()');
$t->is(file_get_contents($file->getTempName()), file_get_contents($url), 'Fixture content is equal' );
if(!$web_fixtures)
  $t->skip(2,"Skipping tests that need web fixtures");
else
{
  $t->diag("Beginning tests that need local web fixtures");
  $t->like($file->getType(),'#xml#','mime type correctly shown as being xml');

  try {
    $missing = new sfValidatedFileFromUrl($url.'ASTRINGTOMAKETHIS404');
    $t->fail("Fetching a missing file didn't throw an exception");
  }
  catch(Exception $e)
  {
    $t->pass("Fetching a missing file threw an exception; that's what we expected");
  }
}
