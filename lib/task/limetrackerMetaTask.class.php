<?php

class limetrackerMetaTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'limetracker';
    $this->name             = 'meta';
    $this->briefDescription = 'Write out metainformation for a build';
    $this->detailedDescription = <<<EOF
The [limetracker:meta|INFO] task does some metadata setup that isn't done elsewhere.
Call it with:

  [php symfony limetracker:meta|INFO]
EOF;
    //$this->addArgument('application', sfCommandArgument::REQUIRED, 'The application name');
    // add other arguments here
    //$this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev');
    //$this->addOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel');
    // add other options here
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->writeVersion();
  }

  protected function writeVersion($unstable=true)
  {
    $root=realpath(dirname(__FILE__).'/../..');
    $path=$root.'/VERSION';
    $build_time="built ".date('c');
    if($unstable)
    {
      $cmd="git log --date=local -n 1 --pretty=format:'git-%h, committed %ci' $root";
      $str=shell_exec(escapeshellcmd($cmd));
      if($str==null)
      {
        $str="no-git, $build_time";
      }
    }
    else
    {
      $str="0.01-invalid, $build_time"; // CHANGE HOW WE UNDERSTAND RELEASE #'S fixme
    }

    $str="LimeTracker, $str, http://limecast.com/tracker";
    $parts=explode(', ',$str);
    $keys=Array('name','rev','comment','url');
    $count=0;
    foreach($parts as &$part)
    {
      $key=$keys[$count++];
      $part="$key: '$part'";
    }
    file_put_contents($path,"{".implode(', ',$parts)."}\n");
    $this->log('Wrote version string: '.$str);
  }
}
