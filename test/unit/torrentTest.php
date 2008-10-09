<?php

include(dirname(__FILE__).'/../bootstrap/unit.php');

class testValidatedFile extends sfValidatedFile
{
  protected $saved_name=null;
  function __construct($originalName, $type, $tempName, $size){}
  public function getOriginalName() { return preg_replace('#\.[^.]*$#','',basename(__FILE__)); }
  public function getOriginalExtension($default='') { return preg_replace('#^.*\.#','',__FILE__); }
  public function save($file, $fileMode = 0666, $create = true, $dirMode = 0777) { return copy(__FILE__,$this->saved_name=$file); }
  public function getSavedName() { return $this->saved_name; }
}

$file=new testValidatedFile(1,2,3,4);
sfConfig::set('sf_upload_dir',$_ENV['TMPDIR']);
$t = new lime_test(1, new lime_output_color());
$t->isa_ok($p=new Torrent($file),'Torrent','constructor works');
