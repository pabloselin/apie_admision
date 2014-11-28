<?php
/**
 * Plugin Name: Formulario de solicitud de admisión
 * Plugin URI: http://admision.spm.cl
 * Description: Generador de formulario y almacenamiento de datos para admisión
 * Version: 0.2
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
$dbver = '0.5';
$tbname = $wpdb->prefix . 'fspmapdata';


//Crear directorios
define( 'FSPM_CSVPATH', WP_CONTENT_DIR . '/spmcsv/');
define( 'FSPM_CSVURL', WP_CONTENT_URL . '/spmcsv/');

if(!is_dir(FSPM_CSVPATH)){
	mkdir(WP_CONTENT_DIR . '/spmcsv', 0755);
}


//admin page
include( plugin_dir_path( __FILE__ ) . 'admin.php');

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
	if(!get_site_option('fspm_dbver') || $dbver != get_site_option('fspm_dbver')) {
		fspm_table();
	}
}

add_action('plugins_loaded', 'fspm_checkupdate');
register_activation_hook( __FILE__, 'fspm_table' );

//Llamar inscritos y devolver un array
function fspm_getdata() {
	global $wpdb;
	global $tbname;
	$inscritos = $wpdb->get_results("SELECT * FROM $tbname");
	return $inscritos;
} 

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
				<label class="control-label" for="nombre_apoderado">Nombre apoderado(a)</label>
					<div class="controls">
						<input type="text" name="nombre_apoderado" value="" placeholder="Nombre Apoderado(a)" required>
					</div>
				</div>
			<!--formel-->
			<div class="control-group">
				<label class="control-label" for="fono_apoderado">Celular apoderado(a)</label>
				<div class="controls">
					<div class="input-prepend">
						<span class="add-on">+56 9</span>
						<input type="text" name="fono_apoderado" value="" placeholder="" required>
					</div>
				</div>
			</div>
			<!--formel-->
			<div class="control-group">
				<label class="control-label" for="email_apoderado">E-Mail apoderado(a)</label>
				<div class="controls">
					<input type="email" name="email_apoderado" value="" placeholder="Email Apoderado(a)" required>
				</div>
			</div>
			<!--formel-->
			<div class="control-group">
				<label class="control-label" for="nombre_alumno">Nombre alumno(a)</label>
				<div class="controls">
					<input type="text" name="nombre_alumno" value="" placeholder="Nombre alumno(a)" required>
				</div>
			</div>
			<!--formel-->
			<div class="control-group">
				<label class="control-label" for="mensaje_apoderado">Consulta u observación especial</label>
				<div class="controls">
					<textarea name="mensaje"></textarea>
				</div>
			</div>
			<!--formel-->
			<div class="control-group">
				<span class="help-block">
					Curso a postular
				</span>
				<div class="controls curso-post">
					<label class="radio">
						<input type="radio" name="curso" value="pre" default>
						<span class="lname">Pre-Kínder</span>
						<div class="alert alert-warning">Nuevo tercer Pre-Kínder, plazo hasta <strong>jueves 11 de diciembre</strong></div>
					</label>
					<label class="radio disabled">
						<input type="radio" name="curso" value="kin" disabled>
						<span class="lname">Kínder (completo)</span>
					</label>
					<label class="radio">
						<input type="radio" name="curso" value="1bas">
						<span class="lname">1º Básico</span>
					</label>
					<label class="radio">
						<input type="radio" name="curso" value="2bas">
						<span class="lname">2º Básico</span>
					</label>
					<label class="radio">
						<input type="radio" name="curso" value="3bas">
						<span class="lname">3º Básico</span>
					</label>
				</div>
			</div>
			<!--submit-->
			<p class="aligncenter">
				<input type="submit" name="Postular" value="Postular" placeholder="" class="btn btn-danger btn-lg">
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
	if($lastid) {
		echo '<div class="alert alert-success"><p style="text-align:center;"><i class="fa fa-4x fa-smile-o"></i></p><h4>Postulación enviada exitosamente</h4><p style="text-align:center;">Gracias por postular a Colegio Seminario Pontificio Menor, te hemos enviado un correo de confirmación a <strong>'.$data['email'].'</strong> y te contactaremos vía teléfono en máximo <strong>1 día hábil</strong> para continuar el proceso.</p></div>';
	} else {
		echo '<div class="alert alert-error"><p><i class="fa fa-meh-o fa-4x"></i></p><p>Hubo un error en la inscripción, por favor contacte al colegio directamente.</p></div>';
	}
}

//Shortcode para el formulario
function fspm_formshortcode($atts) {
	return fspm_form();
}

add_shortcode('fspm_admform', 'fspm_formshortcode');

function spm_shareshortcode($atts) {
	global $post;
	$soctitle = get_post_meta($post->ID, 'rw_titulosocial', true);   
    $share['whatsapp'] = '<a target="_blank" href="whatsapp://send?text='.$post->post_title.' ' . get_permalink($post->ID).'" class="wa" title="Enviar por WhatsApp"><span class="fa-stack">
  				<i class="fa fa-circle-o fa-stack-1x"></i>
  				<i class="fa fa-phone fa-stack-1x"></i>
			</span></i></a>';
    $share['facebook'] = '<a target="_blank" class="fb" href="https://facebook.com/sharer.php?u='.get_permalink($post->ID).'" class="facebook"><i class="fa fa-facebook"></i></a>';
    $share['twitter'] = '<a target="_blank" href="https://twitter.com/intent/tweet?url='.get_permalink($post->ID).'&text='.urlencode($soctitle).'" class="twt"><i class="fa fa-twitter"></i></a>';
    $share['gmas'] = '<a target="_blank" href="https://plus.google.com/share?url='.get_permalink($post->ID).'" class="gpl"><i class="fa fa-google-plus"></i></a>';
    $share = implode(' ', $share);
    $share = '<div class="sharing_toolbox">'.$share.'</div>';
    return $share;
}

add_shortcode('spm_share', 'spm_shareshortcode');

//shortcode para el botón
function fspm_buttonshortcode($atts) {
	$link = $atts['url'];
	$text = $atts['text'];
	return '<a href="'.$link.'" class="prepostbtn btn btn-lg btn-warning">'.$text.'</a>';
}

add_shortcode('fspm_btnform', 'fspm_buttonshortcode');


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

function fspm_cursequi($curso) {
	//transforma los valores de curso en valores legibles
	switch($curso) {
		case('pre'):
			$lcurso = 'Pre-Kínder';
		break;
		case('kin'):
			$lcurso = 'Kínder';
		break;
		case('1bas'):
			$lcurso = '1º Básico';
		break;
		case('2bas'):
			$lcurso = '2º Básico';
		break;
		case('3bas'):
			$lcurso = '3º Básico';
		break;	
	}
	return $lcurso;
}

//Envío de correos
function fspm_mails($data) {
	$mensajeapoderado = '<table width="600" cellspacing="0" cellpadding="20" style="font-family:sans-serif;font-size:14px;background-color:#FEF1D6;border:1px solid #ccc;">
		<tr>
			<td>
				<h3>Confirmación de pre-postulación</h3>
				<p>Estimado <strong>'. $data['nombre'] .'</strong>, hemos recibido exitosamente su postulación. Nos pondremos en contacto con usted vía teléfono en <strong>1 día hábil</strong> como máximo para continuar el proceso.</p>
				<p>Estos son los datos que usted envió:</p>
			</td> 
			<tr>
						<td>
							<h4 style="text-align:center;font-size:18px;">Datos</h4>
							<p><strong>Nombre Apoderado(a): </strong>' . $data['nombre'] . '</p>
							<p><strong>Teléfono Apoderado(a): </strong> +56 9 ' . $data['fono'] . '</p>
							<p><strong>E-Mail Apoderado(a): </strong>' . $data['email'] . '</p>
							<p><strong>Curso al que postula: </strong>' . fspm_cursequi($data['curso']) .'</p>
							<p><strong>Nombre al Alumno(a): </strong>' .$data['nalumno']. '</p>
							<p><strong>Consulta adicional: </strong>' .$data['mensaje'].'</p>
						</td>
					</tr>	
			<tr>
				<td>
				<p>Muchas gracias por su interés.<br>
				Afectuosamente<br>
				<strong>Colegio Seminario Pontificio Menor</strong></p>
				<p><strong>Correo: </strong> admision@spm.cl <br>
				<strong>Teléfono: </strong> +56 (2) 29239902 - Carolina Gundermann S. <br>
				<strong>Horario de atención telefónica y visitas: Lunes a viernes 8:15 a 13:30 y de 15:00 a 16:00 hrs.</strong><br>
				<a href="http://admision.spm.cl">admision.spm.cl</a></p>
				';

	
	if($data['curso'] == 'pre'):
		$mensajeapoderado .= '<p style="color:#555;font-style:italic;"><strong>Recuerda:</strong> Luego de pre-postular te contactaremos en máximo un día hábil para continuar con el proceso.El plazo máximo para postular es el jueves 11 de diciembre, 18.00 hrs. Confirmaremos la posibilidad de matricular a cada uno/a de los/as postulantes el día viernes 12 de diciembre a las 10.00 hrs.De completarse los cupos mínimos de postulantes, desde el mismo viernes 12 se deberá proceder a la matrícula.</p>';
	endif;

	$mensajeapoderado .=	'</td>
							</tr>
						</table>';
	$mensajeadmin = '
					<table width="600" cellspacing="0" cellpadding="20" style="font-family:sans-serif;font-size:14px;background-color:#f0f0f0;border:1px solid #ccc;">
					<tr>
						<td>
							<h3>Se ha enviado una prepostulación a SPM</h3>
							<p></p>
						</td>
					</tr>
					<tr>
						<td>
							<h4>Datos</h4>
							<p><strong>Nombre Apoderado(a): </strong>' . $data['nombre'] . '</p>
							<p><strong>Teléfono Apoderado(a): </strong>+56 9 ' . $data['fono'] . '</p>
							<p><strong>E-Mail Apoderado(a): </strong>' . $data['email'] . '</p>
							<p><strong>Curso al que postula: </strong>' . fspm_cursequi($data['curso']) .'</p>
							<p><strong>Nombre al Alumno(a): </strong>' .$data['nalumno']. '</p>
							<p><strong>Consulta adicional: </strong>' .$data['mensaje'].'</p>
						</td>
					</tr>	
					</table>
					';
	$admins = 'contacto@apie.cl, rectoria@spm.cl, admision@spm.cl, pablobravo@apie.cl, mariaceciliagn@gmail.com, pjuancarloscortez@gmail.com';
	$headers = 'From: "Colegio Seminario Pontificio Menor" <admision@spm.cl>';
	
	add_filter('wp_mail_content_type', function($content_type) {return 'text/html';});

	$mailapoderado = wp_mail( $data['email'], 'Prepostulación SPM', $mensajeapoderado, $headers);
	$mailadmin = wp_mail( $admins, 'Prepostulación SPM', $mensajeadmin, $headers);

	add_filter('wp_mail_content_type', function($content_type) {return 'text/plain';});

	if($mailapoderado && $mailadmin) {
		echo '<div class="alert alert-success"><i class="fa fa-check"></i> <i class="fa fa-envelope"></i></div>';
	} else {
		echo '<div class="alert alert-error"><i class="fa fa-times"></i> <i class="fa fa-envelope"></i></div>';
	}
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