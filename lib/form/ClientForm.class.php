<?php

/**
 * Client form.
 *
 * @package    form
 * @subpackage client
 */
class ClientForm extends BaseClientForm
{
  public function configure()
  {
    $this->setValidators(array(
      'info_hash'   => new sfValidatorAnd(array(
            // commenting this out since it doesn't seem to know about url encoding
            //new sfValidatorString(array('min_length' => 20, 'max_length' => 20)),
            new sfValidatorRegex(array('pattern' => '/[\S]+/')),
      )),
      'peer_id'     => new sfValidatorString(array('max_length' => 20)),
      'uploaded'    => new sfValidatorInteger(array('min'=>0)),
      'downloaded'  => new sfValidatorInteger(array('min'=>0)),
      'left'        => new sfValidatorInteger(array('min'=>0)),
      // Commenting out ip field since it can be  ipv4 ipv6 or a hostname
      //'ip'          => new sfValidatorRegex(array('required'=>false,
      //  'pattern'=>'/(\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b)([^:]*):([^:]*):([^:]*):([^:]*)::([^:]*)/')),
      'port'        => new sfValidatorInteger(array('min'=>0,'max'=>65536)),
      'compact'     => new sfValidatorBoolean(array('required'=>false,'true_values'=>Array(1),'false_values'=>Array(0))),
      'key'         => new sfValidatorString(array('required'=>false,'max_length' => 20)),
    ));
    $this->disableCSRFProtection();
    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', false);
  }
}
