<?php

include(dirname(__FILE__).'/../bootstrap/unit.php');

$t = new lime_test(3, new lime_output_color());
$t->isa_ok($p=new Feed(),'Feed','constructor works');
$p->setRssUrl(fixture_url('1upShow-rss2.xml'));
$t->isa_ok($p->fetch(),'sfRssFeed','fetch() gives us a sfFeed object');
$t->diag("\$p->getLastFetched()\n\t".$p->getLastFetched());
$t->isa_ok($p->getLastFetched(null),'DateTime','getLastFetched() gives us a DateTime object');
