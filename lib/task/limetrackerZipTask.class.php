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
    $this->processFiles($this->root);

    if($this->zip->close()!==TRUE)
      throw new sfException('Saving zip failed');
    $this->log("Saved zip to $this->path",true);
  }
  protected function processFiles($path)
  {
    $files=glob($path."/{,.}*",GLOB_BRACE);
    foreach($files as $file)
    {
      $is_ok=true;

      if(preg_match('#/\.\.?$#',$file)) // special directory entries
      {
        $is_ok = false;
      }
      else
      {
        $short=preg_replace("#^$this->root/#",'',$file);  
      }

      if($is_ok) foreach($this->excludes as $exclude)
      {
        if(fnmatch("$this->root/$exclude",$file))
        {
          $this->log("Ignore ". (is_dir($file)?'dir ':'file') ." $short \t\t[$exclude]");
          $is_ok=false;
          break;
        }
      }

      if($is_ok)
      {

        if(++$this->count%$this->reopen_interval==$this->reopen_interval-1)
        {
          $this->reopen(); // to stop annoying file handle exhaustion bug
        }

        $is_dist=preg_match('#\.dist$#',$file)==1;
        if($is_dist)
        {
          $short=preg_replace('#\.dist$#','',$short);
        }

        if(is_dir($file))
        {
          $this->log("Adding dir  $short"); // double space makes it line up
          if($is_dist)
            throw new sfException("Cannot use .dist suffixed directories yet");
          $this->zip->addEmptyDir($short);
          $this->processFiles($file);
        }
        else
        {
          $this->log("Adding file $short".($is_dist?" from $short.dist":''));
          
          $entry_exists=$this->zip->locateName($short)!==FALSE;


          if($entry_exists)
          {
            if($is_dist)
            {
              $this->log("Warning! $short already exists!",true);
            }
            else
            {
              throw new sfException("$short already exists in archive!");
            }
          }

          if($this->zip->addFile($file,$short)==FALSE)
            throw new sfException("Couldn't add -- probably too many open files");
        }
      }
    }
  }
  protected function generateExcludes($path)
  {
    $excludes=Array( // hardcoded ignores
      '.gitignore', // not dotfiles
      '.git',       // git directory
      '*/.svn',
      '*/.DS_Store','.DS_Store',
      'lib/vendor/symfony/data/web/sf/sf_*',
      'lib/vendor/symfony/doc',
      'lib/vendor/symfony/lib/i18n',
      'lib/vendor/symfony/lib/plugins',
      'lib/vendor/symfony/lib/task/generator/skeleton',
      'lib/vendor/symfony/lib/plugins/sfCompat10Plugin',
      'lib/vendor/symfony/test',
      'plugins/*/test',
      'plugins/.*',
      'test',       // tests don't bleong in an end user release
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
      $this->log('WARNING: no .gitignore; building on Windows is not such a good idea right now…',true);
    }

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
