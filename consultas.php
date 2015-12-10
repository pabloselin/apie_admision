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
	$insert = $wpdb->insert(
		$tbname,
		array(
			'time' => current_time('mysql'),
			'type' => 'consulta',
			'data' => serialize($data)
			)
		);
	$lastid = $wpdb->insert_id;
	if($lastid){
		$mensaje = '<div id="success">Gracias por enviar su mensaje, nos pondremos en contacto con usted a la brevedad.</div>';
	} else {
		$mensaje = '<div id="error">Hubo un error enviando el mensaje.</div>';
	}
	return $mensaje;
}

function fpost_consultas_mails($data) {

}