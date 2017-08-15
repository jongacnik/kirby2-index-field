# Kirby Index Field

Kirby field which displays pages (or files) as a [datalist](https://datatables.net/).

This is useful for navigating large sets of pages with filtering and sorting. Enables Kirby to be used a little more like a database. Pairs nicely with [kirby-hidebar-field](https://github.com/jongacnik/kirby-hidebar-field).

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
  columns:
    name: Name
```

### Options

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

### Query

The Index Field even supports a version of the Select Field `query` parameter to generate more complex indexes of pages/files:

```yaml
pageindex:
  label: Items
  type: index
  options: query
  query:
    page: blog
    fetch: children
```

Where `page` is the [uid](https://getkirby.com/docs/cheatsheet/page/uid) of the desired page, and `fetch` is one of the [`options`](#options) above.

### Columns

By default, only a Title column (showing a pages title) will be shown. You specify what columns should appear using the `columns` parameter:

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

The key maps directly to [page](https://getkirby.com/docs/cheatsheet#page) and [file](https://getkirby.com/docs/cheatsheet#file) methods. The value will be the title of the column. For example: 

```yaml
title: Title
```

Will show a column labeled **Title** and the result of `$page->title()` for each item in the index.

#### Snippet Values

For more advanced output, you can also specify the contents of a row cell using a snippet, like in **Modified** below:

```yaml
columns:
  title: Title
  modified:
    label: Modified
    snippet: index/modified
```

```php
<?php echo $entry->modified('d/m/Y H:i') ?>
```

Use the `$entry` variable in your snippet to access the page or file object.

### Rows

Set the initial number of rows in the datalist by using the `rows` parameter:

```yaml
rows: 25
```

### Add/Edit Links

When showing an index of subpages (`children`, `visibleChildren` or `invisibleChildren`), Edit/Add links will appear next to the field label. You can disable these links using the `addedit` parameter:

```yaml
addedit: false
```

These links appear on an index of subpages so you are able to [hide the subpage](https://getkirby.com/docs/panel/blueprints/subpages-settings#hide-subpages) list in the left column of the panel, or [remove the left column](https://github.com/jongacnik/kirby-hidebar-field) entirely.