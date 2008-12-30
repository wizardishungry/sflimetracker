<?php

/**
 * Torrent form.
 *
 * @package    form
 * @subpackage Torrent
 */
class TorrentForm extends BaseTorrentForm
{
  public function configure()
  {
    $this->setWidgets(Array(
        'id' => new sfWidgetFormInputHidden(),
        'episode_id' => new sfWidgetFormInputHidden(),
        'feed_id' => new sfWidgetFormInputHidden(),
        'web_url'=> new sfWidgetFormInput(),
        'server_path' => new sfWidgetFormInput(),
        'file'=> new sfWidgetFormInputFile(),
    ));

    $this->setValidators(array(
        'id' => new sfValidatorInteger(array('required' => false)),
        'episode_id' => new sfValidatorInteger(array('required' => true)),
        'feed_id' => new sfValidatorInteger(array('required' => true)),

        // three oprions
        'web_url' => new sfValidatorUrl(Array('required' => false)),
        'file' => new sfValidatorFile(Array('required' => false)),
        'server_path' => new sfValidatorString(Array('required' => false)),
    ));

    $options=Array('required' => false);
    $messages=Array('required'=>'You must pick at least a file, a url, or a server path');

    $this->validatorSchema->setPostValidator(
      new sfValidatorOr(array(
        new sfValidatorSchemaFilter('web_url', new sfValidatorUrl($options,$messages)),
        new sfValidatorSchemaFilter('file', new sfValidatorFile($options,$messages)),
        new sfValidatorSchemaFilter('server_path', new sfValidatorString($options,$messages)),
      )
    ));
    
  }
}
