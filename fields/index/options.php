<?php

namespace Kirby\Panel\Form;

use Collection;
use Str;
use Remote;
use V;

/**
 * Largely based on Kirby\Panel\Form\FieldOptions
 * Modified to only support query/method, and return collection object as array
 * Also add panel edit urls to the resulting array
 */

class IndexFieldOptions {

  public $field;
  public $activepage;
  public $options = array();

  static public function build($field) {
    $obj = new static($field);
    return $obj->format();
  }

  public function __construct($field) {

    $this->field = $field;

    if($this->field->options == 'query') {
      $this->options = $this->optionsFromQuery($this->field->query);
    } else {
      $this->options = $this->optionsFromPageMethod($this->field->page, $this->field->options);
    }

  }

  public function optionsFromPageMethod($page, $method) {
    // set active page (for use within format)
    $this->activepage = $page;

    if($page && $items = $this->items($page, $method)) {
      return $items;
    } else {
      return array();
    }

  }

  public function optionsFromQuery($query) {

    // default query parameters
    $defaults = array(
      'page'     => $this->field->page ? ($this->field->page->isSite() ? '/' : $this->field->page->id()) : '',
      'fetch'    => 'children',
      'flip'     => false,
      'template' => false
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

    if($query['template']) {
      $items = $items->filter(function($item) use($query) {
        return in_array(str::lower($item->intendedTemplate()), array_map('str::lower', (array)$query['template']));
      });
    }

    if($query['flip']) {
      $items = $items->flip();
    }

    // set active page (for use within format)
    $this->activepage = $page;

    return $items;
  }

  public function page($uri) {

    if(str::startsWith($uri, '../')) {
      if($currentPage = $this->field->page) {
        $path = $uri;
        while(str::startsWith($path, '../')) {
          if($parent = $currentPage->parent()) {
            $currentPage = $parent;
          } else {
            $currentPage = site();
          }
          $path = str::substr($path, 3);
        }
        if(!empty($path)) {
          $currentPage = $currentPage->find($path);
        }
        $page = $currentPage;
      } else {
        $page = null;
      }
    } else if($uri == '/') {
      $page = site();
    } else {
      $page = page($uri);
    }

    return $page;

  }

  public function sort($options, $sort) {

    if(empty($sort)) return $options;

    switch(strtolower($sort)) {
      case 'asc':
        asort($options);
        break;
      case 'desc':
        arsort($options);
        break;
    }

    return $options;

  }

  public function items($page, $method) {

    if(!$page) return new Collection();

    switch($method) {
      case 'visibleChildren':
        $items = $page->children()->visible();
        break;
      case 'invisibleChildren':
        $items = $page->children()->invisible();
        break;
      case 'visibleGrandchildren':
        $items = $page->grandChildren()->visible();
        break;
      case 'invisibleGrandchildren':
        $items = $page->grandChildren()->invisible();
        break;
      case 'siblings':
        $items = $page->siblings()->not($page);
        break;
      case 'visibleSiblings':
        $items = $page->siblings()->not($page)->visible();
        break;
      case 'invisibleSiblings':
        $items = $page->siblings()->not($page)->invisible();
        break;
      case 'pages':
        $items = site()->index();
        $items = $items->sortBy('title', 'asc');
        break;
      case 'index':
        $items = $page->index();
        $items = $items->sortBy('title', 'asc');
        break;
      case 'children':
      case 'grandchildren':
      case 'files':
      case 'images':
      case 'documents':
      case 'videos':
      case 'audio':
      case 'code':
      case 'archives':
        $items = $page->{$method}();
        break;
      default:
        $items = new Collection();
    }

    return $items;

  }

  public function format () {
    // add panel edit url to each item
    return array_map(function ($item) {
      if (isset($item['filename'])) {
        $item['panelurl'] = panel()->urls()->index() . '/pages/' . $this->activepage->uri() . '/file/' . $item['filename'] . '/edit';
      } else {
        $item['panelurl'] = panel()->urls()->index() . '/pages/' . $item['uri'] . '/edit';
      }
      return $item;
    }, $this->options->toArray());
  }

}
