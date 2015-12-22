<?php 

add_action('admin_menu', 'fpost_admin');

function fpost_admin() {
	add_options_page( __( 'Postulaciones', 'spm' ), __( 'Postulaciones enviadas', 'spm' ), 'manage_options', 'fpost_postulaciones', 'fpost_doadmin' );
	add_options_page( __( 'Postulaciones', 'spm' ), __( 'Consultas enviadas', 'spm' ), 'manage_options', 'fpost_consultas', 'fpost_doadminconsultas' );
}

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
				<th>RUT</th>

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
					<td><?php echo $datos['rut_apoderado'];?></td>
					
					<td><?php echo $datos['postulacion_year'];?></td>

					<td>
						<p><strong><?php echo $datos['nombre_alumno'];?> <?php echo $datos['apellido_alumno'];?></strong></p>
						
						<p><strong>Nac.</strong> <?php echo $datos['alumno_dia_nacimiento'];?> de <?php echo $datos['alumno_mes_nacimiento'];?>, <?php echo $datos['alumno_an_nacimiento'];?></p>

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
		// Desactivado por mientras 
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
		//$csv = fpost_csv();
		//echo '<p><a class="button" href="'.$csv.'"> Descargar archivo CSV con inscripciones </a> </p>';
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

	// output headers so that the file is downloaded rather than displayed
		header('Content-Type: octet/stream');
		header('Content-Disposition: attachment; filename=data.csv');
		header('Content-Length: ' . filesize(FPOST_CSVPATH . $filename));

	$filename = FPOST_PREFIX . 'admision_prepostulacion-'.date('d-m-y').'.csv';

	$output = fopen(FPOST_CSVPATH . $filename, 'w');

	fputcsv($output, array('Día', 'Hora', 'Apellido apoderado(a)', 'Nombre apoderado(a)','E-mail apoderado(a)', 'Fono apoderado(a)', 'RUT Apoderado', 'Nombre alumno(a)', 'F. nacimiento alumno(a)', 'Curso al que postula', 'Año de postulación', 'Procedencia alumno(a)', 'Mensaje adicional', 'Cómo supo del colegio'), "\t");

	foreach($inscritos as $inscrito) {
		
		$data = $inscrito->data;
		$arrdata = unserialize($data);

		$inscarr = array();
		$inscarr[] = mysql2date('j F', $inscrito->time );
		$inscarr[] = mysql2date('H:i', $inscrito->time );
		$inscarr[] = $arrdata['apellido_apoderado'];
		$inscarr[] = $arrdata['nombre_apoderado'];
		$inscarr[] = $arrdata['email_apoderado'];
		$inscarr[] = $arrdata['fono_apoderado'];
		$inscarr[] = $arrdata['rut_apoderado'];
		$inscarr[] = $arrdata['nombre_alumno'] . ' ' . $arrdata['apellido_alumno'];
		$inscarr[] = $arrdata['alumno_dia_nacimiento'] . ' / ' . $arrdata['alumno_mes_nacimiento'] . ' / ' . $arrdata['alumno_an_nacimiento'];
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

}