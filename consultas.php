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

	if(!wp_verify_nonce( $_POST['consultas_nonce'], 'fpost_consultas' )) {
		return 'nonce inválido';
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
	global $wpdb, $tbname;
	$s_data = serialize($data);
	$insert = $wpdb->insert(
		$tbname,
		array(
			'time' => current_time('mysql'),
			'type' => 'consulta',
			'data' => $s_data
			)
		);
	$lastid = $wpdb->insert_id;

	//Mando el mail de consultas
	
	
	if($lastid) {
		$output = fpost_consultas_mails($data);
	} else {
		$output = 'Error en el registro';
	}

	
	return $output;
}

function fpost_consultas_mails($data) {

	$headers[] = 'From: "'.FPOST_NCOLEGIO.'" <'.FPOST_FROMMAIL.'>';

	$headersapoderado[] = 'From: "'.FPOST_NCOLEGIO.'" <'.FPOST_FROMMAIL.'>';
	$headersapoderado[] = 'Reply-to: "' . $data['nombre_consultas'] . '<' . $data['email_consultas'] . '>';

	$mailapoderado = $data['email_consultas'];
	$mailadmins = FPOST_TOMAILS;

	$mensajeapoderado = 'Su consulta se envió exitosamente. Muchas gracias por contactarse con nosotros.';
	$mensajeadmin = '
		<p>Alguien envió un mail de consulta en ' . FPOST_NCOLEGIO . '</p>
		<p><strong>Nombre:</strong>' . $data['nombre_consultas']. '</p>
		<p><strong>Mensaje:</strong></p>
		<p>' . $data['mensaje_consultas'] . '</p>
		<p><strong>Teléfono:</strong>' . $data['fono_consultas']. '</p>
		<p><strong>Email:</strong>' . $data['email_consultas']. '</p>';

	add_filter('wp_mail_content_type', 'fpost_content_type_html');

	$enviadorapoderado = wp_mail( $mailapoderado, 'Consulta en ' . FPOST_NCOLEGIO, $mensajeapoderado, $headersapoderado);

	$enviadoradmins = wp_mail( $mailadmins, 'Consulta en '. FPOST_NCOLEGIO , $mensajeadmin, $headers);

	add_filter('wp_mail_content_type', 'fpost_content_type_plain');

	if($enviadorapoderado && $enviadoradmins) {
		$tmess = 'Mensaje enviado exitosamente';
		$mensaje = '<p class="text-center text-success"><i class="fa fa-4x fa-check"></i></p><p class="text-center text-success">Gracias por enviar su mensaje, nos pondremos en contacto con usted a la brevedad.</p>';
		$inlinemess = '<div class="alert alert-success">
						<p>Gracias por enviar su mensaje, nos pondremos en contacto con usted a la brevedad</p>
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