//Localización mensajes
(function( factory ) {
	if ( typeof define === "function" && define.amd ) {
		define( ["jquery", "../jquery.validate"], factory );
	} else {
		factory( jQuery );
	}
}(function( $ ) {

/*
 * Translated default messages for the jQuery validation plugin.
 * Locale: ES (Spanish; Español)
 */
$.extend($.validator.messages, {
	required: "Este campo es obligatorio.",
	remote: "Por favor, rellena este campo.",
	email: "Por favor, escribe una dirección de correo válida.",
	url: "Por favor, escribe una URL válida.",
	date: "Por favor, escribe una fecha válida.",
	dateISO: "Por favor, escribe una fecha (ISO) válida.",
	number: "Por favor, escribe un número válido.",
	digits: "Por favor, escribe sólo dígitos.",
	creditcard: "Por favor, escribe un número de tarjeta válido.",
	equalTo: "Por favor, escribe el mismo valor de nuevo.",
	extension: "Por favor, escribe un valor con una extensión aceptada.",
	maxlength: $.validator.format("Por favor, no escribas más de {0} caracteres."),
	minlength: $.validator.format("Por favor, no escribas menos de {0} caracteres."),
	rangelength: $.validator.format("Por favor, escribe un valor entre {0} y {1} caracteres."),
	range: $.validator.format("Por favor, escribe un valor entre {0} y {1}."),
	max: $.validator.format("Por favor, escribe un valor menor o igual a {0}."),
	min: $.validator.format("Por favor, escribe un valor mayor o igual a {0}."),
	nifES: "Por favor, escribe un NIF válido.",
	nieES: "Por favor, escribe un NIE válido.",
	cifES: "Por favor, escribe un CIF válido."
});

}));

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
	var otrocurso = $('.otrocurso-control');
	var hasJs = $('html').hasClass('js');

	if(hasJs) {
		$('#formulario-postulacion .submitplaceholder').empty().append('<p class="aligncenter"><input type="submit" name="Postular" value="Postular" class="btn btn-danger btn-lg"></p>');
		$('#formulario_consultas .consultas-submitplaceholder').empty().append('<p class="aligncenter"><input type="submit" name="Enviar" value="Enviar" class="btn btn-danger btn-lg"></p>');
	}

	//para opción otro curso
	otrocurso.hide();

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
			rut_apoderado: {
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


	$('select#curso_postula').on('change', function(event) {
		if($('option:selected', this).attr('value') == 'otro') {
			otrocurso.show().addClass('visible');
		} else {
			if(otrocurso.hasClass('visible')) {
				otrocurso.hide().removeClass('visible');
			}
		}
	});

	$('div#success, div#error').modal('show');
	$('div#modal-alert').modal('show');	
	

	if(!isMobile.any()) {
		$('.sharing_toolbox a.wa').hide();
	};

	var siteurl = 'http://web.dev/cms-admision';

	//console.log($('body[data-url="' + siteurl + '"]'));

	//Tracking analytics
	$('body[data-url="'+ siteurl + '"] div.hl__botones-recordatorio btn.formulario, body[data-url="'+ siteurl + '"] div.hl__accion a, body[data-url="'+ siteurl + '"] div.hl__como-postular a.link-formulario-en-paso, body[data-url="' +  siteurl + '"] .single .btn-accion').on('click', function(event) {
			var datalabel = $(this).data('label');

			//Se gatilla el click solo si está en url oficial
			if(siteurl == 'http://admision.ciademariaseminario.cl') {
				ga('send', 'event', 'Links "Cómo postular"', 'click', datalabel)
			} else {
				console.log($(this).data('label'));
			}
	});
});
