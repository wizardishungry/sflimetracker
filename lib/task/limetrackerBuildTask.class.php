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
    if(!$this->checkPreconditions()) return;
    $tasks = Array(
      new limetrackerMetaTask($this->dispatcher, $this->formatter),
      new sfPropelBuildModelTask($this->dispatcher, $this->formatter),
      new sfPropelBuildSqlTask($this->dispatcher, $this->formatter),
      new sfPropelBuildFormsTask($this->dispatcher, $this->formatter),
      new limetrackerZipTask($this->dispatcher, $this->formatter),
    );
    foreach($tasks as $task)
    {
      $task->setCommandApplication($this->commandApplication);
      $task->run();
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
