<?php

function fpost_content_type_html() {
	return 'text/html';
}

function fpost_content_type_plain() {
	return 'text/plain';
}

//Envío de correos
function fpost_mails($data) {
	
	add_filter('wp_mail_content_type', 'fpost_content_type_html');

	$mailapoderado = fpost_mailapoderado( $data );
	$mailadmin = fpost_mailadmin( $data );


	add_filter('wp_mail_content_type', 'fpost_content_type_plain');

	if($mailapoderado == true && $mailadmin == true) {

		return true;

	} else {

		return false;

	}
}



function fpost_mailadmin($data) {

	$options = get_option('apadm_settings');
	$nombre_colegio = $options['apadm_nombre_colegio'];
	$logo_colegio = $options['apadm_logourl'];
	$email_remitente = $options['apadm_email_remitente'];
	$fono_contacto = $options['apadm_fonocontacto'];
	$emailsto = $options['apadm_emailsto'];
	$bccemailsto = $options['apadm_bccemailsto'];


		$f_fono_apoderado = '+56 9 ' . $data['fono_apoderado'];

		if( isset($data['fonofijo_apoderado']) ):

			$f_fonofijo_apoderado = '+56 2 ' . $data['fonofijo_apoderado'];

		endif;

		$mensajeadmin = '';
		$mensajeadmin .= '<table align="center" width="600" cellspacing="0" cellpadding="20" style="font-family:sans-serif;font-size:14px;background-color:white;border:1px solid #ccc;">
					<tr>
						<td style="background-color:white;color:#333;">
							<p style="text-align:center;"><img src="'. $logo_colegio .'" alt="'. $nombre_colegio .'"><br></p>

							<h1 style="font-family:sans-serif;font-size:28px;font-weight:normal;text-align:center;color:#1A7CAF;">'. $nombre_colegio .'</h1>

							<h3 style="text-align:center;font-size:18px;font-weight:normal;">Se ha enviado una postulación a ' .  $nombre_colegio  . ' para el año '. $data['postulacion_year'] .'</h3>
						</td> 
					</tr>
					
					<tr>
						<td>
							<h4>Datos</h4>
							<p><strong>Nombre Apoderado(a): </strong>' . $data['nombre_apoderado'] . ' ' . $data['apellido_apoderado'] . '</p>
							<p><strong>Teléfono Apoderado(a): </strong> <a href="tel:' . $f_fono_apoderado . '">' . $f_fono_apoderado . '</a> </p>';

				if($data['fonofijo_apoderado']):
					
						$mensajeadmin .= '<p><strong>Teléfono Fijo Apoderado(a): </strong> <a href="tel:' . $f_fonofijo_apoderado . '">' . $f_fonofijo_apoderado . '</a></p>';

				endif;

			$mensajeadmin .=	'<p><strong>E-Mail Apoderado(a): </strong>' . $data['email_apoderado'] . '</p>';

			if( isset($data['rut_apoderado']) ) {

				$mensajeadmin .= '<p><strong>RUT apoderado: </strong>' . $data['rut_apoderado'] .'</p>';

			} else {

				$mensajeadmin .= '<p><strong>Doc. Identificación Apoderado: </strong>' . $data['otrodoc_apoderado'] .'</p>';

			}
			

							
			$mensajeadmin .= '</td>

					</tr>
					<tr>
						<td>
						<h4>Datos del Alumno</h4>
							<p><strong>Curso al que postula: </strong>' . fpost_cursequi($data['curso_postula']) .'</p>';

			if( isset($data['jornada']) ):

					$mensajeadmin .= '<p><strong>Preferencia de jornada: </strong>' . fpost_formatjornada($data['jornada']) . '</p>';

			endif;


			$mensajeadmin .= '<p><strong>Nombre al Alumno(a): </strong>' .$data['nombre_alumno']. ' ' . $data['apellido_alumno'] . ' </p>';


			if( isset($data['rut_alumno']) ) {

				$mensajeadmin .= '<p><strong>RUT Alumno: </strong>' . $data['rut_alumno'] .'</p>';

			} else {

				$mensajeadmin .= '<p><strong>Doc. Identificación Alumno: </strong>' . $data['otrodoc_alumno'] .'</p>';
			}

			


			$mensajeadmin .= '<p><strong>Fecha de Nacimiento:</strong>' . $data['alumno_fecha_nacimiento'] . '</p>
							<p><strong>Año al que postula: </strong>' . $data['postulacion_year'] . '</p>';

			if( isset($data['procedencia_alumno']) ) {

				$mensajeadmin .= '<p><strong>Jardín o colegio del cual proviene:</strong>: ' . $data['procedencia_alumno'].'</p>';

			}

			$mensajeadmin .= '</td>
					</tr>	
					<tr>
						<td>
						<h4>Datos adicionales</h4>

							<p><strong>Consulta adicional: </strong>' .$data['postulacion_mensaje'].'</p>
							<p><strong>Como se enteró del colegio: </strong>' .$data['xtra_apoderado'].'</p>
							<p><strong>Fecha y hora de envío: </strong>' . mysql2date( 'j F, G:i', $data['timestamp'] ) .'</p>
							<p><strong>Número identificador (ID): </strong>' .$data['ID'].'</p>
						</td>
					</tr>	
					</table>
					';
	$admins = $emailsto;

	$headers['From'] = 'From: "'. $nombre_colegio .'" <'. $email_remitente .'>';	

	$extramails = explode(',', $bccemailsto);
	
	foreach($extramails as $extramail):

		$headers[] = 'Bcc: ' . $extramail;

	endforeach;

	$headers['Sender'] = 'Sender: "' .  $nombre_colegio  . ' <'. $email_remitente .'>';
	$headers['Reply-To'] = 'Reply-To: "' . $data['nombre_apoderado'] . ' ' . $data['apellido_apoderado']. ' <' . $data['email_apoderado'] . '>';
	

	$mailadmin = wp_mail( $admins, 'ID: ' . $data['ID'] . ' - Postulación '.  $nombre_colegio , $mensajeadmin, $headers);

	return $mailadmin;

}

