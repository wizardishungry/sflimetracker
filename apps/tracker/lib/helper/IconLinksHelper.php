<?php

function link_to_with_icon($name = '', $icon = '', $internal_uri = '', $options = array()) {
  $name=image_tag('icons'.DIRECTORY_SEPARATOR.$icon, "class=inline_icon").$name;
  return link_to($name, $internal_uri, $options);
}

?>
