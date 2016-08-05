<?php 

function fpost_admin() {
	add_options_page( __( 'Postulaciones', 'spm' ), __( 'Postulaciones enviadas', 'spm' ), 'manage_options', 'fpost_postulaciones', 'fpost_doadmin' );
	add_options_page( __( 'Postulaciones', 'spm' ), __( 'Consultas enviadas', 'spm' ), 'manage_options', 'fpost_consultas', 'fpost_doadminconsultas' );
	add_options_page( __( 'Postulaciones', 'spm' ), __( 'Configuración formularios', 'spm' ), 'manage_options', 'fpost_config', 'fpost_doadminconfig' );
}

add_action('admin_menu', 'fpost_admin');

function fpost_doadmin() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('No tienes permisos suficientes para ver esta página.') );
	}
	global $wpdb;
	//Llamando a los inscritos
	$inscritos = fpost_getdata();
	?>
	<div class="wrap">
		<h2>Postulaciones</h2>
		<table class="widefat wp-list-table fspmlist">
			<thead>
				<th>ID</th>
				<th>Fecha Insc.</th>
				<th>Hora</th>
				<th>Apellido Apoderado</th>
				<th>Nombre Apoderado</th>
				<th>E-Mail</th>
				<th>Teléfono</th>
				<th>Teléfono Fijo</th>
				<th>RUT Apoderado</th>

				<th>Año de postulación</th>
				<th>Datos Alumno</th>
				<th>Curso al que postula</th>

				
				
				<th>Mensaje adicional</th>
				<th>Cómo conoció el colegio</th>
			</thead>
		
		<?php
			foreach($inscritos as $key=>$inscrito) {
				$datos = unserialize($inscrito->data);
				?>
				<?php if($key %2 == 0):?>
					<tr class="alternate">
				<?php else:?>
					<tr>
				<?php endif;?>
					<td><?php echo $inscrito->id;?></td>
					<td><?php echo mysql2date( 'l, j \d\e F, Y ', $inscrito->time );?></td>
					<td><?php echo mysql2date( 'H:i,s', $inscrito->time );?></td>
					<td><?php echo $datos['apellido_apoderado'];?></td>
					<td><?php echo $datos['nombre_apoderado'];?></td>
					<td><?php echo $datos['email_apoderado'];?></td>
					<td><?php echo $datos['fono_apoderado'];?></td>
					<td><?php echo $datos['fonofijo_apoderado'];?></td>
					<td><?php echo fpost_formatrut($datos['rut_apoderado']);?> <!--Original: <?php echo $datos['rut_apoderado'];?>--></td>
					
					<td><?php echo $datos['postulacion_year'];?></td>

					<td>
						<p><strong><?php echo $datos['nombre_alumno'];?> <?php echo $datos['apellido_alumno'];?></strong></p>

						<p><strong>RUT: </strong><?php echo fpost_formatrut($datos['rut_alumno']);?></p>
						
						<p><strong>Fecha de nacimiento: </strong><?php echo ( isset($datos['alumno_fecha_nacimiento']) ? $datos['alumno_fecha_nacimiento'] : '' );?></p>

						<p><strong>Procedencia:</strong> <?php echo $datos['procedencia_alumno'];?></p>
	
					</td>

					<td><?php echo fpost_cursequi($datos['curso_postula']);?></td>
					
					<td><?php echo $datos['postulacion_mensaje'];?></td>
					<td><?php echo $datos['xtra_apoderado'];?></td>
				</tr>
			<?php }
		?>
		</table>
		<?php 
		
		$csv = fpost_csv();
		echo '<p><a class="button" href="'.$csv.'"> Descargar archivo CSV con inscripciones </a> </p>';
		?>
	</div>
	<?php
}

function fpost_doadminconsultas() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('No tienes permisos suficientes para ver esta página.') );
	}
	global $wpdb;
	//Llamando a los inscritos
	$consultas = fpost_getconsultas();
	?>
	<div class="wrap">
		<h2>Consultas</h2>
		<table class="widefat wp-list-table fspmlist">
			<thead>
				<th>ID</th>
				<th>Fecha Insc.</th>
				<th>Hora</th>
				<th>Nombre</th>
				<th>E-Mail</th>
				<th>Teléfono</th>
				<th>Mensaje</th>
			</thead>
		
		<?php
			foreach($consultas as $key=>$consulta) {
				$datos = unserialize($consulta->data);
				?>
				<?php if($key %2 == 0):?>
					<tr class="alternate">
				<?php else:?>
					<tr>
				<?php endif;?>
					<td><?php echo $consulta->id;?></td>
					<td><?php echo mysql2date( 'l, j \d\e F, Y ', $consulta->time );?></td>
					<td><?php echo mysql2date( 'H:i,s', $consulta->time );?></td>
					<td><?php echo $datos['nombre_consultas'];?></td>
					<td><?php echo $datos['email_consultas'];?></td>
					<td><?php echo $datos['fono_consultas'];?></td>
					<td><?php echo $datos['mensaje_consultas'];?></td>
				</tr>
			<?php }
		?>
		</table>
		<?php 
		// Desactivado por mientras 
		$csv = fpost_csv_consultas();
		echo '<p><a class="button" href="'.$csv.'"> Descargar archivo CSV con consultas </a> </p>';
		?>
	</div>
	<?php
}

function fpost_parseyear($year) {
	return $year;
}

