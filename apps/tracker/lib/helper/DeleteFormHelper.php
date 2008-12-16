<?php
function delete_form_for_object($object, $internal_uri = null) {

  $form = new DeleteForm();
  $form->setDefault('id',$object->getId());
  if($internal_uri==null)
  {
    $internal_uri=sfInflector::underscore(get_class($object)).'/delete';
  }

  return '<form action="'. url_for($internal_uri).'" method="POST">'.
    $form->__toString().
  '<input type="submit" value="Remove"/>
  </form>';
}
?>
