<?php

/**
 * sfWidgetFormDuration is a text field that parses a duration ("1:15") into an integer
 * 
 * @package    limetracker
 * @subpackage widget
 * @author     Michael Nutt <michael@nuttnet.net>
 * @version    0.1
 */

class sfWidgetFormDuration extends sfWidgetFormInput
{
  /**
   * Configures the current widget
   */
  protected function configure($options=array(), $attributes=array())
  {
    parent::configure($options, $attributes);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    return parent::render($name, $this->getFormattedLength($value), $attributes, $errors);
  }

  public function getFormattedLength($length=0) {
    $hours = (int) ($length / 3600);
    $minutes = (int) ($length % 3600 / 60);
    $seconds = (int) ($length % 60);

    if($seconds < 10)
      $seconds = "0".$seconds;

    if($hours > 0) {
      if($minutes < 10)
        $minutes = "0".$minutes;
      return $hours.":".$minutes.":".$seconds;
    } else {
      return $minutes.":".$seconds;
    }
  }
}