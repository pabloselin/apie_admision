//Simple Agent detector
var isMobile = {
    Android: function() {
    return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
    return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
    return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
    return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
    return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
    return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};

//Formulario postulación script

//Añadir método validación de RUT
$.validator.addMethod('rut', function(value, element) {
	return this.optional(element) || $.Rut.validar(value);
}, 'Por favor revise que el RUT esté bien escrito');


$(document).ready(function() {
	var hasJs = $('html').hasClass('js');
	if(hasJs) {
		$('#formulario-postulacion .submitplaceholder').empty().append('<p class="aligncenter"><input type="submit" name="Postular" value="Postular" class="btn btn-danger btn-lg"></p>');
		$('#formulario_consultas .consultas-submitplaceholder').empty().append('<p class="aligncenter"><input type="submit" name="Enviar" value="Enviar" class="btn btn-danger btn-lg"></p>');
	}

	$('#formulario-postulacion').validate({
		debug: false,
		messages: {
			nombre_apoderado: 'Falta nombre de apoderado(a)',
			fono_apoderado: {
				required: 'Falta teléfono apoderado(a)',
				minlength: 'El número telefónico parece ser demasiado corto',
				maxlength: 'El número telefónico parece ser demasiado largo',
				digits: 'Sólo se pueden poner números en este campo'
			},
			email_apoderado: {
				required: 'Falta email apoderado(a)',
				email: 'Por favor introduzca un email válido'	
			},
			nombre_alumno: 'Falta nombre del alumno(a)',
			curso: 'Falta elegir un curso para postular',
			year: 'Falta elegir año al que postula'
		},
		submitHandler: function(form) {
			$('#formulario-postulacion .submitplaceholder').empty().html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando Postulación');
			form.submit();
		},
		rules: {
			email_apoderado: {
				required: true,
				email: true
			},
			rut_alumno: {
				required: true,
				rut: true
			},
			fono_apoderado: {
				required: true,
				minlength: 8,
				maxlength: 8,
				digits: true
			},
			curso: {
				required: true
			},
			year: {
				required: true
			}
		},
		errorPlacement: function(error, element) {
			if(element.attr('name') == 'year') {
				error.appendTo('.error-placement');
			} else if(element.parent().hasClass('input-group')) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		},
		messages: {
			rut: {
				required: 'El RUT es requerido',
				rut:  'Por favor escriba un RUT válido'
			}
		}
	});

	$('#formulario_consultas').validate({
		debug: false,
		messages: {
			nombre_consultas: 'Falta nombre de quién envía',
			fono_consultas: {
				minlength: 'El número telefónico parece ser demasiado corto',
				maxlength: 'El número telefónico parece ser demasiado largo',
				digits: 'Sólo se pueden poner números en este campo'
			},
			email_consultas: {
				required: 'Falta email de contacto',
				email: 'Por favor introduzca un email válido'	
			},
			mensaje_consultas: 'Falta añadir un mensaje',
		},
		submitHandler: function(form) {
			$('#formulario_consultas .consultas-submitplaceholder').empty().html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando Mensaje');
			form.submit();
		},
		rules: {
			email_consultas: {
				required: true,
				email: true
			},
			fono_consultas: {
				minlength: 8,
				maxlength: 8,
				digits: true
			}
		},
		errorPlacement: function(error, element) {
			if(element.parent().hasClass('input-group')) {
				error.insertAfter(element.parent());
				}
			else {
				error.insertAfter(element);
				}
			}
		});

	$('.curso-post input:checked, .year-post input:checked').addClass('selected');

	var otrocurso = $('.otrocurso-control');
	var cursocontrol = $('.curso-control div.radio, .curso-control .help-block');

	$('.curso-post input[type="radio"]').on('click', function(event) {
		$('.curso-post div.radio').removeClass('selected');
		$(this).parent('label').parent('div.radio').addClass('selected');
		if($(this).attr('value') == 'otros') {
			otrocurso.show().addClass('visible');
		} else {
			if(otrocurso.hasClass('visible')) {
				otrocurso.hide().removeClass('visible');
			}
		}
	});

	$('div#success, div#error').modal('show');
	$('div#modal-alert').modal('show');

	//para opción otro curso
	otrocurso.hide();
	//para opcion año
	//cursocontrol.hide();
	


	if(!isMobile.any()) {
		$('.sharing_toolbox a.wa').hide();
	};
});