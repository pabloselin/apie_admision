$(document).ready(function() {
	
	$('table.postulaciones-frontend-table').dynatable({
		inputs: {
			paginationPrev: 'Anterior ',
			paginationNext: 'Siguiente ',
			perPageText: 'Mostrar ',
			recordCountText: 'Mostrando '
		}
	});

	$('button.prevmessage').on('click', function() {

		var contentmsg = $('textarea[name="mensaje_contacto_segunda_etapa"]').val();

		$('pre.fill-textarea-repeat').empty().html(contentmsg);

	});

});