<?php
/**
 * Plugin Name: Admisión
 * Plugin URI: http://apie.cl
 * Description: Sistema de manejo de proceso de postulación y admisión para colegios.
 * Version: 0.8
 * Author: A Pie
 * Author URI: http://www.apie.cl
 * License: MIT
 */


/*

TODO:

- Crear lista de opciones para datos generales
	- Nombre de colegio
	- Correo de quien envía
	- Teléfono de contacto
	- Prefijo para metaboxes
	- Correos de envío
	- Logotipo para correo

- Mejorar prefijo
- Modificar lógica y orden de programación para dividir las funcionalidades

*/

global $dbver;
$dbver = '1.7';

define( 'APIEADM_VERSION', '0.91');

//Crear directorios
define( 'APIEADM_CSVPATH', WP_CONTENT_DIR . '/postulaciones/');
define( 'APIEADM_CSVURL', WP_CONTENT_URL . '/postulaciones/');
//Variables de mails y nombres

define( 'FPOST_TABLENAME', 'apie_adm');
 
if(!is_dir(APIEADM_CSVPATH)){
	mkdir(WP_CONTENT_DIR . '/postulaciones', 0755);
}


//admin page
include( plugin_dir_path( __FILE__ ) . 'admin.php' );

//admin page options
include( plugin_dir_path( __FILE__ ) . 'inc/admin_options.php' );


//la parte de las consultas
include( plugin_dir_path( __FILE__ ) . 'consultas.php' );

//la parte del admin via frontend
include( plugin_dir_path( __FILE__ ) . 'admin_front.php' );

//la parte de manejo de bases de datos
include( plugin_dir_path( __FILE__) . 'inc/db-functions.php');

//los shortcodes
include( plugin_dir_path( __FILE__) . 'inc/shortcodes.php');

//Mail functions
include( plugin_dir_path( __FILE__) . 'inc/mailing-functions.php');

//Utilidades y convertidores de strings
include( plugin_dir_path( __FILE__) . 'inc/utils.php');

//Funciones para los formularios
include( plugin_dir_path( __FILE__) . 'inc/form-functions.php');


//Scripts y estilos extras
function fpost_styleandscripts() {
	if(!is_admin()) {
		
		wp_register_style( 'postulacioncss', plugins_url('/css/apie-admision.css', __FILE__), array(), APIEADM_VERSION, 'screen' );
		wp_register_script( 'postulacionjs', plugins_url('/js/apie-admision.js', __FILE__ ), array(), APIEADM_VERSION, false);
		
		wp_enqueue_script( 'postulacionjs' );
		
		wp_enqueue_style( 'postulacioncss' );
		
	};
}

add_action('wp_print_scripts', 'fpost_styleandscripts');