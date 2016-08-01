<?php 
//Si es que se ha enviado el formulario ejecuto otras cosas

if($_POST && $_POST['postulacion_nonce']) {
		$nonce = $_POST['postulacion_nonce'];
	};
if($_POST && $nonce){	
	echo fpost_validate();
	} else { ?>



<div class="postulacion_formwrapper">

	<form class="form" id="formulario-postulacion" action="" method="POST" enctype="multipart/form-data" >
				<!--nonce-->
				<?php echo wp_nonce_field('fpost_prepost', 'postulacion_nonce');?>
				<input type="hidden" name="" value="fpost_prepost" placeholder="">
				<!--formel-->
				
				<?php 
	
				include( plugin_dir_path( __FILE__ ) . 'control-select-year.php' );
	
				include( plugin_dir_path(__FILE__) . 'control-select-course.php' );
	
				include( plugin_dir_path( __FILE__ ) . 'control-select-infostudent.php' );
	
				include( plugin_dir_path( __FILE__ ) . 'control-select-where.php' );			
	
				include( plugin_dir_path( __FILE__ ) . 'control-select-parentinfo.php' );
	
	
				include( plugin_dir_path( __FILE__ ) . 'control-select-extrainfo.php' );
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
?>