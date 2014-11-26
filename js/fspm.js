//Fspm script
$(document).ready(function() {
	function spmSubmit(form) {
		$.ajax()
	};
	$('#fspm_prepostulacion').validate({
		messages: {
			nombre_apoderado: 'Falta nombre de apoderado',
			fono_apoderado: 'Falta teléfono apoderado',
			email_apoderado: 'Falta email apoderado',
			nombre_alumno: 'Falta nombre alumno',
			curso: 'Falta elegir un curso para postular'
		}
		submitHandler: function(form) {
			$(form).submit();
		}
		rules: {
			email_apoderado: {
				required: true,
				email: true
			}
		}
	});
});
