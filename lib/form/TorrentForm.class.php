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
    ));

    $this->setValidators(array(
        'id' => new sfValidatorInteger(array('required' => false)),
        'episode_id' => new sfValidatorInteger(array('required' => true)),
        'feed_id' => new sfValidatorInteger(array('required' => true)),

        // two options
        'web_url' => new sfValidatorUrl(Array('required' => false)),
    ));

  }
}
