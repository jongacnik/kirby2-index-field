$(document).on('ajaxComplete ready', function () {
	if ($('[data-indexfield-data]').length && !$.fn.DataTable.isDataTable('[data-indexfield-data]') ) {
    console.log($('[data-indexfield-data]').data('indexfield-data'))
		$('[data-indexfield-data]')
			.DataTable({
				data : $('[data-indexfield-data]').data('indexfield-data')
			})
	}
})