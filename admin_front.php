<?php
/**
 * Funciones para ver las postulaciones desde el Frontend, con contraseña
 */

function fpost_smalltable() {
	/**
	 * Devuelve una tabla resumida con las postulaciones
	 */
	global $wpdb;

	$inscritos = fpost_getdata();

	$output = '<table class="postulaciones-frontend-table table table-striped">';
	$output .= '<thead style="background-color:#333">
					<th>ID</th>
					<th>Año</th>
					<th>Apellido apoderado</th>
					<th>Nombre apoderado</th>
					<th>Fecha</th>
					<th>Hora</th>
					<th></th>
				</thead>';

	foreach($inscritos as $key=>$inscrito) {

		$datos = unserialize($inscrito->data);

		if($key %2 == 0):
			
			$output .= '<tr class="odd">';

		else:

			$output .= '<tr>';

		endif;

		$output .= '<td>' . $inscrito->id . '</td>';
		$output .= '<td>' . $datos['postulacion_year'] . '</td>';
		$output .= '<td>' . $datos['apellido_apoderado'] . '</td>';
		$output .= '<td>' . $datos['nombre_apoderado']. '</td>';
		$output .= '<td>' . mysql2date( 'l, j \d\e F, Y ', $inscrito->time ) . '</td>';
		$output .= '<td>' . mysql2date( 'H:i,s', $inscrito->time ) . '</td>';
		$output .= '<td> <a href="' . fpost_postulacionlink( $inscrito->id ) .'" class="btn btn-default">Ver ficha completa</a> </td>';
		$output .= '</tr>';


	}

	$output .= '</table>';

	return $output;

}

function fpost_fichapostulacion( $postulacion_id ) {
	/**
	 * Devuelve una ficha de postulación completa
	 */
	
	$postulacion = fpost_getpostulacion( $postulacion_id );
	$data = unserialize( $postulacion->data );

	$output = '<div class="ficha-postulacion"><h2>Postulación ID: ' . $postulacion->id . '</h2>';

	$output .= '<table class="table table-striped">';

	$output .= '<tr><td class="thleft">Fecha y hora postulación:</td> <td>' . mysql2date( 'l, j \d\e F, Y ', $postulacion->time ) . ' ' . mysql2date( 'H:i,s', $postulacion->time ) . '</td></tr>';

	$output .= '<tr><td class="thleft">Nombre Apoderado:</td><td>' . $data['nombre_apoderado'] . ' ' . $data['apellido_apoderado'] . '</td></tr>';

	if(isset($data['rut_apoderado'])):

		$output .= '<tr><td class="thleft">Rut Apoderado: </td><td>' . fpost_formatrut($data['rut_apoderado']) . '</td></tr>';

	endif;

	if(isset($data['otrodoc_apoderado'])):

		$output .= '<tr><td class="thleft">Documento Identificación Apoderado: </td><td>' . $data['otrodoc_apoderado'] . '</td></tr>';

	endif;



	$output .= '<tr><td class="thleft">Email:</td><td>' . $data['email_apoderado'] . '</td></tr>';
	$output .= '<tr><td class="thleft">Teléfono:</td><td>' . $data['fono_apoderado'] . '</td></tr>';
	$output .= '<tr><td class="thleft">Teléfono fijo:</td><td>' . $data['fonofijo_apoderado']. '</td></tr>';


	$output .= '<tr><td class="thleft">Año al que postula:</td> <td>' . $data['postulacion_year'] . '</td></tr>';

	$output .= '<tr><td class="thleft">Nombre y apellido alumno: </td> <td>' . $data['nombre_alumno'] . ' ' . $data['apellido_alumno'] . '</td></tr>';

	if(isset( $data['rut_alumno'] )):

		$output .= '<tr><td class="thleft">Rut Alumno:</td><td>' . fpost_formatrut($data['rut_alumno']) . '</td></tr>';

	endif;

	if(isset( $data['otrodoc_alumno'] )):

		$output .= '<tr><td class="thleft">Documento Identificación:</td><td>' . $data['otrodoc_alumno'] . '</td></tr>';

	endif;

	$output .= '<tr><td class="thleft">Fecha de Nacimiento:</td> <td>' . $data['alumno_fecha_nacimiento'] . '</td></tr>';

	$output .= '<tr><td class="thleft">Procedencia:</td> <td>' . $data['procedencia_alumno'] . '</td></tr>';

	$output .= '<tr><td class="thleft">Curso al que postula:</td> <td>' . fpost_cursequi($data['curso_postula']) . '</td></tr>';

	if(isset( $data['jornada'])):

		$output .= '<tr><td class="thleft">Jornada:</td> <td>' . fpost_formatjornada($data['jornada']) . '</td></tr>';

	endif;

	$output .= '<tr><td class="thleft">Año al que postula:</td> <td>' . $data['postulacion_year'] . '</td></tr>';

	$output .= '<tr><td class="thleft">Mensaje de postulación:</td> <td>' . $data['postulacion_mensaje'] . '</td></tr>';

	$output .= '</table>';

	$output .= '</div>';

	return $output;


}

