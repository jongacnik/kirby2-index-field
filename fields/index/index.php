<?php

/*
Todo

- Fix Edit/Add links functionality (right now only works for subpages)
- Rows should link to edit page
- Design

*/

class IndexField extends SelectField {

  public function __construct() {
    $this->type    = 'index';
    $this->options = [];
    $this->icon    = false;
  }

  static public $assets = [
		'css' => [
      'datatables.min.css',
			'main.css'
		],
		'js' => [
			'datatables.min.js',
			'main.js'
		]
	];

  public function options() {
    return SubpageIndexFieldOptions::build($this);
  }

  public function input () {
    $this->columns = isset($this->columns) ? $this->columns : ['title' => 'Title'];

    // Create table structure

    $table = new Brick('table');

    $thead = new Brick('thead');
    $tfoot = new Brick('tfoot');

    $tr = new Brick('tr');
    // insert Edit Header
    $tr->append(new Brick('th', 'Edit'));
    foreach ($this->columns as $key => $column) {
      $tr->append(new Brick('th', $column));
    }

    $thead->append($tr);
    $tfoot->append($tr);
    $table->append($thead);
    $table->append($tfoot);

    // Create JSON of items

    $items = [];
    foreach ($this->options() as $option) {
      $data = [];
      // insert edit url
      $data[] = (string)$option->url('edit');

      // insert columns
      foreach ($this->columns as $key => $column) {
        $data[] = (string)$option->{$key}(); // call method
      }
      $items[] = $data;
    }

    $table->data('indexfield-data', json_encode($items));

    if (isset($this->rows)) {
      $table->data('indexfield-rows', $this->rows);      
    }

    $table->addClass('display');
    $table->attr('cellspacing', 0);
    $table->attr('width', '100%');
    
		return $table;
	}

  public function element() {
    $element = parent::element();
    $element->data('field', 'indexfield');
    return $element;
  }

  public function splinks () {
    if (in_array($this->options, ['children', 'visibleChildren', 'invisibleChildren'])) {
      $spanWrap = new Brick('span');
      $spanWrap->addClass('hgroup-options shiv shiv-dark shiv-left');

      $spanWrapInner = new Brick('span');
      $spanWrapInner->addClass('hgroup-option-right');

      $editLink = new Brick('a', '<i class="icon icon-left fa fa-pencil"></i><span>Edit</span>');
      $editLink->attr('href', $this->page->url('subpages'));
      $editLink->attr('title', 'Edit');

      $addLink = new Brick('a', '<i class="icon icon-left fa fa-plus-circle"></i><span>Add</span>');
      $addLink->attr('href', $this->page->url('add'));
      $addLink->attr('title', '+');
      $addLink->data('shortcut', '+');
      $addLink->data('modal', 'true');

      $spanWrapInner->append($editLink);
      $spanWrapInner->append($addLink);
      $spanWrap->append($spanWrapInner);
      
      return $spanWrap; 
    }
  }

  public function label() {
    if(!$this->label) return null;

    $label = new Brick('label');
    $label->addClass('label');
    $label->attr('for', $this->id());

    $h2 = new Brick('h2');
    $h2->addClass('hgroup hgroup-single-line hgroup-compressed cf');
    $span = new Brick('span', $this->i18n($this->label));
    $span->addClass('hgroup-title');

    $h2->append($span);

    // Edit/Add links if index of subpages
    if (isset($this->options) && $splinks = $this->splinks()) {
      if (!(isset($this->addedit) && !$this->addedit)) {
        $h2->append($splinks); 
      }
    }

    $label->append($h2);

    return $label;
  }

}

/**
 * Custom field options class which only supports
 * options as query and page method. Eventually support API.
 *
 * @return array of kirby objects
 */

class SubpageIndexFieldOptions extends FieldOptions {

  public function __construct($field) {

    $this->field = $field;

    if($this->field->options == 'query') {
      $this->options = $this->optionsFromQuery($this->field->query);
    // } else if($this->field->options == 'url') {
    //   $this->options = $this->optionsFromApi($this->field->url);
    // } else if($this->isUrl($this->field->options)) {
    //   $this->options = $this->optionsFromApi($this->field->options);
    } else {
      $this->options = $this->optionsFromPageMethod($this->field->page, $this->field->options);
    }

    // sorting
    $this->options = $this->sort($this->options, !empty($this->field->sort) ? $this->field->sort : null);

  }

  public function optionsFromPageMethod($page, $method) {

    if($page && $items = $this->items($page, $method)) {
      $options = array();
      foreach($items as $item) {
        if(is_a($item, 'Page')) {
          $options[$item->uid()] = $item;
        } else if(is_a($item, 'File')) {
          $options[$item->filename()] = $item;
        }
      }
      return $options;
    } else {
      return array();
    }

  }

  public function optionsFromQuery($query) {

    // default query parameters
    $defaults = array(
      'page'     => $this->field->page ? ($this->field->page->isSite() ? '/' : $this->field->page->id()) : '',
      'fetch'    => 'children',
      'value'    => '{{uid}}',
      'flip'     => false,
    );

    // sanitize the query
    if(!is_array($query)) {
      $query = array();
    }

    // merge the default parameters with the actual query
    $query = array_merge($defaults, $query);

    // dynamic page option
    // ../
    // ../../ etc.
    $page    = $this->page($query['page']);
    $items   = $this->items($page, $query['fetch']);
    $options = array();

    // if($query['template']) {
    //   $items = $items->filter(function($item) use($query) {
    //     return in_array(str::lower($item->intendedTemplate()), array_map('str::lower', (array)$query['template']));
    //   });
    // }

    if($query['flip']) {
      $items = $items->flip();
    }

    foreach($items as $item) {
      $value = $this->tpl($query['value'], $item);
      // $text  = $this->tpl($query['text'], $item);

      $options[$value] = $item;
    }

    return $options;

  }

}