<?php

require __DIR__ . DS . 'options.php';

class IndexFieldController extends Kirby\Panel\Controllers\Field {

  // get entries for the current table
  public function list () {
    $field = $this->field();
    $data = Kirby\Panel\Form\IndexFieldOptions::build($field);
    if ($field->filter()) {
      $data = call_user_func($field->filter(), $data);
    } 
    return response::json($data);
  }

}