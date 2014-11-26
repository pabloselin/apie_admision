<?php
/**
 * Plugin Name: Formulario de solicitud de admisión
 * Plugin URI: http://admision.spm.cl
 * Description: Generador de formulario y almacenamiento de datos para admisión
 * Version: 0.1
 * Author: Pablo Selín Carrasco Armijo
 * Author URI: http://www.apie.cl
 * License: A short license name. Example: GPL2
 */


/*
TODO:
- Poblar base de datos vía ajax
- Crear vista de datos en admin
- Crear correo de aviso para apoderado y admin
*/

global $dbver;
$dbver = '0.4';
$tbname = $wpdb->prefix . 'fspmapdata';

//Tablas de datos
function fspm_table() {
	global $wpdb;
	global $dbver;
	global $tbname;
	$actver = get_option('fspm_dbver');
	$charset_collate = $wpdb->get_charset_collate();
	//Datos a recopilar
	//Nombre apoderado
	//Teléfono
	//Email
	//Nombre alumno
	//Curso postula
	//Fecha de inscripción
	$sql = "CREATE TABLE $tbname (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			apname text NOT NULL,
			alname text NOT NULL,
			apfono text NOT NULL,
			apmail text NOT NULL,
			apextr text NOT NULL,
			cursoi text NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta($sql);
		
		add_option('fspm_dbver', $dbver);
}

function fspm_checkupdate() {
	global $dbver;
	if($dbver != get_site_option('fspm_dbver')) {
		fspm_table();
	}
}

add_action('plugins_loaded', 'fspm_checkupdate');
register_activation_hook( __FILE__, 'fspm_table' );

//Html del formulario
function fspm_form() {
	$nonce = $_POST['prepostnonce'];
	$form = '<form class="form-horizontal" id="fspm_prepostulacion" action="" method="POST">
			<h2>Pre-postulación Seminario Pontificio Menor</h2>
			<!--nonce-->
			'.wp_nonce_field('fspm_prepost', 'prepostnonce').'
			<input type="hidden" name="" value="fspm_prepost" placeholder="">
			<!--formel-->
			<div class="control-group">
				<label class="control-label" for="nombre_apoderado">Nombre apoderado</label>
					<div class="controls">
						<input type="text" name="nombre_apoderado" value="" placeholder="Nombre Apoderado" required>
					</div>
				</div>
			<!--formel-->
			<div class="control-group">
				<label class="control-label" for="fono_apoderado">Teléfono apoderado</label>
				<div class="controls">
					<input type="text" name="fono_apoderado" value="" placeholder="Teléfono Apoderado" required>
				</div>
			</div>
			<!--formel-->
			<div class="control-group">
				<label class="control-label" for="email_apoderado">E-Mail apoderado</label>
				<div class="controls">
					<input type="email" name="email_apoderado" value="" placeholder="Email Apoderado" required>
				</div>
			</div>
			<!--formel-->
			<div class="control-group">
				<label class="control-label" for="nombre_alumno">Nombre alumno</label>
				<div class="controls">
					<input type="text" name="nombre_alumno" value="" placeholder="Nombre alumno" required>
				</div>
			</div>
			<!--formel-->
			<div class="control-group">
				<label class="control-label" for="mensaje_apoderado">Mensaje</label>
				<div class="controls">
					<textarea name="mensaje"></textarea>
				</div>
			</div>
			<!--formel-->
			<div class="control-group">
				<span class="help-block">
					Curso al que le interesa postular
				</span>
				<div class="controls">
					<label class="radio">
						<input type="radio" name="curso" value="pre" default>
						Pre-Kínder
					</label>
					<label class="radio disabled">
						<input type="radio" name="curso" value="kin" disabled>
						Kínder
					</label>
					<label class="radio">
						<input type="radio" name="curso" value="1bas">
						1º Básico
					</label>
					<label class="radio">
						<input type="radio" name="curso" value="2bas">
						2º Básico
					</label>
					<label class="radio">
						<input type="radio" name="curso" value="3bas">
						3º Básico
					</label>
				</div>
			</div>
			<!--submit-->
			<p class="aligncenter">
				<input type="submit" name="Enviar" value="Enviar" placeholder="" class="btn btn-danger btn-lg">
			</p>
		</form>';
		if($nonce){	
			fspm_validate();
		} else {
			return $form;	
		}
		
}



//Insertar datos en tabla
function fspm_putdata($data) {
	global $wpdb;
	global $tbname;
	$insert = $wpdb->insert(
						$tbname,
						array(
							'time'   => current_time('mysql'),
							'apname' => $data['nombre'],
							'alname' => $data['nalumno'],
							'apfono' => $data['fono'],
							'apmail' => $data['email'],
							'apextr' => $data['mensaje'],
							'cursoi' => $data['curso']
							)
						);
	$lastid = $wpdb->insert_id;
}

//Shortcode para el formulario
function fspm_formshortcode($atts) {
	return fspm_form();
}

add_shortcode('fspm_admform', 'fspm_formshortcode');

//shortcode para el botón
function fspm_buttonshortcode($atts) {
	$link = $atts['url'];
	$text = $atts['text'];
	return '<a href="'.$link.'" class="prepostbtn btn btn-lg btn-warning">'.$text.'</a>';
}

add_shortcode('fspm_btnform', 'fspm_buttonshortcode');

//Visualización en admin
function fspm_adminviews() {

}

//Validación
function fspm_validate() {
	if(!wp_verify_nonce( $_POST['prepostnonce'], 'fspm_prepost' )) {
		echo 'nonce inválido';
	} else {
		//Sanitizar
		$data['nombre'] = sanitize_text_field($_POST['nombre_apoderado']);
		$data['fono'] = sanitize_text_field($_POST['fono_apoderado']);
		$data['email'] = sanitize_text_field($_POST['email_apoderado']);
		$data['nalumno'] = sanitize_text_field($_POST['nombre_alumno'] );
		$data['mensaje'] = sanitize_text_field($_POST['mensaje']);
		$data['curso'] = sanitize_text_field($_POST['curso']);
		//Meter en la base de datos
		fspm_putdata($data);
		//Enviar mensaje y correr funciones
		fspm_mails($data);
	}
}

//Envío de correos
function fspm_mails($data) {
	echo $data['nombre'] . ': Inscripción enviada';
}

//Scripts y estilos extras
function fspm_stylesetscripts() {
	if(!is_admin()) {
		wp_register_style( 'fspm', plugins_url('/css/fspm.css', __FILE__) , array(), '1.0', 'screen' );
		wp_register_script( 'fspm', plugins_url('/js/fspm.js', __FILE__), array('jquery'), '1.0', false);
		wp_register_script( 'jqvalidate', plugins_url('/bower_components/jquery-validation/dist/jquery.validate.js', __FILE__), array('jquery'), '1.0', false);
		wp_enqueue_style( 'fspm' );
		wp_enqueue_script( 'fspm' );
		wp_enqueue_script('jqvalidate');
	};
}

add_action('wp_print_scripts', 'fspm_stylesetscripts');