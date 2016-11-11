<?php 
/**
 * Template para admin frontend postulaciones con:
 * - Tabla de postulaciones
 * - Ficha de cada postulación (vía parámetros)
 * - Sistema de contacto y envío de link con datos segunda etapa
 */
?>

<?php 
if( isset($_GET['fpostid'])) {?>

<div class="container">
	<div class="row">
		<div class="col-md-5">
			
			<?php echo fpost_fichapostulacion( $_GET['fpostid'] );?>

		</div>
		<div class="col-md-5 admin-postulante">
			
			<h2>Gestión de postulante</h2>

			<?php echo fpost_contact( $_GET['fpostid'] );?>
			
		</div>
	</div>
</div>


<?php } else { ?> 

<div class="container">
	<div class="row">
		<div class="col-md-10">
			
			<?php echo fpost_smalltable();?>

		</div>
	</div>
</div>

<?php
}
?>