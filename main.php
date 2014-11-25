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

$dbver = '0.1';

//Tablas de datos
function fspm_table() {
	global $wpdb, $dbver;
	$actver = get_option('fspm_dbver');
	//Datos a recopilar
	//Nombre apoderado
	//Teléfono
	//Email
	//Nombre alumno
	//Curso postula
	//Fecha de inscripción
	$tbname = $wpdb->prefix . 'fspmapdata';
	if($dbver != $actver) {
		$sql = "CREATE TABLE $tbname (id mediumint(9) NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			nombre_apoderado text NOT NULL,
			fono_apoderado text NOT NULL,
			email_apoderado text NOT NULL,
			extra_apoderado text NOT NULL
			UNIQUE_KEY id(id))";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta($sql);
		update_option('fspm_dbver', $dbver);
	}
}

register_activation_hook( __FILE__, 'fspm_table' );

function fspm_checkupdate() {
	global $dbver;
	if($dbver != get_site_option('fspm_dbver')) {
		fspm_table();
	}
}

//Html del formulario
function fspm_form() {
	$form = '<form class="form-horizontal" id="fspm_prepostulacion" action="'.admin_url('admin-ajax.php').'" method="POST">
			<h2>Pre-postulación Seminario Pontificio Menor</h2>
			<!--nonce-->
			'.wp_nonce_field('fspm_prepost', 'prepostnonce').'
			<input type="hidden" name="" value="fspm_prepost" placeholder="">
			<!--formel-->
			<div class="control-group">
				<label class="control-label" for="nombre_apoderado">Nombre apoderado</label>
					<div class="controls">
						<input type="text" name="nombre_apoderado" value="" placeholder="Nombre Apoderado">
					</div>
				</div>
			<!--formel-->
			<div class="control-group">
				<label class="control-label" for="fono_apoderado">Teléfono apoderado</label>
				<div class="controls">
					<input type="text" name="fono_apoderado" value="" placeholder="Teléfono Apoderado">
				</div>
			</div>
			<!--formel-->
			<div class="control-group">
				<label class="control-label" for="email_apoderado">E-Mail apoderado</label>
				<div class="controls">
					<input type="text" name="email_apoderado" value="" placeholder="Email Apoderado">
				</div>
			</div>
			<!--formel-->
			<div class="control-group">
				<label class="control-label" for="nombre_alumno">Nombre alumno</label>
				<div class="controls">
					<input type="text" name="nombre_alumno" value="" placeholder="Nombre alumno">
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
						<input type="radio" name="curso" value="pre">
						Pre-Kínder
					</label>
					<label class="radio">
						<input type="radio" name="curso" value="kin">
						Kínder
					</label>
					<label class="radio">
						<input type="radio" name="curso" value="bas">
						Básica
					</label>
					<label class="radio">
						<input type="radio" name="curso" value="med">
						Media
					</label>
				</div>
			</div>
			<!--submit-->
			<p class="aligncenter">
				<input type="submit" name="Enviar" value="Enviar" placeholder="" class="btn btn-danger btn-lg">
			</p>
		</form>';
	return $form;
}



//Insertar datos en tabla
function fspm_putdata() {
	global $wpdb;
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
	$data['nombre'] = $_POST['nombre_apoderado'];
	$data['fono'] = $_POST['fono_apoderado'];
	$data['email'] = $_POST['email_apoderado'];
	$data['mensaje'] = $_POST['mensaje'];
	$data['curso'] = $_POST['curso'];
}

//Envío de correos
function fspm_mails() {

}

//Scripts y estilos extras
function fspm_stylesetscripts() {
	if(!is_admin()) {
		wp_register_style( 'fspm', plugins_url('/css/fspm.css', __FILE__) , array(), '1.0', 'screen' );
		wp_register_script( 'fspm', plugins_url('/js/fspm.js', __FILE__), array('jquery'), '1.0', false);
		wp_enqueue_style( 'fspm' );
		wp_enqueue_script( 'fspm' );
	};
}

add_action('wp_print_scripts', 'fspm_stylesetscripts');