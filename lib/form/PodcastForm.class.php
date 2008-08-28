<?php

/**
 * Podcast form.
 *
 * @package    form
 * @subpackage Podcast
 * @version    SVN: $Id: sfPropelFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class PodcastForm extends BasePodcastForm
{
  public function configure()
  {
    $this->setWidgets(Array(
        'feed_url'  => new sfWidgetFormInput(),
    ));
   $this->setValidators(array(
        'feed_url'  => new sfValidatorUrl(array('required' => false)),
    ));
  }
}
