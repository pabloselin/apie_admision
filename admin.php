<?php 

add_action('admin_menu', 'fspm_admin');

function fspm_admin() {
	add_options_page( __( 'Prepostulaciones Admisión CSD', 'spm' ), __( 'Prepostulaciones Admisión CSD', 'spm' ), 'manage_options', 'prepost_admision', 'fspm_doadmin' );
}

function fspm_doadmin() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('No tienes permisos suficientes para ver esta página.') );
	}
	global $wpdb;
	//Llamando a los inscritos
	$inscritos = fspm_getdata();
	?>
	<div class="wrap">
		<h2>Prepostulación CSD</h2>
		<table class="widefat wp-list-table fspmlist">
			<thead>
				<th>Fecha</th>
				<th>Hora</th>
				<th>Nombre Apoderado</th>
				<th>Nombre Alumno</th>
				<th>E-Mail Apoderado</th>
				<th>Teléfono Apoderado</th>
				<th>Curso</th>
				<th>Información adicional</th>
				<th>Año de postulación</th>
			</thead>
		
		<?php
			foreach($inscritos as $inscrito) {?>
				<tr>
					<td><?php echo mysql2date( 'j F', $inscrito->time );?></td>
					<td><?php echo mysql2date( 'H:i', $inscrito->time );?></td>
					<td><?php echo $inscrito->apname;?></td>
					<td><?php echo $inscrito->alname;?></td>
					<td><?php echo $inscrito->apmail;?></td>
					<td><?php echo '+56 9' . $inscrito->apfono;?></td>
					<td><?php echo fspm_cursequi($inscrito->cursoi, $inscrito->otrocurso);?></td>
					<td><?php echo $inscrito->apextr;?></td>
					<td><?php echo fspm_parseyear($inscrito->year);?></td>
				</tr>
			<?php }
		?>
		</table>
		<?php 
		$csv = fspm_csv();
		echo '<p><a class="button" href="'.$csv.'"> Descargar archivo CSV con inscripciones </a> </p>';
		?>
	</div>
	<?php
}

function fspm_parseyear($year) {
	if(!$year || $year == 'actual') {
		return '2015';
	} else {
		return '2016';
	}
}

function fspm_csv() {
	//Genera un csv con todos los datos
	global $wpdb;
	global $tbname;
	$inscritos = fspm_getdata();

	// output headers so that the file is downloaded rather than displayed
		//header('Content-Type: text/csv; charset=utf-8');
		//header('Content-Disposition: attachment; filename=data.csv');

	$filename = 'csd_admision_prepostulacion-'.date('d-m-y').'.csv';

	$output = fopen(FSPM_CSVPATH . $filename, 'w');

	fputcsv($output, array('DIA', 'HORA', 'Nombre Apoderado(a)', 'Nombre Alumno(a)', 'E-mail Apoderado(a)', 'Teléfono Apoderado(a)', 'Curso que postula', 'Consulta', 'Año de postulación'), "\t");

	foreach($inscritos as $inscrito) {
		$inscarr = array();
		$inscarr[] = mysql2date('j F', $inscrito->time );
		$inscarr[] = mysql2date('H:i', $inscrito->time );
		$inscarr[] = $inscrito->apname;
		$inscarr[] = $inscrito->alname;
		$inscarr[] = $inscrito->apmail;
		$inscarr[] = '+56 9' . $inscrito->apfono;
		$inscarr[] = fspm_cursequi($inscrito->cursoi);
		$inscarr[] = $inscrito->apextr;
		$inscarr[] = fspm_parseyear($inscrito->year);
		fputcsv($output, $inscarr, "\t");
	}

	$csvfile = FSPM_CSVURL . $filename;
	return $csvfile;

}