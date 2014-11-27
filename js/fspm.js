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
			nombre_apoderado: 'Falta nombre de apoderado',
			fono_apoderado: 'Falta teléfono apoderado',
			email_apoderado: 'Falta email apoderado',
			nombre_alumno: 'Falta nombre alumno',
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
			}
		}
	});

	if(!isMobile.any()) {
		$('.sharing_toolbox a.wa').hide();
	};
});
