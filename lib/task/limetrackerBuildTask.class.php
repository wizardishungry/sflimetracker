<?php

class limetrackerBuildTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'limetracker';
    $this->name             = 'build';
    $this->briefDescription = 'Build a release of LimeTracker';
    $this->detailedDescription = <<<EOF
The [limetracker:build|INFO] task does things to setup a complete build of LimeTracker.
Call it with:

  [php symfony limetracker:build|INFO]
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
    $tasks = Array(
      new sfPropelBuildModelTask($this->dispatcher, $this->formatter),
      new sfPropelBuildSqlTask($this->dispatcher, $this->formatter),
      new sfPropelBuildFormsTask($this->dispatcher, $this->formatter),
    );
    foreach($tasks as $task)
    {
      $task->setCommandApplication($this->commandApplication);
      $task->run();
    }
  }

  protected function writeVersion($unstable=true)
  {
    $root=realpath(dirname(__FILE__).'/../..');
    $path=$root.'/VERSION';
    if($unstable)
    {
      $cmd="git log --date=local -n 1 --pretty=format:'git-%h committed %ci' $root";
      $str=shell_exec(escapeshellcmd($cmd));
    }
    else
    {
      $str='0.01 INVALID'; // CHANGE HOW THIS WORKS fixme
    }

    $str="LimeTracker ($str) http://limecast.com/tracker";
    file_put_contents($path,$str);
    $this->log('Wrote version string: '.$str);
  }
}
