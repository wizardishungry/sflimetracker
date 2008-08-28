<?php

include(dirname(__FILE__).'/../bootstrap/unit.php');

$t = new lime_test(2, new lime_output_color());
$t->isa_ok($p=new Podcast(),'Podcast','constructor works');

$p->setFeedUrl(fixture_url('1upShow-rss2.xml'));
$t->isa_ok($p->fetch(),'sfRssFeed','fetch gives us a sfFeed object');
//$p->save();
