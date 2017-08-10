# Kirby Index Field

**⚠️ Work in progress...**

Kirby field which displays pages as a [datalist](https://datatables.net/).

This is useful for navigating large sets of subpages which need filtering and sorting. Allows Kirby to be used a little more like a database. Pairs nicely with [kirby-hidebar-field](https://github.com/jongacnik/kirby-hidebar-field)

![Preview](https://github.com/jongacnik/kirby-index-field/blob/master/preview.jpg?raw=true)

## Usage

```yaml
pageindex:
  label: Subpage Index
  type: index
  options: children
  columns:
    title: Title
    date: Date
  editlinks: true
```

- Use `options`/`query` from the [select field](https://getkirby.com/docs/cheatsheet/panel-fields/select)
- `columns` keys can be any field method
- `editlinks` shows Edit/Add links so you can hide the page list in the left column

## Todo

- [x] Add Option: rows
- [ ] Link rows to page Edit
- [ ] Improve Edit/Add (only works for parent page atm)
- [ ] Design