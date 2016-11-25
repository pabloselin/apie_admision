<?php 
// Sección de consultas

//Shortcode para el formulario
function fpost_consultas($atts) {
	return fpost_consultasform();
}

add_shortcode( 'formulario_consultas', 'fpost_consultas' );

//Html del formulario
function fpost_consultasform() {
	ob_start();
		include plugin_dir_path( __FILE__ ) . '/parts/consultas-form.php';
	return ob_get_clean();
}

//Validador de consultas
function fpost_consultasvalidate() {
	/**
	 * Validador de consulta
	 */

	if(!wp_verify_nonce( $_POST['consultas_nonce'], 'fpost_consultas' ) && $data['email_falso'] == '') {
		return '<p>Nonce inválido o spam detectado</p>';
	} else {
		$data['nombre_consultas'] = sanitize_text_field( $_POST['nombre_consultas'] );
		$data['fono_consultas'] = sanitize_text_field( $_POST['fono_consultas'] );
		$data['email_consultas'] = sanitize_text_field( $_POST['email_consultas'] );
		$data['mensaje_consultas'] = sanitize_text_field( $_POST['mensaje_consultas'] );
		$output = fpost_putserialdata_consultas($data);
		return $output;
	}
}

function fpost_putserialdata_consultas($data) {
	global $wpdb;

	$tbname = $wpdb->prefix . FPOST_TABLENAME;
	$s_data = serialize($data);
	$timestamp = current_time('mysql');
	$insert = $wpdb->insert(
		$tbname,
		array(
			'time' => $timestamp,
			'type' => 'consulta',
			'data' => $s_data
			)
		);
	$lastid = $wpdb->insert_id;

	//Mando el mail de consultas
	//Agrego el ID a los datos
	$data['ID'] = $lastid;

	//Agrego la fecha y hora a los datos
	$data['timestamp'] = $timestamp;

	if($lastid) {
		$output = fpost_consultas_mails($data);
	} else {
		$output = 'Error en el registro';
	}

	
	return $output;
}

