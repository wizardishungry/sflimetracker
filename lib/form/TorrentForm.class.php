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
        'file'=> new sfWidgetFormInputFile(),
        'podcast_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
        'file' => new sfValidatorFile(),
        'podcast_id' => new sfValidatorInteger(array('required' => false)),
    ));
    
  }
}