function fpost_contact( $postulacion_id ) {
	/**
	 * Abre un formulario para enviar un mensaje con el link
	 */
	
	$output = ' <button class="btn btn-success prevmessage" data-toggle="modal" data-target="#prevmessage">Previsualizar mensaje</button>';


	$output .= ' <a href="' . fpost_secondformlink( $postulacion_id ) . '" class="btn btn-info">Previsualizar formulario</a>';

	$output .= '<p></p>';

	$output .= '<form class="form-mensaje" action="">';
	
	$output .= '<div class="form-group">
				<label for="mensaje_contacto_segunda_etapa">Mensaje</label>';

	$output .= '<div class="bg-info"><p>Se enviará este mensaje al apoderado junto con un enlace al formulario para completar datos de segunda etapa</p></div>';

	$output .= '<textarea class="form-control" name="mensaje_contacto_segunda_etapa" placeholder="Escribir un mensaje aquí"></textarea>
				</div>';

	$output .= ' <p></p><p><input class="btn btn-danger btn-lg" type="submit" name="Enviar mensaje" value="Enviar Mensaje"/></p>';

	$output .= '</form>';

	$output .= '<div class="modal fade" id="prevmessage" tabindex="-1" role="dialog" aria-labelledby="modalmensaje">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" type="button" data-dismiss="modal" aria-label="Cerrar"></button> 
							<h4 class="modal-title">Previsualizar mensaje </h4>
						</div>
						<div class="modal-body">
							' . fpost_secondmail( $postulacion_id, '<pre class="fill-textarea-repeat"></pre>') . '
						</div>
						
						<div class="modal-footer">

						</div>
					</div>
				</div>
				</div>';

	return $output;
}

function fpost_secondmail( $postulacion_id, $message ) {

	$options = get_option('apadm_settings');
	$nombre_colegio = $options['apadm_nombre_colegio'];
	$logo_colegio = $options['apadm_logourl'];
	$email_remitente = $options['apadm_email_remitente'];
	$fono_contacto = $options['apadm_fono_contacto'];

	$mensajeapoderado = '<style>table p {line-height:1,4em;}</style>
		<table align="center" width="600" cellspacing="0" cellpadding="20" style="font-family:sans-serif;font-size:14px;">
		<tr>
			<td style="background-color:white;color:#333;">
				<p style="text-align:center;"><img src="'. $logo_colegio .'" alt="'. $nombre_colegio .'"><br><h1 style="font-family:sans-serif;font-size:28px;font-weight:normal;text-align:center;color:#1A7CAF;">'. $nombre_colegio .'</h1></p>
				<h3 style="text-align:center;font-size:18px;font-weight:normal;">Solicitud de datos para continuación de postulación</h3>
			</td> 
		</tr>
			<tr>
				<td><table style="padding:20px;"><tr><td>';

	$mensajeapoderado .= $message;
					
	$mensajeapoderado .= '<p></p><p></p></td></tr></table></td></tr>';

	$mensajeapoderado .= '<tr>
				<td>
				<p>Muchas gracias por su interés.<br>
				Afectuosamente<br>
				<strong>'. $nombre_colegio .'</strong></p>
				<p><strong>Correo: </strong> '. $email_remitente .' <br>
				<strong>Teléfono: </strong> <a href="tel:'. $fono_contacto .'">'. $fono_contacto .'</a>  <br>
				<strong>Web: </strong><a href="'.get_bloginfo('url').'">'.get_bloginfo('url').'</a></p>
				';

	$mensajeapoderado .=	'</td>
							</tr>
						</table>';

	return $mensajeapoderado;
}

function fpost_secondform( $postulacion_id ) {
	/**
	 * Devuelve el formulario con información secundaria
	 */
}

function fpost_postulacionlink( $postulacion_id ) {
	/**
	 * Devuelve un link a la ficha de postulación
	 */
	global $post;

	//La página en la que estoy
	$link = get_permalink( $post->ID );

	$linkficha = add_query_arg('fpostid', $postulacion_id, $link );

	return $linkficha;

}

function fpost_secondformlink( $postulacion_id ) {
	/**
	 * Devuelve un link al formulario con extra de información
	 * Hay que añadir una opción en WP para poder detectar este formulario
	 */
	
	$formlink = get_permalink(FPOST_FORMID);

	$queryargs = array(
		'fpostid' => $postulacion_id,
		'datos_adicionales' => 1,
		'fposthash' => uniqid()
		);

	$linkform = add_query_arg( $queryargs, $formlink );

	return $linkform;

}

function fpost_adminfrontshortcode( $atts ) {
	/**
	 * Shortcode para generar la sección de administración
	 */
	
	ob_start();

	if(is_user_logged_in()):

		include plugin_dir_path( __FILE__ ) . '/parts/postulacion-admin-front.php';

	else:

		include plugin_dir_path( __FILE__ ) . '/parts/sorry.php';		

	endif;
	
	return ob_get_clean();
	
}

add_shortcode( 'admin_front', 'fpost_adminfrontshortcode' );