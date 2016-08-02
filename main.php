<?php
/**
 * Plugin Name: Formulario de postulación para colegios
 * Plugin URI: http://apie.cl
 * Description: Generador de formulario y almacenamiento de datos para admisión
 * Version: 0.5
 * Author: Pablo Selín Carrasco Armijo - A Pie
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
$dbver = '1.6';
$tbname = $wpdb->prefix . 'postulaciones';


//Crear directorios
define( 'FPOST_CSVPATH', WP_CONTENT_DIR . '/postulaciones/');
define( 'FPOST_CSVURL', WP_CONTENT_URL . '/postulaciones/');
//Variables de mails y nombres
define( 'FPOST_NCOLEGIO', 'Colegio Compañía de María Apoquindo');
define( 'FPOST_FROMMAIL', 'admision@ciademaria.cl');
define( 'FPOST_FONO', '+562 236 359 00');
//Prefijo para algunas cosas
define( 'FPOST_PREFIX', 'cma_');
 
//Cambia los mails según.
if(get_bloginfo('url') == 'http://admision.ciademaria.cl'):

	define( 'FPOST_TOMAILS', 'pablo@apie.cl, jorgeloayza@gmail.com, admision@ciademaria.cl, pablobravo@apie.cl');

else:
	define( 'FPOST_TOMAILS', 'pabloselin@gmail.com, jorgeloayza@gmail.com');
endif;

define( 'FPOST_LOGO', plugins_url( 'img/logo_cma.png', __FILE__ ) );

if(!is_dir(FPOST_CSVPATH)){
	mkdir(WP_CONTENT_DIR . '/postulaciones', 0755);
}


//admin page
include( plugin_dir_path( __FILE__ ) . 'admin.php' );

//la parte de las consultas
include( plugin_dir_path( __FILE__ ) . 'consultas.php' );

//Tablas de datos
function fpost_table() {
	global $wpdb;
	global $dbver;
	global $tbname;
	$actver = get_option('fpost_dbver');
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
	global $tbname;
	$inscritos = $wpdb->get_results("SELECT * FROM $tbname WHERE type LIKE 'postulacion'");
	return $inscritos;
} 

//Llamar inscritos y devolver un array
function fpost_getconsultas() {
	global $wpdb;
	global $tbname;
	$consultas = $wpdb->get_results("SELECT * FROM $tbname WHERE type LIKE 'consulta'");
	return $consultas;
} 


//Html del formulario
function fpost_form() {
	ob_start();
	include plugin_dir_path( __FILE__ ) . '/parts/postulacion-form.php';
	return ob_get_clean();
}

//Insertar datos en tabla
function fpost_putserialdata($data) {
	global $wpdb;
	global $tbname;
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
	$okmess = '<div class="alert alert-success">
						<p style="text-align:center;font-size:32px;"><i class="fa fa-check fa-2x"></i></p>
						<h4 style="font-family: sans-serif;font-size:32px;text-align:center;">Postulación enviada con éxito</h4>
						<p style="text-align:center;">Gracias por postular a '. FPOST_NCOLEGIO . ', te hemos enviado un correo de confirmación a <strong>'.$data['email_apoderado'].'</strong> (revisa tu bandeja de spam por si acaso...) y te contactaremos vía teléfono o correo en máximo <strong>2 días hábiles</strong> para continuar el proceso.</p></div>
						</div>';
	$errmess = '<div class="alert alert-error"><p><i class="fa fa-times"></i></p><p>Hubo un error en la inscripción, por favor contacte al colegio directamente en ' . FPOST_FROMMAIL . '.</p></div>';
	if($lastid) {
		$message = $okmess;
		$message .=  '<div class="modal fade" id="success" role="dialog" tabindex="-1" aria-labelledby="Inscripción Exitosa en '.FPOST_NCOLEGIO.'" aria-hidden="true">';
		$message .= '<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						'.$okmess.'
						<div class="modal-footer">
        				<button type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
      				</div>
					</div>
					 
				</div>
			</div>';
	} else {
		$message = $errmess;
		$message .= '<div class="modal fade" id="error" role="dialog" tabindex="-1" aria-labelledby="Error en la Inscripción">
			<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					'.$errmess.'
				</div>
				<div class="modal-footer">
        				<button type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
      				</div>
			</div>
			</div>
			</div>';
	}
	//Enviar mensaje y correr funciones
	$data['ID'] = $lastid;
	$data['timestamp'] = $timestamp;
	$message .= fpost_mails($data);

	return $message;
}


//Insertar datos en tabla
function fpost_putdata($data) {
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
							'cursoi' => $data['curso'],
							'otrocurso' => $data['otrocurso'],
							'year' => $data['year']
							)
						);
	$lastid = $wpdb->insert_id;
	$okmess = '<div class="alert alert-success">
						<p style="text-align:center;font-size:32px;"><i class="fa fa-check fa-2x"></i></p>
						<h4 style="font-family: sans-serif;font-size:32px;text-align:center;">Postulación enviada con éxito</h4>
						<p style="text-align:center;">Gracias por postular a '. FPOST_NCOLEGIO . ', te hemos enviado un correo de confirmación a <strong>'.$data['email'].'</strong> (revisa tu bandeja de spam por si acaso...) y te contactaremos vía teléfono o correo en máximo <strong>2 días hábiles</strong> para continuar el proceso.</p></div>
						</div>';
	$errmess = '<div class="alert alert-error"><p><i class="fa fa-times fa-2x"></i></p><p>Hubo un error en la inscripción, por favor contacte al colegio directamente en ' . FPOST_FROMMAIL .'.</p></div>';
	if($lastid) {
		$message = $okmess;
		$message .=  '<div class="modal fade" id="success" role="dialog" tabindex="-1" aria-labelledby="Inscripción Exitosa en '.FPOST_NCOLEGIO.'" aria-hidden="true">';
		$message .= '<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						'.$okmess.'
						<div class="modal-footer">
        				<button type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
      				</div>
					</div>
					 
				</div>
			</div>';
	} else {
		$message = $errmess;
		$message .= '<div class="modal fade" id="error" role="dialog" tabindex="-1" aria-labelledby="Error en la Inscripción">
			<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					'.$errmess.'
				</div>
				<div class="modal-footer">
        				<button type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
      				</div>
			</div>
			</div>
			</div>';
	}
	//Enviar mensaje y correr funciones
	$message .= fpost_mails($data);

	return $message;
}

//Shortcode para el formulario
function fpost_formshortcode($atts) {
	return fpost_form();
}

add_shortcode('formulario_postulacion', 'fpost_formshortcode');

function fpost_shareshortcode($atts) {
	global $post;
	$soctitle = get_post_meta($post->ID, 'rw_titulosocial', true);   
    $share['whatsapp'] = '<a target="_blank" href="whatsapp://send?text='.$post->post_title.' ' . get_permalink($post->ID).'" class="wa" title="Enviar por WhatsApp"><span class="fa-stack">
  				<i class="fa fa-circle-o fa-stack-1x"></i>
  				<i class="fa fa-phone fa-stack-1x"></i>
			</span></i></a>';
    $share['facebook'] = '<a target="_blank" class="fb" href="https://facebook.com/sharer.php?u='.get_permalink($post->ID).'" class="facebook"><i class="fa fa-facebook"></i></a>';
    //$share['twitter'] = '<a target="_blank" href="https://twitter.com/intent/tweet?url='.get_permalink($post->ID).'&text='.urlencode($soctitle).'" class="twt"><i class="fa fa-twitter"></i></a>';
    $share['gmas'] = '<a target="_blank" href="https://plus.google.com/share?url='.get_permalink($post->ID).'" class="gpl"><i class="fa fa-google-plus"></i></a>';
    $share = implode(' ', $share);
    $share = '<div class="sharing_toolbox">'.$share.'</div>';
    return $share;
}

add_shortcode('fpost_share', 'fpost_shareshortcode');

//shortcode para el botón
function fpost_buttonshortcode($atts) {
	$link = $atts['url'];
	$text = $atts['text'];
	return '<a href="'.$link.'" class="prepostbtn btn btn-lg btn-warning">'.$text.'</a>';
}

add_shortcode('fpost_btnform', 'fpost_buttonshortcode');


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
		$data['rut_alumno'] = sanitize_text_field( $_POST['rut_alumno'] );
		$data['alumno_fecha_nacimiento'] = sanitize_text_field( $_POST['alumno_fecha_nacimiento'] );
		$data['procedencia_alumno'] = sanitize_text_field( $_POST['procedencia_alumno'] );
		$data['curso_postula'] = sanitize_text_field( $_POST['curso_postula'] );

		//Sanitizar apoderado

		$data['nombre_apoderado'] = sanitize_text_field( $_POST['nombre_apoderado'] );
		$data['rut_apoderado'] = sanitize_text_field( $_POST['rut_apoderado'] );
		$data['apellido_apoderado'] = sanitize_text_field( $_POST['apellido_apoderado'] );
		$data['fono_apoderado'] = sanitize_text_field( $_POST['fono_apoderado'] );
		$data['fonofijo_apoderado'] = sanitize_text_field( $_POST['fonofijo_apoderado'] );
		$data['email_apoderado'] = sanitize_text_field( $_POST['email_apoderado'] );
		$data['postulacion_mensaje'] = sanitize_text_field( $_POST['postulacion_mensaje'] );
		$data['xtra_apoderado'] = sanitize_text_field( $_POST['xtra_apoderado'] );

		//Meter en la base de datos
		$output = fpost_putserialdata($data);
		return $output;
	}
}



function fpost_cursequi($curso, $otro = NULL) {
	//transforma los valores de curso en valores legibles
	switch($curso) {
		case('pk'):
			$lcurso = 'Pre-Kinder';
		break;
		case('k'):
			$lcurso = 'Kinder';
		break;
		case('1'):
			$lcurso = '1º Básico';
		break;
		case('2'):
			$lcurso = '2º Básico';
		break;
		case('3'):
			$lcurso = '3º Básico';
		break;
		case('4'):
			$lcurso = '4º Básico';
		break;
		case('5'):
			$lcurso = '5º Básico';
		break;
		case('6'):
			$lcurso = '6º Básico';
		break;
		case('7'):
			$lcurso = '7º Básico';
		break;
		case('8'):
			$lcurso = '8º Básico';
		break;
		case('9'):
			$lcurso = 'Iº Medio';
		break;
		case('10'):
			$lcurso = 'IIº Medio';
		break;
		case('jardin'):
			$lcurso = 'Jardín';
		break;
		case('otro'):
			$lcurso = $data['otrocurso'];
		default:
			$lcurso = $curso;
		break;	
	}
	return $lcurso;
}
//Envío de correos

function fpost_content_type_html() {
	return 'text/html';
}

function fpost_content_type_plain() {
	return 'text/plain';
}

//Envío de correos
function fpost_mails($data) {
	$mensajeapoderado = '<style>table p {line-height:1,4em;}</style>
		<table align="center" width="600" cellspacing="0" cellpadding="20" style="font-family:sans-serif;font-size:14px;border:1px solid #ccc;">
		<tr>
			<td style="background-color:white;color:#333;">
				<p style="text-align:center;"><img src="'.FPOST_LOGO.'" alt="'.FPOST_NCOLEGIO.'"><br><h1 style="font-family:sans-serif;font-size:28px;font-weight:normal;text-align:center;color:#1A7CAF;">'.FPOST_NCOLEGIO.'</h1></p>
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
							<p><strong>Fecha de Nacimiento:</strong>' . $data['alumno_fecha_nacimiento'] . '</p>
							<p><strong>RUT Alumno: </strong>' . $data['rut_alumno'] .'</p>
							<p><strong>Curso al que postula: </strong>' . fpost_cursequi($data['curso_postula']) .'</p>
							<p><strong>Año al que postula: </strong>' . $data['postulacion_year'] . '</p>
							<p>&nbsp;</p>
							<h4 style="text-align:center;font-size:22px;font-weight:normal;">Datos del apoderado</h4>
							<p><strong>Nombre Apoderado(a): </strong>' . $data['nombre_apoderado'] . ' ' . $data['apellido_apoderado'] .'</p>
							<p><strong>RUT apoderado: </strong>' . $data['rut_apoderado'] .'</p>
							<p><strong>Teléfono Apoderado(a): </strong> +56 9 ' . $data['fono_apoderado'] . '</p>';

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
				<strong>'.FPOST_NCOLEGIO.'</strong></p>
				<p><strong>Correo: </strong> '.FPOST_FROMMAIL.' <br>
				<strong>Teléfono: </strong> <a href="tel:'.FPOST_FONO.'">'.FPOST_FONO.'</a>  <br>
				<strong>Web: </strong><a href="'.get_bloginfo('url').'">'.get_bloginfo('url').'</a></p>
				';

	$mensajeapoderado .=	'</td>
							</tr>
						</table>';
	$mensajeadmin = '
					<table align="center" width="600" cellspacing="0" cellpadding="20" style="font-family:sans-serif;font-size:14px;background-color:white;border:1px solid #ccc;">
					<tr>
						<td style="background-color:white;color:#333;">
							<p style="text-align:center;"><img src="'.FPOST_LOGO.'" alt="'.FPOST_NCOLEGIO.'"><br></p>

							<h1 style="font-family:sans-serif;font-size:28px;font-weight:normal;text-align:center;color:#1A7CAF;">'.FPOST_NCOLEGIO.'</h1>

							<h3 style="text-align:center;font-size:18px;font-weight:normal;">Se ha enviado una postulación a ' . FPOST_NCOLEGIO . ' para el año '. $data['postulacion_year'] .'</h3>
						</td> 
					</tr>
					
					<tr>
						<td>
							<h4>Datos</h4>
							<p><strong>Nombre Apoderado(a): </strong>' . $data['nombre_apoderado'] . ' ' . $data['apellido_apoderado'] . '</p>
							<p><strong>Teléfono Apoderado(a): </strong>+56 9 ' . $data['fono_apoderado'] . '</p>';

				if($data['fonofijo_apoderado']):
						$mensajeadmin .= '<p><strong>Teléfono Fijo Apoderado(a): </strong>+56 2 ' . $data['fonofijo_apoderado'] . '</p>';
					endif;

$mensajeadmin .=	'<p><strong>E-Mail Apoderado(a): </strong>' . $data['email_apoderado'] . '</p>
							<p><strong>RUT apoderado: </strong>' . $data['rut_apoderado'] .'</p>

							
						</td>

					</tr>
					<tr>
						<td>
						<h4>Datos del Alumno</h4>
							<p><strong>Curso al que postula: </strong>' . fpost_cursequi($data['curso_postula']) .'</p>
							<p><strong>Nombre al Alumno(a): </strong>' .$data['nombre_alumno']. ' ' . $data['apellido_alumno'] . ' </p>
							<p><strong>RUT Alumno: </strong>' . $data['rut_alumno'] .'</p>
							<p><strong>Fecha de Nacimiento:</strong>' . $data['alumno_fecha_nacimiento'] . '</p>
							<p><strong>Año al que postula: </strong>' . $data['postulacion_year'] . '</p>
						</td>
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
	$admins = FPOST_TOMAILS;
	
	$headers[] = 'From: "'.FPOST_NCOLEGIO.'" <'.FPOST_FROMMAIL.'>';
	$headers[] = 'Sender: "' . FPOST_NCOLEGIO . ' <'.FPOST_FROMMAIL.'>';
	$headers[] = 'Reply-To: "' . $data['nombre_apoderado'] . ' ' . $data['apellido_apoderado']. ' <' . $data['email_apoderado'] . '>';
	
	add_filter('wp_mail_content_type', 'fpost_content_type_html');

	$mailapoderado = wp_mail( $data['email_apoderado'], 'Postulación ' . FPOST_NCOLEGIO, $mensajeapoderado, $headers);
	$mailadmin = wp_mail( $admins, 'Postulación '. FPOST_NCOLEGIO , $mensajeadmin, $headers);

	add_filter('wp_mail_content_type', 'fpost_content_type_plain');

	if($mailapoderado && $mailadmin) {
		return '<div class="alert alert-success"><i class="fa fa-check"></i> <i class="fa fa-envelope"></i></div>';
	} else {
		return '<div class="alert alert-error"><i class="fa fa-times"></i> <i class="fa fa-envelope"></i></div>';
	}
}

//Scripts y estilos extras
function fpost_styleandscripts() {
	if(!is_admin()) {
		wp_register_style( 'postulacion', plugins_url('/css/postulacion.css', __FILE__), 'screen', array() );
		
		wp_register_script( 'modernizr', plugins_url('/lib/modernizr/modernizr.js', __FILE__ ), array(), '3.2.0', false);
		wp_register_script( 'funciones-postulacion', plugins_url('/js/funciones-postulacion.js', __FILE__), array('jqvalidate', 'pickadate'), '1.0', false);
		wp_register_script( 'jquery-rut', plugins_url('/js/jquery.rut.min.js', __FILE__ ), array(), '0.5', false);
		wp_register_script( 'jqvalidate', plugins_url('/lib/jquery-validation/dist/jquery.validate.min.js', __FILE__), array('jquery-rut'), '1.14.0', false);

		wp_register_script( 'pickadate', plugins_url('/lib/pickadate/lib/picker.js', __FILE__ ) , array(), '4.0.0', false );
		wp_register_script( 'pickadate-date', plugins_url('/lib/pickadate/lib/picker.date.js', __FILE__ ) , array('pickadate'), '4.0.0', false );
		wp_register_style( 'pickadate-classic', plugins_url( '/lib/pickadate/lib/themes/default.css', __FILE__ ));
		wp_register_style( 'pickadate-classic-date', plugins_url( '/lib/pickadate/lib/themes/default.date.css', __FILE__ ));
		
		wp_enqueue_script( 'jquery-rut' );
		wp_enqueue_script( 'modernizr' );
		wp_enqueue_script( 'funciones-postulacion' );
		wp_enqueue_script( 'jqvalidate' );
		wp_enqueue_script( 'pickadate' );
		wp_enqueue_script( 'pickadate-date' );
		wp_enqueue_style( 'postulacion' );
		wp_enqueue_style( 'pickadate-classic' );
		wp_enqueue_style( 'pickadate-classic-date' );
	};
}

add_action('wp_print_scripts', 'fpost_styleandscripts');