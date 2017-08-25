<div 
  data-field="indexfield" 
>
  <table
    class="display"
    width="100%"
    data-indexfield-columns="<?php __(json_encode($field->columns())) ?>"
    data-indexfield-entries="<?php __($field->url('list')) ?>"
    data-indexfield-rows="<?php __($field->rows) ?>"
    data-indexfield-order="<?php __($field->order) ?>"
  ></table>
</div>