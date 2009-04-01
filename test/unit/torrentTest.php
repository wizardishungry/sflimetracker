<?php

include(dirname(__FILE__).'/../bootstrap/unit.php');

class testValidatedFile extends sfValidatedFile
{
  protected $saved_name=null;
  function __construct($originalName, $type, $tempName, $size){}
  public function getOriginalName() { return preg_replace('#\.[^.]*$#','',basename(__FILE__)); }
  public function getOriginalExtension($default='') { return preg_replace('#^.*\.#','',__FILE__); }
  public function save($file = null, $fileMode = 0666, $create = true, $dirMode = 0777) { return copy(__FILE__,$this->saved_name=$file); }
  public function getSavedName() { return $this->saved_name; }
  public function getTempName() { return __FILE__; /*better not call unlink() :) */ }
}

$file=new testValidatedFile(1,2,3,4);
sfConfig::set('sf_upload_dir',sys_get_temp_dir());

$t = new lime_test(3, new lime_output_color());

$p=new Torrent($file);
$p->setFile($file);

$t->ok(file_exists($p->getTorrentPath()), '.torrent exists at path'.$p->getTorrentPath());
$t->isa_ok($p->getFeedEnclosure(),'sfFeedEnclosure','got feed enclosure');

$t->diag('running delete');
try {
  $p->delete();
}
catch(PropelException $pe) { /* no database connectivity */  }
$t->ok(!file_exists($p->getTorrentPath()), '.torrent no longer exists at path'.$p->getTorrentPath());
