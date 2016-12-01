<?php 
//Si es que se ha enviado el formulario ejecuto otras cosas

if( isset($_GET['excode']) ) {

	echo fpost_exitmessages( $_GET['excode'] );

} else { ?>


<div class="postulacion_formwrapper">

	<form class="form" id="formulario-postulacion" action="" method="POST" enctype="multipart/form-data" >
				<!--nonce-->
				<?php echo wp_nonce_field('fpost_prepost', 'postulacion_nonce');?>
				<input type="hidden" name="" value="fpost_prepost" placeholder="">
				<!--formel-->
				
				<?php 
	
				if( isset($_GET['datos_adicionales']) && isset($_GET['fpostid']) ) {

					echo fpost_fichapostulacion( sanitize_text_field( $_GET['fpostid'] ));

					include( plugin_dir_path( __FILE__ ) . 'control-select-domicilio.php' );

					include( plugin_dir_path( __FILE__ ) . 'control-select-conquienvive.php' );


				} else {

					echo '<div class="row">';

					echo '<div class="col-md-12"><h2 class="first">Curso al que postula</h2></div>';
					echo '<div class="col-md-6">';

					include( plugin_dir_path( __FILE__ ) . 'control-select-year.php' );

					echo '</div> <div class="col-md-6">';

					include( plugin_dir_path( __FILE__ ) . 'control-select-semestre.php' );

					echo '</div>';

					echo '</div>';
	
					include( plugin_dir_path(__FILE__) . 'control-select-course.php' );
		
					include( plugin_dir_path( __FILE__ ) . 'control-select-infostudent.php' );
		
					//include( plugin_dir_path( __FILE__ ) . 'control-select-where.php' );			
		
					include( plugin_dir_path( __FILE__ ) . 'control-select-parentinfo.php' );
		
		
					include( plugin_dir_path( __FILE__ ) . 'control-select-extrainfo.php' );

				}

				
				?>
	
				<!--submit-->
				<div class="submitplaceholder">
					<div class="alert">
						Necesitas JavaScript activado para poder usar el formulario
					</div>
				</div>
	
	
			</form>
</div>

<?php	
//end conditional output
}