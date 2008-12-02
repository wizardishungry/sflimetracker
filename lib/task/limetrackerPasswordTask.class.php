<?php
$sf_root_dir = realpath(dirname(__FILE__).'/../..');
require_once("$sf_root_dir/apps/tracker/lib/trackerUser.class.php");

class limetrackerPasswordTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'limetracker';
    $this->name             = 'password';
    $this->briefDescription = 'Set the login password';
    $this->detailedDescription = <<<EOF
The [limetracker:password|INFO] task writes a hashed password to the datastore.
Call it with:

  [php symfony limetracker:meta|INFO]

Be careful as this will likely leave the unencrypted password in your shell
history.

The datastore is a file named ".htpasswd" stored in the root of the limetracker
directory. This is intentional -- see the wiki for discussion of this.

EOF;
    $this->addArgument('password', sfCommandArgument::REQUIRED, 'The password',null);

  }

  protected function execute($arguments = array(), $options = array())
  {
    $user=new trackerUser(new sfEventDispatcher(), new sfSessionTestStorage());
    $user->setPassword($arguments['password']);
    $this->log('Password changed.');
  }
}
