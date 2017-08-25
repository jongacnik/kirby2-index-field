# Kirby Index Field

Kirby field which displays pages (or files) as a [datatable](https://datatables.net/).

This is useful for navigating large-ish sets of items with filtering and sorting. Enables Kirby to be used a little more like a database. Pairs nicely with [kirby-hidebar-field](https://github.com/jongacnik/kirby-hidebar-field).

![Preview](https://github.com/jongacnik/kirby-index-field/blob/master/preview.png?raw=true)

## Examples

Show an index of pages children:

```yaml
pageindex:
  label: Items
  type: index
  options: children
```

Show an index of pages files:

```yaml
pageindex:
  label: Items
  type: index
  options: files
```

## Options

Similar to Kirby's [Select Field](https://getkirby.com/docs/cheatsheet/panel-fields/select), you use the `options` parameter to specify what the index should be populated with. All Dynamic Options from the Select Field can be used:

Option | Description
--- | ---
children  | Index of all children
visibleChildren | Index of all visible children
invisibleChildren | Index of all invisible children
grandchildren | Index of all grandchildren
visibleGrandchildren | Index of all visible grandchildren
invisibleGrandchildren | Index of all invisible grandchildren
siblings | Index of all siblings
visibleSiblings | Index of all visible siblings
invisibleSiblings | Index of all invisible siblings
index | Index of all descendants
pages | Index of all pages of the site
files | Index of all files of the page
images | Index of all images of the page
documents | Index of all documents of the page
videos | Index of all videos of the page
audio | Index of all audio files of the page
code | Index of all code files of the page
archives | Index of all archives of the page

## Query

The Index Field also supports a version of the Select Field `query` parameter to generate more complex indexes of pages/files:

```yaml
pageindex:
  label: Items
  type: index
  options: query
  query:
    page: blog
    fetch: children
    template: article
```

Where `page` is the [uid](https://getkirby.com/docs/cheatsheet/page/uid) of the desired page, and `fetch` is one of the [`options`](#options) above.

## Columns

By default, only a Title column (showing a page `title` or file `filename`) will be shown. You specify what columns should appear using the `columns` parameter. The first column determines the initial sort.

```yaml
pageindex:
  label: Items
  type: index
  options: children
  columns:
    title: Title
    date: Date
    uid: Slug
```

The key specifies which property of the [page/file json](https://getkirby.com/docs/cheatsheet/page/to-json) should be fetched. The value specifies the title of the column. The following example will show a column labeled **Title** and the property `title` from each item's json representation:

```yaml
title: Title
```

### Column Options:

You can set a few options on a per column basis.

#### Column Width

```yaml
columns:
  title:
    label: Title
    width: 100
  uid: Slug
```

#### Column Class

```yaml
columns:
  title:
    label: Title
    class: classname
  uid: Slug
```

#### Column Sort-ability

```yaml
columns:
  title:
    label: Title
    sort: false
  uid: Slug
```

#### Column Visibility

```yaml
columns:
  title:
    label: Title
    visible: false
  uid: Slug
```

## Filter Data

A custom filter can be applied to the data before it is put out as a json response. This is perfect if you need to modify some of the data for presentation, change columns, etc.

Create a simple plugin `site/plugins/mydatafilters/mydatafilters.php`:
```php
<?php

class MyDataFilters {
  static function myfilterfunc($data) {
    // filter data here
    return $data;
  }
}
```

Update field definition:
```yaml
pageindex:
  label: Items
  type: index
  options: children
  filter: MyDataFilters::myfilterfunc
  columns:
    title: Title
    date: Date
    uid: Slug
```

## Rows

Set the initial number of rows in the datatable:

```yaml
rows: 25
```

## Order

Set the initial sort order of the first column:

```yaml
order: desc
```

## Add/Edit Links

When showing an index of subpages (`children`, `visibleChildren` or `invisibleChildren`), Edit/Add links will appear next to the field label. You can disable these links using the `addedit` parameter:

```yaml
addedit: false
```

These links appear on an index of subpages so you are able to [hide the subpage](https://getkirby.com/docs/panel/blueprints/subpages-settings#hide-subpages) list in the left column of the panel, or [remove the left column](https://github.com/jongacnik/kirby-hidebar-field) entirely.
