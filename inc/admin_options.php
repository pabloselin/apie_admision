<?php
add_action( 'admin_menu', 'apadm_add_admin_menu' );
add_action( 'admin_init', 'apadm_settings_init' );


function apadm_add_admin_menu(  ) { 

	add_menu_page( 'Formulario Admisión', 'apie_admision', 'manage_options', 'apie_admision', 'apadm_options_page' );

}


function apadm_settings_init(  ) { 

	register_setting( 'pluginPage', 'apadm_settings' );

	add_settings_section(
		'apadm_pluginPage_section', 
		__( 'Your section description', 'apadm' ), 
		'apadm_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'apadm_text_field_0', 
		__( 'Settings field description', 'apadm' ), 
		'apadm_text_field_0_render', 
		'pluginPage', 
		'apadm_pluginPage_section' 
	);

	add_settings_field( 
		'apadm_text_field_1', 
		__( 'Settings field description', 'apadm' ), 
		'apadm_text_field_1_render', 
		'pluginPage', 
		'apadm_pluginPage_section' 
	);

	add_settings_field( 
		'apadm_text_field_2', 
		__( 'Settings field description', 'apadm' ), 
		'apadm_text_field_2_render', 
		'pluginPage', 
		'apadm_pluginPage_section' 
	);

	add_settings_field( 
		'apadm_text_field_3', 
		__( 'Settings field description', 'apadm' ), 
		'apadm_text_field_3_render', 
		'pluginPage', 
		'apadm_pluginPage_section' 
	);

	add_settings_field( 
		'apadm_text_field_4', 
		__( 'Settings field description', 'apadm' ), 
		'apadm_text_field_4_render', 
		'pluginPage', 
		'apadm_pluginPage_section' 
	);

	add_settings_field( 
		'apadm_text_field_5', 
		__( 'Settings field description', 'apadm' ), 
		'apadm_text_field_5_render', 
		'pluginPage', 
		'apadm_pluginPage_section' 
	);


}


function apadm_text_field_0_render(  ) { 

	$options = get_option( 'apadm_settings' );
	?>
	<input type='text' name='apadm_settings[apadm_text_field_0]' value='<?php echo $options['apadm_text_field_0']; ?>'>
	<?php

}


function apadm_text_field_1_render(  ) { 

	$options = get_option( 'apadm_settings' );
	?>
	<input type='text' name='apadm_settings[apadm_text_field_1]' value='<?php echo $options['apadm_text_field_1']; ?>'>
	<?php

}


function apadm_text_field_2_render(  ) { 

	$options = get_option( 'apadm_settings' );
	?>
	<input type='text' name='apadm_settings[apadm_text_field_2]' value='<?php echo $options['apadm_text_field_2']; ?>'>
	<?php

}


function apadm_text_field_3_render(  ) { 

	$options = get_option( 'apadm_settings' );
	?>
	<input type='text' name='apadm_settings[apadm_text_field_3]' value='<?php echo $options['apadm_text_field_3']; ?>'>
	<?php

}


function apadm_text_field_4_render(  ) { 

	$options = get_option( 'apadm_settings' );
	?>
	<input type='text' name='apadm_settings[apadm_text_field_4]' value='<?php echo $options['apadm_text_field_4']; ?>'>
	<?php

}


function apadm_text_field_5_render(  ) { 

	$options = get_option( 'apadm_settings' );
	?>
	<input type='text' name='apadm_settings[apadm_text_field_5]' value='<?php echo $options['apadm_text_field_5']; ?>'>
	<?php

}


function apadm_settings_section_callback(  ) { 

	echo __( 'This section description', 'apadm' );

}


function apadm_options_page(  ) { 

	?>
	<form action='options.php' method='post'>

		<h2>apie_admision</h2>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
	<?php

}

?>