function fpost_consultas_mails($data) {

	$options = get_option('apadm_settings');
	$nombre_colegio = $options['apadm_nombre_colegio'];
	$logo_colegio = $options['apadm_logourl'];
	$email_remitente = $options['apadm_email_remitente'];
	$fono_contacto = $options['apadm_fono_contacto'];
	$emailsto = $options['apadm_emailsto'];
	$bccemailsto = $options['apadm_bccemailsto'];


	$headers['From'] = 'From: "' . $nombre_colegio . '"<'. $email_remitente .'>';
	$headers['Sender'] = 'Sender: "' . $nombre_colegio . ' <'. $email_remitente .'>';
	$headers['Reply-To'] = 'Reply-To:' . $data['email_consultas'];

	$extramails = explode( ',', $bccemailsto );

	foreach($extramails as $extramail):

		$headers[] = 'Bcc: ' . $extramail;

	endforeach;


	$headersapoderado['From'] = 'From: "'.$nombre_colegio.'" <'. $email_remitente .'>';

	$mailapoderado = $data['email_consultas'];
	$mailadmins = $emailsto;

	$mensajeapoderado = '<style>table p {line-height:1,4em;}</style>
		<table align="center" width="600" cellspacing="0" cellpadding="20" style="font-family:sans-serif;font-size:14px;border:1px solid #ccc;">
		<tr>
			<td style="background-color:white;color:#333;">
				<p style="text-align:center;"><img src="'. $logo_colegio .'" alt="'.$nombre_colegio.'"><br><h1 style="font-family:sans-serif;font-size:28px;font-weight:normal;text-align:center;color:#1A7CAF;">'.$nombre_colegio.'</h1></p>
				<h3 style="text-align:center;font-size:18px;font-weight:normal;">Consulta enviada en ' . $nombre_colegio . '</h3>
			</td> 
		</tr>
			<tr>
				<td>
					<p>Estimado/a, hemos recibido exitosamente su consulta. Nos pondremos en contacto con usted vía teléfono o correo.</p>
					
				</td>
			</tr>
			<tr>
				<td>
				<p>Muchas gracias por su interés.<br>
				Afectuosamente<br>
				<strong>'.$nombre_colegio.'</strong></p>
				<p><strong>Correo: </strong> '. $email_remitente .' <br>
				<strong>Teléfono: </strong> <a href="tel:'.$fono_contacto.'">'.$fono_contacto.'</a>  <br>
				<strong>Web: </strong><a href="'.get_bloginfo('url').'">'.get_bloginfo('url').'</a></p>
				</td>
				</tr>
			</table>';


		$mensajeadmin = '<style>table p {line-height:1,4em;}</style>
		<table align="center" width="600" cellspacing="0" cellpadding="20" style="font-family:sans-serif;font-size:14px;border:1px solid #ccc;">
		<tr>
			<td style="background-color:white;color:#333;">
				<p style="text-align:center;"><img src="'.$logo_colegio.'" alt="'.$nombre_colegio.'"><br><h1 style="font-family:sans-serif;font-size:28px;font-weight:normal;text-align:center;color:#1A7CAF;">'.$nombre_colegio.'</h1></p>
				<h3 style="text-align:center;font-size:18px;font-weight:normal;">Consulta enviada en ' . $nombre_colegio . '</h3>
			</td> 
		</tr>
			<tr>
				<td>
					<p>Alguien envío un correo de consultas a través del formulario del sitio de admisión en ' . $nombre_colegio .'.</p>
					
				</td>
			</tr>
			<tr>
				<td>
				<p><strong>Nombre:</strong>' . $data['nombre_consultas']. '</p>
				<p><strong>Mensaje:</strong></p>
				<p>' . $data['mensaje_consultas'] . '</p>
				<p><strong>Teléfono:</strong> <a href="+56 9 ' . $data['fono_consultas'] . '">+56 9 ' . $data['fono_consultas']. '</a></p>
				<p><strong>Email:</strong>' . $data['email_consultas']. '</p>
				<p><strong>ID Consulta:</strong> ' . $data['ID']. '</p>
				<p><strong>Fecha y hora de envío: </strong>' . mysql2date( 'j F, G:i', $data['timestamp'] ) .'</p>
				</td>
				</tr>
			</table>';

	add_filter('wp_mail_content_type', 'fpost_content_type_html');

	$enviadorapoderado = wp_mail( $mailapoderado, 'Consulta en ' . $nombre_colegio, $mensajeapoderado, $headersapoderado);

	$enviadoradmins = wp_mail( $mailadmins, 'ID: ' . $data['ID'] . ' - Consulta en '. $nombre_colegio, $mensajeadmin, $headers);

	

	add_filter('wp_mail_content_type', 'fpost_content_type_plain');

	if($enviadorapoderado && $enviadoradmins) {
		$tmess = 'Mensaje enviado exitosamente';
		$mensaje = '<p class="text-center text-success"><i class="fa fa-4x fa-check"></i></p><p class="text-center text-success">Gracias por enviarnos tu consulta. Te contactaremos a la brevedad</p>';
		$inlinemess = '<div class="alert alert-success">
						<p>Gracias por enviarnos tu consulta. Te contactaremos a la brevedad.</p>
					</div>';
	} else {
		$tmess = 'Error en el envío';
		$mensaje = '<p class="text-danger text-center"><i class="fa fa-4x fa-times"></i></p><p class="text-danger text-center">Hubo un error enviando el mensaje.</p>';
		$inlinemess = '<div class="alert alert-danger">
						<p>Hubo un error enviando su mensaje, por favor escriba directamente a admision@ciademariaseminario.cl.</p>
					</div>';
	}

	$output = '<div id="modal-alert" class="modal fade" tabindex="-1" role="dialog">
					  <div class="modal-dialog">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
					        <h4 class="modal-title">' . $tmess .'</h4>
					      </div>
					      <div class="modal-body">
					        ' . $mensaje . '
					      </div>
					    </div><!-- /.modal-content -->
					  </div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
					'. $inlinemess .'
					';

	return $output;
}