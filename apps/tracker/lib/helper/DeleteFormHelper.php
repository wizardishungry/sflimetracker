<?php
function delete_form_for_object($object, $internal_uri = null) {

  $form = new DeleteForm();
  $form->setDefault('id',$object->getId());
  if($internal_uri==null)
  {
    $internal_uri=sfInflector::underscore(get_class($object)).'/delete';
  }

  return '<form class="delete-form" action="'. url_for($internal_uri).'" method="POST">'.
    $form.
    '<a class="delete-button" href="#">Remove '.strtolower(get_class($object)).'</a>'.
  ' <span class="confirm" style="display: none;">'.
    'Are you sure you want to remove this '.strtolower(get_class($object)).'? '.
    '<a class="confirm-yes" href="#">Yes</a> '.
    '<a class="confirm-no" href="#">No</a>'.
  '</span>'.
  '</form>';
}
?>
