<?php
function delete_form_for_object($object, $internal_uri = null,$call_me=null) {

  $form = new DeleteForm();
  $form->setDefault('id',$object->getId());

  if($internal_uri==null)
  {
    $internal_uri=sfInflector::underscore(get_class($object)).'/delete';
  }

  if($call_me==null)
  {
    $call_me=strtolower(get_class($object));
  }

  return '<form class="delete-form" action="'. url_for($internal_uri).'" method="POST">'.
    $form.
    '<a class="delete-button" href="#">Delete '.$call_me.'</a>'.
  ' <span class="confirm" style="display: none;">'.
    'Are you sure you want to delete this '.$call_me.'? '.
    '<a class="confirm-yes" href="#">Yes</a> '.
    '<a class="confirm-no" href="#">No</a>'.
  '</span>'.
  '</form>';
}
?>
