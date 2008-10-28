<?php

/**
 * Torrent form.
 *
 * @package    form
 * @subpackage Torrent
 * @version    SVN: $Id: sfPropelFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class TorrentForm extends BaseTorrentForm
{
  public function configure()
  {
    $this->setWidgets(Array(
        'podcast_id' => new sfWidgetFormInputHidden(),
        'title'=> new sfWidgetFormInput(),
        'description' => new sfWidgetFormTextarea(),
        'web_url'=> new sfWidgetFormInput(),
        'server_path' => new sfWidgetFormInput(),
        'file'=> new sfWidgetFormInputFile(),
    ));

    $this->setValidators(array(
        'podcast_id' => new sfValidatorInteger(array('required' => false)),
        'title' => new sfValidatorString(array('required' => false)),
        'description' => new sfValidatorString(array('required' => false)),

        // three oprions
        'web_url' => new sfValidatorUrl(Array('required' => false)),
        'file' => new sfValidatorFile(Array('required' => false)),
        'server_path' => new sfValidatorString(Array('required' => false)),
    ));

    $options=Array('required' => true);
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
