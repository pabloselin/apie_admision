//Check mobile stuff
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

//Fspm script
$(document).ready(function() {
	$('#fspm_prepostulacion').validate({
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
			curso: 'Falta elegir un curso para postular'
		},
		submitHandler: function(form) {
			$('#fspm_prepostulacion input[type="submit"]').empty().html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando Postulación');
			console.log('enviando');
			form.submit();
		},
		rules: {
			email_apoderado: {
				required: true,
				email: true
			},
			fono_apoderado: {
				required: true,
				minlength: 8,
				maxlength: 8,
				digits: true
			}
		}
	});

	$('.curso-post input:checked').addClass('selected');

	var otrocurso = $('.otrocurso-control');

	$('.curso-post input[type="radio"]').on('click', function(event) {
		$('.curso-post label').removeClass('selected');
		$(this).parent('label').addClass('selected');
		if($(this).attr('value') == 'otros') {
			otrocurso.show().addClass('visible');
		} else {
			if(otrocurso.hasClass('visible')) {
				otrocurso.hide().removeClass('visible');
			}
		}
	});

	$('.year-post input[type="radio"]').on('click', function(event) {
		$('.year-post label').removeClass('selected');
		$(this).parent('label').addClass('selected');
	});

	//para opción otro curso
	otrocurso.hide();


	if(!isMobile.any()) {
		$('.sharing_toolbox a.wa').hide();
	};
});