function fpost_mailapoderado( $data ) {

	$options = get_option('apadm_settings');
	$nombre_colegio = $options['apadm_nombre_colegio'];
	$logo_colegio = $options['apadm_logourl'];
	$email_remitente = $options['apadm_email_remitente'];
	$fono_contacto = $options['apadm_fonocontacto'];
	$emailsto = $options['apadm_emailsto'];

	$mensajeapoderado = '<style>table p {line-height:1,4em;}</style>
		<table align="center" width="600" cellspacing="0" cellpadding="20" style="font-family:sans-serif;font-size:14px;border:1px solid #ccc;">
		<tr>
			<td style="background-color:white;color:#333;">
				<p style="text-align:center;"><img src="'. $logo_colegio .'" alt="'. $nombre_colegio .'"><br><h1 style="font-family:sans-serif;font-size:28px;font-weight:normal;text-align:center;color:#1A7CAF;">'. $nombre_colegio .'</h1></p>
				<h3 style="text-align:center;font-size:18px;font-weight:normal;">Confirmación de postulación para el año '.fpost_parseyear($data['postulacion_year']).'</h3>
			</td> 
		</tr>
			<tr>
				<td>
					<p>Estimado/a <strong>'. $data['nombre_apoderado'] .'</strong>, hemos recibido exitosamente su postulación. Nos pondremos en contacto con usted vía teléfono o correo en <strong>2 días hábiles</strong> como máximo para continuar el proceso.</p>
					<p>Estos son los datos que usted envió:</p>
				</td>
			</tr>
			<tr>
						<td style="border-width:1px 0 1px 0;border-style:dotted;border-color:#ccc;background-color:white;">
							<h4 style="text-align:center;font-size:22px;font-weight:normal;">Datos del alumno</h4>
							<p><strong>Nombre Alumno(a): </strong>' .$data['nombre_alumno']. ' ' . $data['apellido_alumno'] . '</p>
							<p><strong>Fecha de Nacimiento:</strong>' . $data['alumno_fecha_nacimiento'] . '</p>';

							if( isset($data['rut_alumno']) ){

								$mensajeapoderado .= '<p><strong>RUT Alumno: </strong>' . $data['rut_alumno'] .'</p>';

							} else {

								$mensajeapoderado .= '<p><strong>Doc. Identificación Alumno: </strong>' . $data['otrodoc_alumno'] .'</p>';
							}
							

							$mensajeapoderado .= '<p><strong>Curso al que postula: </strong>' . fpost_cursequi($data['curso_postula']) .'</p>';

							if( isset($data['jornada']) ) :

								$mensajeapoderado .= '<p><strong>Preferencia de jornada: </strong>' . fpost_formatjornada($data['jornada']) . '</p>';

							endif;


							$mensajeapoderado .= '<p><strong>Año al que postula: </strong>' . $data['postulacion_year'] . '</p>
							<p>&nbsp;</p>
							<h4 style="text-align:center;font-size:22px;font-weight:normal;">Datos del apoderado</h4>
							<p><strong>Nombre Apoderado(a): </strong>' . $data['nombre_apoderado'] . ' ' . $data['apellido_apoderado'] .'</p>';

							if( isset($data['rut_apoderado'])) {

								$mensajeapoderado .= '<p><strong>RUT apoderado: </strong>' . $data['rut_apoderado'] .'</p>';

							} else {

								$mensajeapoderado .= '<p><strong>Doc. Identificación apoderado: </strong>' . $data['otrodoc_apoderado'] .'</p>';
							}

							

							$mensajeapoderado .= '<p><strong>Teléfono Apoderado(a): </strong> +56 9 ' . $data['fono_apoderado'] . '</p>';

						if($data['fonofijo_apoderado']):

							$mensajeapoderado .= '<p><strong>Teléfono Fijo Apoderado(a): </strong>+56 2 ' . $data['fonofijo_apoderado'] . '</p>';

						endif;

							$mensajeapoderado .= '<p><strong>E-Mail Apoderado(a): </strong>' . $data['email_apoderado'] . '</p>
							<p>&nbsp;</p>

							<h4 style="text-align:center;font-size:22px;font-weight:normal;">Otros datos</h4>

							<p><strong>Consulta adicional: </strong>' .$data['postulacion_mensaje'].'</p>
							<p><strong>Como se enteró del colegio: </strong>' .$data['xtra_apoderado'].'</p>
							<p><strong>Fecha y hora de envío: </strong>' . mysql2date( 'j F, G:i', $data['timestamp'] ) .'</p>
							<p><strong>Número identificador (ID): </strong>' .$data['ID'].'</p>

						</td>
					</tr>';

	$mensajeapoderado .= '<tr>
				<td>
				<p>Muchas gracias por su interés.<br>
				Afectuosamente<br>
				<strong>'. $nombre_colegio .'</strong></p>
				<p><strong>Correo: </strong> '. $email_remitente .' <br>
				<strong>Teléfono: </strong> <a href="tel:'. $fono_contacto .'">'. $fono_contacto .'</a>  <br>
				<strong>Web: </strong><a href="'.get_bloginfo('url').'">'.get_bloginfo('url').'</a></p>
				';

	$mensajeapoderado .=	'</td>
							</tr>
						</table>';

	$headers[] = 'From: "'. $nombre_colegio .'" <'. $email_remitente .'>';
	$headers[] = 'Sender: "' .  $nombre_colegio  . ' <'. $email_remitente .'>';
	$headers[] = 'Reply-To: "' . $data['nombre_apoderado'] . ' ' . $data['apellido_apoderado']. ' <' . $data['email_apoderado'] . '>';

	

	$mailapoderado = wp_mail( $data['email_apoderado'], 'Postulación ' .  $nombre_colegio , $mensajeapoderado, $headers);

	return $mailapoderado;
}