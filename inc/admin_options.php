<?php
add_action( 'admin_menu', 'apadm_add_admin_menu' );
add_action( 'admin_init', 'apadm_settings_init' );


function apadm_add_admin_menu(  ) { 

	add_menu_page( 'Formulario Admisión', 'F. Admisión', 'manage_options', 'apie_admision', 'apadm_options_page' );

	//add_submenu_page( 'apie_admision', 'Admisión', 'F. Admisión', 'manage_options', 'admin.php?page=apie_admision');

	add_submenu_page( 'apie_admision', 'Postulaciones enviadas', 'Postulaciones Enviadas',  'manage_options', 'fpost_postulaciones', 'fpost_doadmin' );

	add_submenu_page( 'apie_admision', 'Consultas enviadas', 'Consultas Enviadas', 'manage_options', 'fpost_consultas', 'fpost_doadminconsultas' );

}


function apadm_settings_init(  ) { 

	register_setting( 'optadm', 'apadm_settings' );

	add_settings_section(
		'apadm_optadm_section', 
		__( 'Configuración formulario de admisión', 'apadm' ), 
		'apadm_settings_section_callback', 
		'optadm'
	);

	add_settings_field( 
		'apadm_nombre_colegio', 
		__( 'Nombre del colegio para usar en los correos y formularios', 'apadm' ), 
		'apadm_nombre_colegio_render', 
		'optadm', 
		'apadm_optadm_section' 
	);

	add_settings_field( 
		'apadm_year_current', 
		__( 'Primer año de postulación', 'apadm' ), 
		'apadm_year_current_render', 
		'optadm', 
		'apadm_optadm_section' 
	);

	add_settings_field( 
		'apadm_year_next', 
		__( 'Siguiente año de postulación', 'apadm' ), 
		'apadm_year_next_render', 
		'optadm', 
		'apadm_optadm_section' 
	);

	add_settings_field( 
		'apadm_email_remitente', 
		__( 'Email del remitente de los correos a enviar', 'apadm' ), 
		'apadm_email_remitente_render', 
		'optadm', 
		'apadm_optadm_section' 
	);

	add_settings_field( 
		'apadm_fonocontacto', 
		__( 'Teléfono de contacto que aparecerá en los correos', 'apadm' ), 
		'apadm_fonocontacto_render', 
		'optadm', 
		'apadm_optadm_section' 
	);

	add_settings_field( 
		'apadm_prefijometabox', 
		__( 'Prefijo para los campos de opciones (ej: csd_)', 'apadm' ), 
		'apadm_prefijometabox_render', 
		'optadm', 
		'apadm_optadm_section' 
	);

	add_settings_field( 
		'apadm_emailsto', 
		__( 'Correos a quien enviar los formularios (separados por coma)', 'apadm' ), 
		'apadm_emailsto_render', 
		'optadm', 
		'apadm_optadm_section' 
	);

	add_settings_field( 
		'apadm_bccemailsto', 
		__( 'Correos a quien enviar los formularios en copia oculta (separados por coma)', 'apadm' ), 
		'apadm_bccemailsto_render', 
		'optadm', 
		'apadm_optadm_section' 
	);

	add_settings_field( 
		'apadm_logourl', 
		__( 'URL del logotipo para los correos', 'apadm' ), 
		'apadm_logourl_render', 
		'optadm', 
		'apadm_optadm_section' 
	);


}


function apadm_nombre_colegio_render(  ) { 

	$options = get_option( 'apadm_settings' );
	?>
	<input type='text' name='apadm_settings[apadm_nombre_colegio]' value='<?php echo $options['apadm_nombre_colegio']; ?>'>
	<?php

}


function apadm_email_remitente_render(  ) { 

	$options = get_option( 'apadm_settings' );
	?>
	<input type='text' name='apadm_settings[apadm_email_remitente]' value='<?php echo $options['apadm_email_remitente']; ?>'>
	<?php

}

function apadm_year_current_render(  ) { 

	$options = get_option( 'apadm_settings' );
	?>
	<input type='text' name='apadm_settings[apadm_year_current]' value='<?php echo $options['apadm_year_current']; ?>'>
	<?php

}

function apadm_year_next_render(  ) { 

	$options = get_option( 'apadm_settings' );
	?>
	<input type='text' name='apadm_settings[apadm_year_next]' value='<?php echo $options['apadm_year_next']; ?>'>
	<?php

}


function apadm_fonocontacto_render(  ) { 

	$options = get_option( 'apadm_settings' );
	?>
	<input type='text' name='apadm_settings[apadm_fonocontacto]' value='<?php echo $options['apadm_fonocontacto']; ?>'>
	<?php

}


function apadm_prefijometabox_render(  ) { 

	$options = get_option( 'apadm_settings' );
	?>
	<input type='text' name='apadm_settings[apadm_prefijometabox]' value='<?php echo $options['apadm_prefijometabox']; ?>'>
	<?php

}


function apadm_emailsto_render(  ) { 

	$options = get_option( 'apadm_settings' );
	?>
	<input type='text' name='apadm_settings[apadm_emailsto]' value='<?php echo $options['apadm_emailsto']; ?>'>
	<?php

}

function apadm_bccemailsto_render(  ) { 

	$options = get_option( 'apadm_settings' );
	?>
	<input type='text' name='apadm_settings[apadm_bccemailsto]' value='<?php echo $options['apadm_bccemailsto']; ?>'>
	<?php

}


function apadm_logourl_render(  ) { 

	$options = get_option( 'apadm_settings' );
	?>
	<input type='text' name='apadm_settings[apadm_logourl]' value='<?php echo $options['apadm_logourl']; ?>'>
	<?php

}


function apadm_settings_section_callback(  ) { 

	echo __( 'Aquí están los datos que controlan como funciona el formulario de postulación.', 'apadm' );

}


function apadm_options_page(  ) { 

	?>
	<form action='options.php' method='post'>

		<h2>Configuración formulario</h2>

		<?php
		settings_fields( 'optadm' );
		do_settings_sections( 'optadm' );
		submit_button();
		?>

	</form>
	<?php

}