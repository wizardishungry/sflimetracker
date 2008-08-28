<?php

/**
 * Client form.
 *
 * @package    form
 * @subpackage client
 * @version    SVN: $Id: sfPropelFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class ClientForm extends BaseClientForm
{
  public function configure()
  {
    $this->setValidators(array(
      'info_hash'   => new sfValidatorAnd(array(
            new sfValidatorString(array('min_length' => 20, 'max_length' => 20)),
            new sfValidatorRegex(array('pattern' => '/[\S]+/')),
      )),
      'peer_id'     => new sfValidatorString(array('max_length' => 20)),
      'uploaded'    => new sfValidatorInteger(array('min'=>0)),
      'downloaded'  => new sfValidatorInteger(array('min'=>0)),
      'left'        => new sfValidatorInteger(array('min'=>0)),
      'ip'          => new sfValidatorRegex(array('pattern'=>'/(\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b)'
                                                              .'([^:]*):([^:]*):([^:]*):([^:]*)::([^:]*)/')),
      'port'        => new sfValidatorInteger(array('min'=>0,'max'=>65536)),

      'compact'     => new sfValidatorBoolean(array('required'=>false,'true_values'=>Array(1),'false_values'=>Array(0))),
      'key'         => new sfValidatorString(array('required'=>false,'max_length' => 20)),
    ));
  }
}
