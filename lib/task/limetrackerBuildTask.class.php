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
EOF;
    $this->addArgument('file', sfCommandArgument::REQUIRED, 'The path to the zipfile',null);
    $this->addArgument('version', sfCommandArgument::OPTIONAL, 'The application version',null);
    $this->addOption('verbose', null, sfCommandOption::PARAMETER_REQUIRED, 'Enables verbose output', false);
    
  }

  protected function execute($arguments = array(), $options = array())
  {
    if(!$this->checkPreconditions()) return;

    $tasks = Array(
      Array(new limetrackerMetaTask($this->dispatcher, $this->formatter), Array('version'=>@$arguments['version']) , Array('verbose'=>@$options['verbose']) ),
      Array(new sfPropelBuildModelTask($this->dispatcher, $this->formatter), Array() , Array('verbose'=>@$options['verbose']) ),
      Array(new sfPropelBuildSqlTask($this->dispatcher, $this->formatter), Array() , Array('verbose'=>@$options['verbose']) ),
      Array(new sfPropelBuildFormsTask($this->dispatcher, $this->formatter), Array() , Array('verbose'=>@$options['verbose']) ),
      Array(new limetrackerZipTask($this->dispatcher, $this->formatter), Array('file'=>$arguments['file']) , Array('verbose'=>@$options['verbose']) ),
    );
    foreach($tasks as $list)
    {
      list($task,$args,$opts)=$list;
      $task->setCommandApplication($this->commandApplication);
      $task->run($args,$opts);
    }
  }

  protected function checkPreconditions()
  {
    $errors=Array();

    if(!class_exists('ZipArchive')) $errors[]='Zip support needs to be compiled into PHP';
    if(`git`==null) $errors[]='You need an install of git in your $PATH';

    if(count($errors))
    {
      $this->log("Cannot build; preconditions unsatisfied:");
      $this->log($errors);
      return false;
    }
    return true;
  }
}
