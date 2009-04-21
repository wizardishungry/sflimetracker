<?php

function page_title($title,$tag='h1',$echo=true) {
  $sf_response=sfContext::getInstance()->getResponse();
  $sf_response->setTitle(strip_tags($title));
  if($echo) echo $tag?content_tag($tag,$title):$title;
  return $title;
}

?>
