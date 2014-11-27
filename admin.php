<?php 

add_action('admin_menu', 'fspm_admin');

function fspm_admin() {
	add_options_page( __( 'Prepostulaciones Admisión SPM', 'spm' ), __( 'Prepostulaciones Admisión SPM', 'spm' ), 'manage_options', 'prepost_admision', 'fspm_doadmin' );
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
		<h2>Prepostulación SPM</h2>
		<table class="widefat wp-list-table fspmlist">
			<thead>
				<th>Fecha</th>
				<th>Hora</th>
				<th>Nombre Apoderado</th>
				<th>Nombre Alumno</th>
				<th>E-Mail Apoderado</th>
				<th>Teléfono Apoderado</th>
				<th>Curso</th>
				<th>Mensaje adicional</th>
			</thead>
		
		<?php
			foreach($inscritos as $inscrito) {?>
				<tr>
					<td><?php echo mysql2date( 'j F', $inscrito->time );?></td>
					<td><?php echo mysql2date( 'h:i', $inscrito->time );?></td>
					<td><?php echo $inscrito->apname;?></td>
					<td><?php echo $inscrito->alname;?></td>
					<td><?php echo $inscrito->apmail;?></td>
					<td><?php echo $inscrito->apfono;?></td>
					<td><?php echo $inscrito->cursoi;?></td>
					<td><?php echo $inscrito->apextr;?></td>
				</tr>
			<?php }
		?>
		</table>
	</div>
	<?php
}