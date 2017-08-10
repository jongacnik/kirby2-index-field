$(document).on('ajaxComplete ready', function () {
  $indexes = $('[data-indexfield-data]')
  if ($indexes.length) {
    $indexes.each(function () {
      if (!$.fn.DataTable.isDataTable(this)) {
        var data = $(this).data('indexfield-data')
        var rows = $(this).data('indexfield-rows')
        $(this).DataTable({
          data: data,
          pageLength: rows
        })
      }
    })
  }
})