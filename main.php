<?php
/**
 * Plugin Name: Formulario de solicitud de admisión para CSD
 * Plugin URI: http://apie.cl
 * Description: Generador de formulario y almacenamiento de datos para admisión
 * Version: 0.5
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
$dbver = '1.2';
$tbname = $wpdb->prefix . 'fcsdappdata';


//Crear directorios
define( 'FSPM_CSVPATH', WP_CONTENT_DIR . '/spmcsv/');
define( 'FSPM_CSVURL', WP_CONTENT_URL . '/spmcsv/');
//Variables de mails y nombres
define( 'FSPM_NCOLEGIO', 'Colegio Santo Domingo');
define( 'FSPM_FROMMAIL', 'admision@colegiosantodomingo.cl');
define( 'FSPM_TOMAILS', 'admision@colegiosantodomingo.cl, jorgeloayza@gmail.com, lmsanchezpintor@gmail.com, imprentabbr@gmail.com, pabloselin@gmail.com');
define( 'FSPM_FONO', '+56 2 265 278 73');
//define( 'FSPM_TOMAILS', 'pabloselin@gmail.com, jorgeloayza@gmail.com');
define( 'FSPM_LOGO', 'http://admision.colegiosantodomingo.cl/wp-content/themes/csd-admision/assets/img/logocsd2014_7.png');

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
			otrocurso text NOT NULL,
			year text NOT NULL,
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
	if($_POST && $_POST['prepostnonce']) {
		$nonce = $_POST['prepostnonce'];
	};
	$form = '<form class="form-horizontal" id="fcsd_prepostulacion" action="" method="POST">
			<!--nonce-->
			'.wp_nonce_field('fspm_prepost', 'prepostnonce').'
			<input type="hidden" name="" value="fspm_prepost" placeholder="">
			<!--formel-->
			<div class="form-group">
				<label class="control-label col-sm-5" for="nombre_apoderado">Nombre apoderado(a)</label>
					<div class="col-sm-7">
						<input type="text" name="nombre_apoderado" value="" placeholder="Nombre Apoderado(a)" required class="form-control">
					</div>
				</div>
			<!--formel-->
			<div class="form-group">
				<label class="control-label col-sm-5" for="fono_apoderado">Celular apoderado(a)</label>
				<div class="col-sm-7">
					<div class="input-group">
						<span class="input-group-addon">+56 9</span>
						<input class="form-control" type="text" name="fono_apoderado" value="" placeholder="" required">
					</div>
				</div>
			</div>
			<!--formel-->
			<div class="form-group">
				<label class="control-label col-sm-5" for="email_apoderado">E-Mail apoderado(a)</label>
				<div class="col-sm-7">
					<input class="form-control" type="email" name="email_apoderado" value="" placeholder="Email Apoderado(a)" required>
				</div>
			</div>
			<!--formel-->
			<div class="form-group">
				<label class="control-label col-sm-5" for="nombre_alumno">Nombre alumno(a)</label>
				<div class="col-sm-7">
					<input class="form-control" type="text" name="nombre_alumno" value="" placeholder="Nombre alumno(a)" required>
				</div>
			</div>
			
			<div class="form-group year-control">
					<div class="col-sm-12 help-block">
						<p>Año al que postula</p>
					</div>
					<div class="col-sm-7 year-post">
						<div class="radio">
							<label>
								<input type="radio" name="year" value="proximo">
								<span class="lname">2016</span>
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="year" value="actual">
								<span class="lname">2015</span>
							</label>
						</div>
					</div>
			</div>	
			<div class="form-group curso-control">
				<div class="col-sm-12 help-block">
					<p>Curso a postular</p>
				</div>
				<div class="col-sm-12 curso-post">
					<div class="radio" data-target="proximo actual">
						<label>
							<input type="radio" name="curso" value="jardin" default>
							<span class="lname">Jardín</span>
						</label>
					</div>
					<div class="radio" data-target="proximo actual">
						<label>
							<input type="radio" name="curso" value="pre" default>
							<span class="lname">Pre-Kínder</span>
						</label>
					</div>
					<div class="radio" data-target="proximo actual">
						<label>
							<input type="radio" name="curso" value="kin">
							<span class="lname">Kínder</span>
						</label>
					</div>
					<div class="radio" data-target="proximo">
						<label>
							<input type="radio" name="curso" value="1bas">
							<span class="lname">1º Básico</span>
						</label>
					</div>
					<div class="radio" data-target="proximo">
						<label>
							<input type="radio" name="curso" value="2bas">
							<span class="lname">2º Básico</span>
						</label>
					</div>
					<div class="radio" data-target="proximo actual">
						<label>
							<input type="radio" name="curso" value="otros">
							<span class="lname">Otros cursos <span class="lnamewarn">(Sujeto a disponibilidad de cupos)</span></span>
						</label>
					</div>
				</div>
				<div class="form-group otrocurso-control">
					<label class="control-label col-sm-5" for="otrocurso">¿Cuál?</label>
						<div class="col-sm-5">
							<input type="text" name="otrocurso" value="" placeholder="Curso" class="form-control" aria-describedby="otrocurso">
							<p>
								Indique a qué otro curso le interesa postular
							</p>
						</div>
				</div>
			</div>
			
			<!--formel-->
			<div class="form-group col-sm-12">
				<label class="control-label" for="mensaje_apoderado">Información adicional</label>
				<div>
					<textarea class="form-control" name="mensaje"></textarea>
				</div>
			</div>
			<!--formel-->			

			<!--submit-->
			<div class="submitplaceholder">
				<div class="alert">
					Necesitas JavaScript activado para poder usar el formulario
				</div>
			</div>
		</form>';
		if($_POST && $nonce){	
			$output = fspm_validate();
		} else {
			$output = $form;	
		}
		return $output;
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
							'cursoi' => $data['curso'],
							'otrocurso' => $data['otrocurso'],
							'year' => $data['year']
							)
						);
	$lastid = $wpdb->insert_id;
	$okmess = '<div class="alert alert-success">
						<p style="text-align:center;font-size:32px;"><span class="glyphicon glyphicon-ok-sign"></span></p>
						<h4 style="font-family: sans-serif;font-size:32px;text-align:center;">Pre-postulación enviada con éxito</h4>
						<p style="text-align:center;">Gracias por prepostular a '. FSPM_NCOLEGIO . ', te hemos enviado un correo de confirmación a <strong>'.$data['email'].'</strong> (revisa tu bandeja de spam por si acaso...) y te contactaremos vía teléfono o correo en máximo <strong>2 días hábiles</strong> para continuar el proceso.</p></div>
						</div>';
	$errmess = '<div class="alert alert-error"><p><span class="glyphicon glyphicon-remove-sign"></span></p><p>Hubo un error en la inscripción, por favor contacte al colegio directamente en admision@colegiosantodomingo.cl.</p></div>';
	if($lastid) {
		$message = $okmess;
		$message .=  '<div class="modal fade" id="success" role="dialog" tabindex="-1" aria-labelledby="Inscripción Exitosa en '.FSPM_NCOLEGIO.'" aria-hidden="true">';
		$message .= '<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						'.$okmess.'
						<div class="modal-footer">
        				<button type="button" class="btn btn-success" data-dismiss="modal"><span class="glyphicon glyphicon-ok"></span> Cerrar</button>
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
        				<button type="button" class="btn btn-success" data-dismiss="modal"><span class="glyphicon glyphicon-ok"></span> Cerrar</button>
      				</div>
			</div>
			</div>
			</div>';
	}
	//Enviar mensaje y correr funciones
	$message .= fspm_mails($data);

	return $message;
}

//Shortcode para el formulario
function fspm_formshortcode($atts) {
	return fspm_form();
}

add_shortcode('fcsd_admform', 'fspm_formshortcode');

function spm_shareshortcode($atts) {
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

add_shortcode('csd_share', 'spm_shareshortcode');

//shortcode para el botón
function fspm_buttonshortcode($atts) {
	$link = $atts['url'];
	$text = $atts['text'];
	return '<a href="'.$link.'" class="prepostbtn btn btn-lg btn-warning">'.$text.'</a>';
}

add_shortcode('fcsd_btnform', 'fspm_buttonshortcode');


//Validación
//Añadir esta función por AJAX
function fspm_validate() {
	if(!wp_verify_nonce( $_POST['prepostnonce'], 'fspm_prepost' )) {
		return 'nonce inválido';
	} else {
		//Sanitizar
		$data['nombre'] = sanitize_text_field($_POST['nombre_apoderado']);
		$data['fono'] = sanitize_text_field($_POST['fono_apoderado']);
		$data['email'] = sanitize_email($_POST['email_apoderado']);
		$data['nalumno'] = sanitize_text_field($_POST['nombre_alumno'] );
		$data['mensaje'] = sanitize_text_field($_POST['mensaje']);
		$data['curso'] = sanitize_key($_POST['curso']);
		$data['otrocurso'] = sanitize_text_field($_POST['otrocurso']);
		$data['year'] = sanitize_key($_POST['year']); 
		//Meter en la base de datos
		$output = fspm_putdata($data);
		return $output;
	}
}



function fspm_cursequi($curso, $otro = NULL) {
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
		case('otros'):
			$lcurso = $otro;
		break;
		case('jardin'):
			$lcurso = 'Jardín';
		break;
		default:
			$lcurso = $curso;
		break;	
	}
	return $lcurso;
}
//Envío de correos
function fspm_mails_clone($data) {
	
	$admins = FSPM_TOMAILS;
	$headers = 'From: "'.FSPM_NCOLEGIO.'" <'.FSPM_FROMMAIL.'>';
	
	add_filter('wp_mail_content_type', function($content_type) {return 'text/html';});

	$mailadmin = wp_mail( $admins, 'Prepostulación CSD', $mensajeadmin, $headers);

	add_filter('wp_mail_content_type', function($content_type) {return 'text/plain';});

	if($mailapoderado && $mailadmin) {
		echo '<div class="alert alert-success"><i class="fa fa-check"></i> <i class="fa fa-envelope"></i></div>';
	} else {
		echo '<div class="alert alert-error"><i class="fa fa-times"></i> <i class="fa fa-envelope"></i></div>';
	}
}

//Envío de correos
function fspm_mails($data) {
	$mensajeapoderado = '<style>table p {line-height:1,4em;}</style>
		<table align="center" width="600" cellspacing="0" cellpadding="20" style="font-family:sans-serif;font-size:14px;border:1px solid #ccc;">
		<tr>
			<td style="background-color:#555;color:white;">
				<p style="text-align:center;"><img src="'.FSPM_LOGO.'" alt="'.FSPM_NCOLEGIO.'"><br><h1 style="font-family:serif;font-size:24px;font-weight:normal;text-align:center;">'.FSPM_NCOLEGIO.'</h1></p>
				<h3 style="text-align:center;font-size:18px;font-weight:normal;">Confirmación de pre-postulación para el año '.fspm_parseyear($data['year']).'</h3>
			</td> 
			<tr>
				<td>
					<p>Estimado/a <strong>'. $data['nombre'] .'</strong>, hemos recibido exitosamente su postulación. Nos pondremos en contacto con usted vía teléfono o correo en <strong>2 días hábiles</strong> como máximo para continuar el proceso.</p>
					<p>Estos son los datos que usted envió:</p>
				</td>
			</tr>
			<tr>
						<td style="border-width:1px 0 1px 0;border-style:dotted;border-color:#ccc;background-color:white;">
							<h4 style="text-align:center;font-size:18px;font-weight:normal;">Datos</h4>
							<p><strong>Nombre al Alumno(a): </strong>' .$data['nalumno']. '</p>
							<p><strong>Curso al que postula: </strong>' . fspm_cursequi($data['curso'], $data['otrocurso']) .'</p>
							<p><strong>Año al que postula: </strong>' . fspm_parseyear($data['year']) . '</p>
							<p>&nbsp;</p>
							<p><strong>Nombre Apoderado(a): </strong>' . $data['nombre'] . '</p>
							<p><strong>Teléfono Apoderado(a): </strong> +56 9 ' . $data['fono'] . '</p>
							<p><strong>E-Mail Apoderado(a): </strong>' . $data['email'] . '</p>
							<p>&nbsp;</p>
							<p><strong>Consulta adicional: </strong>' .$data['mensaje'].'</p>
						</td>
					</tr>';

	$mensajeapoderado .= '<tr>
				<td>
				<p>Muchas gracias por su interés.<br>
				Afectuosamente<br>
				<strong>'.FSPM_NCOLEGIO.'</strong></p>
				<p><strong>Correo: </strong> '.FSPM_FROMMAIL.' <br>
				<strong>Teléfono: </strong> <a href="tel:'.FSPM_FONO.'">'.FSPM_FONO.'</a>  <br>
				<strong>Web: </strong><a href="'.get_bloginfo('url').'">'.get_bloginfo('url').'</a></p>
				';

	$mensajeapoderado .=	'</td>
							</tr>
						</table>';
	$mensajeadmin = '
					<table width="600" cellspacing="0" cellpadding="20" style="font-family:sans-serif;font-size:14px;background-color:#f0f0f0;border:1px solid #ccc;">
					<tr>
						<td>
							<h3>Se ha enviado una prepostulación a CSD para el año '.fspm_parseyear($data['year']).'</h3>
							<p></p>
						</td>
					</tr>
					<tr>
						<td>
							<h4>Datos</h4>
							<p><strong>Nombre Apoderado(a): </strong>' . $data['nombre'] . '</p>
							<p><strong>Teléfono Apoderado(a): </strong>+56 9 ' . $data['fono'] . '</p>
							<p><strong>E-Mail Apoderado(a): </strong>' . $data['email'] . '</p>
							<p><strong>Curso al que postula: </strong>' . fspm_cursequi($data['curso'], $data['otrocurso']) .'</p>
							<p><strong>Nombre al Alumno(a): </strong>' .$data['nalumno']. '</p>
							<p><strong>Año al que postula: </strong>' . fspm_parseyear($data['year']) . '</p>
							<p><strong>Consulta adicional: </strong>' .$data['mensaje'].'</p>
						</td>
					</tr>	
					</table>
					';
	$admins = FSPM_TOMAILS;
	$headers = 'From: "'.FSPM_NCOLEGIO.'" <'.FSPM_FROMMAIL.'>';
	
	add_filter('wp_mail_content_type', function($content_type) {return 'text/html';});

	$mailapoderado = wp_mail( $data['email'], 'Prepostulación ' . FSPM_NCOLEGIO, $mensajeapoderado, $headers);
	$mailadmin = wp_mail( $admins, 'Prepostulación '. FSPM_NCOLEGIO , $mensajeadmin, $headers);

	add_filter('wp_mail_content_type', function($content_type) {return 'text/plain';});

	if($mailapoderado && $mailadmin) {
		return '<div class="alert alert-success"><i class="fa fa-check"></i> <i class="fa fa-envelope"></i></div>';
	} else {
		return '<div class="alert alert-error"><i class="fa fa-times"></i> <i class="fa fa-envelope"></i></div>';
	}
}

//Scripts y estilos extras
function fspm_stylesetscripts() {
	if(!is_admin()) {
		wp_register_style( 'fspm', plugins_url('/css/fspm.css', __FILE__) , array(), '1.0', 'screen' );
		wp_register_script( 'fspm', plugins_url('/js/fspm.js', __FILE__), array('jquery'), '1.0', false);
		wp_register_script( 'jqvalidate', plugins_url('/js/jquery.validate.min.js', __FILE__), array('jquery'), '1.14.0', false);
		wp_enqueue_style( 'fspm' );
		wp_enqueue_script( 'fspm' );
		wp_enqueue_script('jqvalidate');
	};
}

add_action('wp_print_scripts', 'fspm_stylesetscripts');