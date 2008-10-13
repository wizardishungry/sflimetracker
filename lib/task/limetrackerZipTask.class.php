<?php

class limetrackerZipTask extends sfBaseTask
{

  protected $verbose=false; // todo add cmdline option
  protected $reopen_interval=200; // see http://bugs.php.net/bug.php?id=40494
  protected $count=0;
  protected $path;

  protected function configure()
  {
    $this->namespace        = 'limetracker';
    $this->name             = 'zip';
    $this->briefDescription = 'Creates a zipfile of a release';
    $this->detailedDescription = <<<EOF
The [limetracker:zip|INFO] task makes a ready to go zipfile.
Call it with:

  [php symfony limetracker:zip|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    if(!class_exists('ZipArchive')) throw new sfException('Class ZipArchive not found');

    $this->zip=new ZipArchive();

    $this->path='/tmp/limetracker.zip';

    $this->log("Saving  zip to $this->path , please wait…",true);
    if($this->zip->open($this->path,ZipArchive::OVERWRITE)!==TRUE)
      throw new sfException("Can't make a zip");
    $this->root=realpath(dirname(__FILE__).'/../..');
    $this->generateExcludes($this->root);
    $files=$this->processFiles($this->root);

    if($this->zip->close()!==TRUE)
      throw new sfException('Saving zip failed');
    $this->log("Saved zip to $this->path",true);
  }
  protected function processFiles($path)
  {
    $files=glob($path."/*");
    $match=Array();
    foreach($files as $file)
    {
      $this->log("Considering $file");
      $is_ok=true;
      foreach($this->excludes as $exclude)
      {
        if(fnmatch($exclude,$file))
        {
          $this->log("Excluding $file via $exclude");
          $is_ok=false;
          break;
        }
      }
      if($is_ok)
      {
        $match[]=$file;
        $short=preg_replace("#^$this->root/#",'',$file);

        if(++$this->count%$this->reopen_interval==$this->reopen_interval-1)
        {
          $this->reopen(); // to stop annoying file handle exhaustion bug
        }

        if(is_dir($file))
        {
          $this->log("Adding directory $file as $short");
          $this->zip->addEmptyDir($short);
          $this->log("Entering directory $file");
          $match=array_merge($match,$this->processFiles($file));
        }
        else
        {
          $this->log("Adding file $file as $short");
          if($this->zip->addFile($file,$short)==FALSE)
            throw new sfException("ARGH");
          //$this->addFromString($short,file_get_contents($file));
        }
      }
    }
    return $match;
  }
  protected function generateExcludes($path)
  {
    $excludes=Array( // hardcoded ignores
      '.gitignore', // not dotfiles
      'test',       // tests don't bleong in an end user release
      'lib/vendor/symfony/test',
      'lib/vendor/symfony/doc',
      'lib/vendor/symfony/lib/i18n',
      'lib/vendor/symfony/lib/plugins',
      'plugins/*/test',
      'lib/vendor/symfony/data/web/sf/sf_*',
    );

    $contents=file_get_contents("$path/.gitignore");
    if($contents!==FALSE)
    {
      foreach(split("\n",$contents) as $line)
      {
        if(preg_match('/^.*add2zip/',$line)) // lines below "add2zip" are removed from exlude array
          break;
        else
          $excludes[]=$line;
      }
    }
    else
    {
      $this->log('Warning: no .gitignore; building on Windows is not such a good idea right now…');
    }

    foreach($excludes as &$glob)
      $glob="$path/$glob";

    $this->excludes=$excludes;
  }

  public function log($v,$force=false)
  {
    if($force||$this->verbose)
      parent::log($v);
  }

  protected function reopen()
  {
    $this->zip->close();
    $this->zip->open($this->path);
  }
}
