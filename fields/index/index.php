<?php

require_once __DIR__ . DS . 'options.php';

class IndexField extends BaseField {

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

  public function __construct () {
    $this->rows = 10;
    $this->order = 'asc';
    $this->type = 'index';
    $this->options = [];
    $this->icon = false;
  }

  public function routes () {
    return array(
      array(
        'pattern' => 'list',
        'method'  => 'get',
        'action'  => 'list_entries'
      )
    );
  }

  public function subpagelinks () {
    if (in_array($this->options, ['children', 'visibleChildren', 'invisibleChildren'])) {
      $hrefEdit = $this->page->url('subpages');
      $hrefAdd = $this->page->url('add');
      $addAttribute = 'data-modal="true"';
    } else if (in_array($this->options, ['files', 'images', 'documents', 'videos', 'audio', 'code', 'archives'])) {
      $hrefEdit = $this->page->url('files');
      $hrefAdd = '#upload';
      $addAttribute = 'data-upload';
    } else if (in_array($this->options, ['query']) && array_key_exists('fetch', $this->query) && in_array($this->query['fetch'], ['children', 'visibleChildren', 'invisibleChildren'])) {
      if (array_key_exists('page', $this->query)) {
        $options = new Kirby\Panel\Form\IndexFieldOptions($this);
        $page = $options->activePage();
      } else {
        $page = $this->page;
      }

      if($page){
        $hrefEdit = $page->url('subpages');
        $hrefAdd = $page->url('add');
        $addAttribute = 'data-modal="true"';
      } else {
        $addAttribute = 0;
      }
      
    } else {
      $addAttribute = 0;
    }

    if (is_string($addAttribute)) {
      return <<<HTML
        <span class="hgroup-options shiv shiv-dark shiv-left">
          <span class="hgroup-option-right">
            <a href="{$hrefEdit}" title="Edit">
              <i class="icon icon-left fa fa-pencil"></i><span>Edit</span>
            </a>
            <a href="{$hrefAdd}" title="+" shortcut="+" {$addAttribute}>
              <i class="icon icon-left fa fa-plus-circle"></i><span>Add</span>
            </a>
          </span>
        </span>
HTML;
    }
  }

  public function label () {
    if (!$this->label) return null;
    
    $subpagelinks = '';
    if (isset($this->options) && $subpagelinks = $this->subpagelinks()) {
      if (!(isset($this->addedit) && !$this->addedit)) {
        $subpagelinks = $subpagelinks;
      }
    }

    return <<<HTML
      <label class="label" for="{$this->id()}">
        <h2 class="hgroup hgroup-single-line hgroup-compressed cf">
          <span class="hgroup-title">{$this->i18n($this->label)}</span>
          {$subpagelinks}
        </h2>
      </label>
HTML;
  }

  public function columns () {
    return !empty($this->columns) ? $this->columns : [ 'title' => 'Title' ];
  }

  public function content () {
    return tpl::load(__DIR__ . DS . 'template.php', array('field' => $this));
  }

  public function url ($action) {
    return purl($this->model(), 'field/' . $this->name() . '/index/' . $action);
  }

  public function validate () {
    return true;
  }

}