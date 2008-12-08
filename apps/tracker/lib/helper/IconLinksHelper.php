<?php

function link_to_with_icon($name = '', $icon = '', $internal_uri = '', $options = array()) {
  $name = "<img class='inline_icon' src='/images/icons/" . $icon . ".png'/>" . $name;
  return link_to($name, $internal_uri, $options);
}

?>