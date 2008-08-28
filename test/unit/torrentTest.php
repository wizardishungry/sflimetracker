<?php

include(dirname(__FILE__).'/../bootstrap/unit.php');

$t = new lime_test(2, new lime_output_color());
$t->isa_ok($p=new Torrent(__FILE__),'Torrent','constructor works');

//$p->save();
