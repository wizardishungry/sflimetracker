<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

// create a new test browser
$browser = new sfTestBrowser();

$browser->
  get('/podcast_feed/index')->
  isStatusCode(200)->
  isRequestParameter('module', 'podcast_feed')->
  isRequestParameter('action', 'index')->
  checkResponseElement('body', '!/This is a temporary page/');
