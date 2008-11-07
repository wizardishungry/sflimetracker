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
    $this->addArgument('version', sfCommandArgument::OPTIONAL, 'The application version',null);
    $this->addArgument('file', sfCommandArgument::OPTIONAL, 'The path to the zipfile',null);
    $this->addOption('verbose', null, sfCommandOption::PARAMETER_REQUIRED, 'Enables verbose output', false);
    $this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'Environment for build SQL task', 'sqlite');
    $this->addOption('build-db', null, sfCommandOption::PARAMETER_REQUIRED,
      'builds the database -- defaults to true if env==sqlite', 'false');
    
  }

  protected function execute($arguments = array(), $options = array())
  {
    if(!$this->checkPreconditions($arguments,$options)) return;

    if($options['env']=='sqlite')
      $options['build-db']=true; // actually a connection

    $tasks = Array(
      Array(new limetrackerMetaTask($this->dispatcher, $this->formatter),
        Array('version'=>@$arguments['version']) , Array('verbose'=>@$options['verbose']) ),
      Array(new sfPropelBuildModelTask($this->dispatcher, $this->formatter),
        Array() , Array('verbose'=>@$options['verbose']) ),
      Array(new sfPropelBuildSqlTask($this->dispatcher, $this->formatter),
        Array() , Array('verbose'=>@$options['verbose']) ),
      Array(new sfPropelBuildFormsTask($this->dispatcher, $this->formatter),
        Array() , Array('verbose'=>@$options['verbose']) ),
    );

    if($options['build-db'])
    {
      $tasks[]=Array(new sfPropelBuildDbTask($this->dispatcher, $this->formatter),Array(),Array() );
      // todo ^ this doesn't work
      // todo copy tracker.db to tracker.db.dist
    }

    if(@$arguments['file'])
    {
      $tasks[]=Array(new limetrackerZipTask($this->dispatcher, $this->formatter),
        Array('file'=>$arguments['file']) , Array('verbose'=>@$options['verbose']) );
      $tasks[]=Array(new limetrackerMetaTask($this->dispatcher, $this->formatter),
        Array('version'=>null), Array('verbose'=>@$options['verbose']) );
    }
    foreach($tasks as $list)
    {
      list($task,$args,$opts)=$list;
      $task->setCommandApplication($this->commandApplication);
      $task->run($args,$opts);
    }
  }

  protected function checkPreconditions($arguments,$options)
  {
    $errors=Array();

    if(  (!$arguments['file'] || !$arguments['version']) && ($arguments['file'] || $arguments['version']  ) )
      $errors[]='You must provide both an filename and version or neither.';

    if($arguments['file']&&!class_exists('ZipArchive'))
      $errors[]='Zip support needs to be compiled into PHP.';

    if(!$arguments['version']&&`git`==null)
      $errors[]='You need an install of git in your $PATH.';

    if($arguments['version']&&(!preg_match('#^\d+\.\d\d#',$arguments['version']) ))
      $errors[]='Version must be of the form 1.00';

    if($arguments['file']&&!preg_match('#\.zip#',$arguments['file']))
      $errors[]='File name must end in zip.';

    if(count($errors))
    {
      $this->log("Cannot build; preconditions unsatisfied:");
      $this->log($errors);
      return false;
    }
    return true;
  }
}
