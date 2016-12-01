<?php

function fpost_exitmessages( $exitcode ) {
	/**
	 * Devuelve mensajes según tipo de código de formulario
	 *
	 * 1: Todo OK - Mail e Inscripción
	 * 2: Sólo mail
	 * 3: Sólo inscripción
	 * 4: Nada - Error total
	 */
	
	$options = get_option('apadm_settings');

	$nombre_colegio = $options['apadm_nombre_colegio'];
	$logo_colegio = $options['apadm_logourl'];
	$email_remitente = $options['apadm_email_remitente'];
	$fono_contacto = $options['apadm_fonocontacto'];
	$emailsto = $options['apadm_emailsto'];
	$bccemailsto = $options['apadm_bccemailsto'];
	
	$idinsc = $_GET['idinsc'];

	switch($exitcode):

		case(1):
			
			$message = '<div class="alert alert-success">
						<p style="text-align:center;font-size:32px;"><i class="fa fa-check fa-2x"></i></p>
						<h4 style="font-family: sans-serif;font-size:32px;text-align:center;">Postulación enviada con éxito</h4>
						<p style="text-align:center;">Gracias por postular a '. $nombre_colegio . ', te hemos enviado un correo de confirmación a tu correo (revisa tu bandeja de spam por si acaso...) y te contactaremos vía teléfono o correo en máximo <strong>2 días hábiles</strong> para continuar el proceso.</p></div>';

		break;

		case(2):

			$message = '<div class="alert alert-success">
						<p style="text-align:center;font-size:32px;"><i class="fa fa-check fa-2x"></i></p>
						<h4 style="font-family: sans-serif;font-size:32px;text-align:center;">Postulación enviada con éxito</h4>
						<p style="text-align:center;">Gracias por postular a '. $nombre_colegio . '</p>

						<p>Tu postulación quedó grabada, pero no se pudo enviar un correo de confirmación, te contactaremos vía teléfono o correo en máximo <strong>2 días hábiles</strong> para continuar el proceso.</p>
						<p>Para mayor información por favor contacte al colegio directamente en ' . $email_remitente . '</p>
						</div>';

		break;

		case(3):

			$message = '<div class="alert alert-error"><p><i class="fa fa-times"></i></p><p>Hubo un error en la inscripción, aunque puede haber recibido un mail, su inscripción no quedó grabada, por favor contacte al colegio directamente en ' . $email_remitente . '.</p></div>';

		break;
		case(4):

		default:

		$message = '<div class="alert alert-error"><p><i class="fa fa-times"></i></p><p>Hubo un error en la inscripción, por favor contacte al colegio directamente en ' . $email_remitente . '.</p></div>';

		break;

	endswitch;

	return $message;

}




//Validación
//Añadir esta función por AJAX
function fpost_validate() {
	if(!wp_verify_nonce( $_POST['postulacion_nonce'], 'fpost_prepost' )) {
		
		return 'nonce inválido';

	} else {

		//Sanitizar alumno

		$data['postulacion_year'] = sanitize_text_field( $_POST['postulacion_year'] );
		$data['nombre_alumno'] = sanitize_text_field( $_POST['nombre_alumno'] );
		$data['apellido_alumno'] = sanitize_text_field( $_POST['apellido_alumno'] );

		if($_POST['tipo_documento_alumno'] == 'rut') {

			$data['rut_alumno'] = sanitize_text_field( $_POST['rut_alumno'] );	

		} else {

			$data['otrodoc_alumno'] = sanitize_text_field( $_POST['otrodoc_alumno'] );

		}
		
		$data['alumno_fecha_nacimiento'] = sanitize_text_field( $_POST['alumno_fecha_nacimiento'] );
		
		if(isset($_POST['procedencia_alumno'])):
			$data['procedencia_alumno'] = sanitize_text_field( $_POST['procedencia_alumno'] );
		endif;


		
		if( isset($_POST['otrocurso']) && $_POST['curso_postula'] == 'otro' ) {

			$data['curso_postula'] = sanitize_text_field( $_POST['otrocurso'] );			

		} else {

			$data['curso_postula'] = sanitize_text_field( $_POST['curso_postula'] );

		}
		
		if( isset($_POST['jornada'])) {

			$data['jornada'] = sanitize_text_field( $_POST['jornada'] );

		}
		

		//Sanitizar apoderado

		$data['nombre_apoderado'] = sanitize_text_field( $_POST['nombre_apoderado'] );

		if( $_POST['tipo_documento_apoderado'] == 'rut' ) {

			$data['rut_apoderado'] = sanitize_text_field( $_POST['rut_apoderado'] );	

		} else {

			$data['otrodoc_apoderado'] = sanitize_text_field( $_POST['otrodoc_apoderado'] );

		}
		
		$data['apellido_apoderado'] = sanitize_text_field( $_POST['apellido_apoderado'] );
		$data['fono_apoderado'] = sanitize_text_field( $_POST['fono_apoderado'] );
		$data['fonofijo_apoderado'] = sanitize_text_field( $_POST['fonofijo_apoderado'] );
		$data['email_apoderado'] = sanitize_text_field( $_POST['email_apoderado'] );
		$data['postulacion_mensaje'] = sanitize_text_field( $_POST['postulacion_mensaje'] );
		$data['xtra_apoderado'] = sanitize_text_field( $_POST['xtra_apoderado'] );

		//Meter en la base de datos y redirigir
		
		
		$putdata = fpost_putserialdata($data);

		if( $putdata !== false) {

			$message = fpost_mails($putdata);

			if( $message !== false ) {

				$urlargs = array(
					'excode' => 1,
					'idinsc' => $putdata['ID']
					);

				
				
				$newurl = add_query_arg($urlargs, get_permalink());
				wp_safe_redirect( $newurl, 303 );
				exit;

			} else {

				$urlargs = array(
					'excode' => 3
					);

			}

		}

		// if(wp_redirect( $redirect, 303 )) {
		// 	exit;
		// }

	}
}

function fpost_redirect() {
	/**
	 * Devuelve los formularios dependiendo del POST
	 */
	if(isset($_POST['postulacion_nonce'])) {

		fpost_validate();

	}
}

add_action('wp_loaded', 'fpost_redirect');