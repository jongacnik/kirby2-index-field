$(document).on('ajaxComplete ready', function () {
  $indexes = $('[data-indexfield-data]')
  if ($indexes.length) {
    $indexes.each(function () {
      if (!$.fn.DataTable.isDataTable(this)) {
        var data = $(this).data('indexfield-data')
        var rows = $(this).data('indexfield-rows')
        var table = $(this).DataTable({
          data: data,
          pageLength: rows,
          columnDefs: [
            {
              targets: [ -1 ],
              visible: false,
              searchable: false
            }
          ]
        })

        $(this).on('click', 'tbody tr', function () {
          var editurl = table.row(this).data().slice(-1)[0] 
          app.content.open(editurl)
        })
      }
    })
  }
});