function fpost_csv() {
	//Genera un csv con todos los datos
	global $wpdb;
	global $tbname;
	$inscritos = fpost_getdata();

	$filename = FPOST_PREFIX . 'admision_prepostulacion-'.date('d-m-y').'.csv';

	// output headers so that the file is downloaded rather than displayed
		// header('Content-Type: octet/stream');
		// header('Content-Disposition: attachment; filename=data.csv');
		// header('Content-Length: ' . filesize(FPOST_CSVPATH . $filename));

	

	$output = fopen(FPOST_CSVPATH . $filename, 'w');

	fputcsv($output, array('ID', 'Día', 'Hora', 'Apellido apoderado(a)', 'Nombre apoderado(a)','E-mail apoderado(a)', 'Fono apoderado(a)','Fono fijo apoderado(a)', 'RUT Apoderado', 'Nombre alumno(a)', 'F. nacimiento alumno(a)', 'RUT Alumno(a)', 'Curso al que postula', 'Año de postulación', 'Procedencia alumno(a)', 'Mensaje adicional', 'Cómo supo del colegio'), "\t");

	foreach($inscritos as $inscrito) {
		
		$data = $inscrito->data;
		$arrdata = unserialize($data);

		$inscarr = array();
		$inscarr[] = $inscrito->id;
		$inscarr[] = mysql2date('j F', $inscrito->time );
		$inscarr[] = mysql2date('H:i', $inscrito->time );
		$inscarr[] = $arrdata['apellido_apoderado'];
		$inscarr[] = $arrdata['nombre_apoderado'];
		$inscarr[] = $arrdata['email_apoderado'];
		$inscarr[] = $arrdata['fono_apoderado'];
		$inscarr[] = $arrdata['fonofijo_apoderado'];
		$inscarr[] = fpost_formatrut($arrdata['rut_apoderado']);
		$inscarr[] = $arrdata['nombre_alumno'] . ' ' . $arrdata['apellido_alumno'];
		$inscarr[] = $arrdata['alumno_fecha_nacimiento'];
		$inscarr[] = fpost_formatrut($arrdata['rut_alumno']);
		$inscarr[] = fpost_cursequi($arrdata['curso_postula']);
		$inscarr[] = $arrdata['postulacion_year'];
		$inscarr[] = $arrdata['procedencia_alumno'];
		$inscarr[] = $arrdata['postulacion_mensaje'];
		$inscarr[] = $arrdata['xtra_apoderado'];
		fputcsv($output, $inscarr, "\t");
	}

	$csvfile = FPOST_CSVURL . $filename;
	return $csvfile;

}

function fpost_csv_consultas() {
	//Genera un csv con todos los datos
	global $wpdb;
	global $tbname;
	$consultas = fpost_getconsultas();

	// output headers so that the file is downloaded rather than displayed
		header('Content-Type: octet/stream');
		header('Content-Disposition: attachment; filename=data.csv');
		header('Content-Length: ' . filesize(FPOST_CSVPATH . $filename));

	$filename = FPOST_PREFIX . '_consultas-'.date('d-m-y').'.csv';

	$output = fopen(FPOST_CSVPATH . $filename, 'w');

	fputcsv($output, array('ID', 'Fecha', 'Hora', 'Nombre', 'Telefono', 'Email', 'Consultas'), "\t");

	foreach($consultas as $consulta) {
		$data = $consulta->data;
		$arrdata = unserialize($data);

		$consarr = array();
		$consarr[] = $consulta->id;
		$consarr[] = mysql2date('j F', $consulta->time );
		$consarr[] = mysql2date('H:i', $consulta->time );
		$consarr[] = $arrdata['nombre_consultas'];
		$consarr[] = '+56 9' . $arrdata['fono_consultas'];
		$consarr[] = $arrdata['email_consultas'];
		$consarr[] = $arrdata['mensaje_consultas'];
	
		fputcsv($output, $consarr, "\t");
	}

	$csvfile = FPOST_CSVURL . $filename;
	return $csvfile;

}

function fpost_formatrut($r = false){
    if((!$r) or (is_array($r)))
        return false; /* Hace falta el rut */
 
    if(!$r = preg_replace('|[^0-9kK]|i', '', $r))
        return false; /* Era código basura */
 
    if(!((strlen($r) == 8) or (strlen($r) == 9)))
        return false; /* La cantidad de carácteres no es válida. */
 
    $v = strtoupper(substr($r, -1));
    if(!$r = substr($r, 0, -1))
        return false;
 
    if(!((int)$r > 0))
        return false; /* No es un valor numérico */
 
    $x = 2; $s = 0;
    for($i = (strlen($r) - 1); $i >= 0; $i--){
        if($x > 7)
            $x = 2;
        $s += ($r[$i] * $x);
        $x++;
    }
    $dv=11-($s % 11);
    if($dv == 10)
        $dv = 'K';
    if($dv == 11)
        $dv = '0';
    if($dv == $v)
        return number_format($r, 0, '', '.').'-'.$v; /* Formatea el RUT */
    return false;
}

function fpost_init_settings() {

	register_setting( 'fpost_infoformularios', 'fpost_settings_info_formularios' );

	add_settings_section(
		'fpost_info_formularios', 
		__( 'Datos de Contacto', 'fpost' ), 
		'adm_settings_infocontacto_section_callback', 
		'admision_infocontacto'
	);

	add_settings_field( 
		'fpost_text_ncolegio', 
		__( 'Nombre del colegio', 'fpost' ), 
		'adm_text_encargada_render', 
		'admision_infocontacto', 
		'adm_admision_infocontacto_section' 
	);
}

function fpost_doadminconfig() {
	/**
	 * Crea los campos de configuración para los formularios
	 */
	?>

	<div class="wrap">
		<h2>Configuración formularios de contacto</h2>
	</div>



	<?php
}