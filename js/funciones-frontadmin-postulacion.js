$(document).ready(function() {
	
	$('table.postulaciones-frontend-table').dynatable({
		inputs: {
			paginationPrev: 'Anterior ',
			paginationNext: 'Siguiente ',
			perPageText: 'Mostrar ',
			recordCountText: 'Mostrando '
		}
	});

});