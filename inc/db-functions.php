<?php

//Tablas de datos
function fpost_table() {
	global $wpdb;
	global $dbver;

	$tbname = $wpdb->prefix . FPOST_TABLENAME;

	$actver = get_option('fpost_dbver');
	$charset_collate = $wpdb->get_charset_collate();
	
	//id: ID
	//time: fecha de inscripción
	//type: tipo de envío (consulta o postulación)
	//data: los datos enviados
	$sql = "CREATE TABLE $tbname (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			type text NOT NULL,
			data text NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta($sql);
		
		add_option('fpost_dbver', $dbver);
}

function fpost_checkupdate() {
	global $dbver;
	if(!get_site_option('fpost_dbver') || $dbver != get_site_option('fpost_dbver')) {
		fpost_table();
	}
}

add_action('plugins_loaded', 'fpost_checkupdate');
register_activation_hook( __FILE__, 'fpost_table' );


//Llamar inscritos y devolver un array
function fpost_getdata() {
	global $wpdb;
	
	$tbname = $wpdb->prefix . FPOST_TABLENAME;

	$inscritos = $wpdb->get_results("SELECT * FROM $tbname WHERE type LIKE 'postulacion'");
	return $inscritos;
}

function fpost_getpostulacion( $postulacion_id ) {
	/**
	 * Devuelve una postulación a partir del id
	 */
	
	global $wpdb;

	$tbname = $wpdb->prefix . FPOST_TABLENAME;

	$inscrito = $wpdb->get_row("SELECT * FROM $tbname WHERE type LIKE 'postulacion' AND id LIKE $postulacion_id");

	return $inscrito;

}

//Llamar inscritos y devolver un array
function fpost_getconsultas() {
	global $wpdb;
	
	$tbname = $wpdb->prefix . FPOST_TABLENAME;

	$consultas = $wpdb->get_results("SELECT * FROM $tbname WHERE type LIKE 'consulta'");
	return $consultas;
} 


//Insertar datos en tabla
function fpost_putserialdata($data) {
	global $wpdb;
	
	$tbname = $wpdb->prefix . FPOST_TABLENAME;

	$timestamp = current_time('mysql');
	$insert = $wpdb->insert(
						$tbname,
						array(
							'time'   => $timestamp,
							'type' => 'postulacion',
							'data' => serialize($data)
							)
						);
	$lastid = $wpdb->insert_id;

	//Enviar mensaje y correr funciones
	$data['ID'] = $lastid;
	$data['timestamp'] = $timestamp;
	$message = fpost_mails($data);

	if( $message == true && $lastid ) {

		$excode = 1;

	} elseif( $message != true && $lastid) {

		$excode = 2;

	} elseif ( $message == true && !$lastid ) {

		$excode = 3;

	} else {

		$excode = 4;

	}

	$urlargs = array(
		'excode' => $excode,
		'idinsc' => $lastid
		);

	$exiturl = add_query_arg( $urlargs, get_permalink() );

	return $exiturl;
}