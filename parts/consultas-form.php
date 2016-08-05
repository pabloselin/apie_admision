<?php
//Formulario
if($_POST && $_POST['consultas_nonce']) {
		$nonce = $_POST['consultas_nonce'];
	};
if($_POST && $nonce) {	

	echo fpost_consultasvalidate();
	
	} else { ?>

		<form class="form" id="formulario_consultas" action="" method="POST" enctype="multipart/form-data">		
				<?php echo wp_nonce_field('fpost_consultas', 'consultas_nonce');?>
				<input type="hidden" name="" value="fpost_consultas" placeholder="">
					<div class="form-group">
					<label for="nombre_consultas">Nombre</label>
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-user"></i></div>
							<input type="text" class="form-control" name="nombre_consultas" required placeholder="Nombre">
						</div>
					</div>
					<div class="form-group">
					<label for="email_consultas">Correo</label>
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-envelope"></i></div>
							<input type="email" class="form-control" name="email_consultas" required placeholder="Correo">
						</div>
					</div>
					<div class="form-group hidden">
					<label for="email_falso">No llenar</label>
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-envelope"></i></div>
							<input type="email" class="form-control" name="email_falso" placeholder="Correo">
						</div>
					</div>
					<div class="form-group">
					<label for="fono_consultas">Teléfono</label>
					<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-phone"></i> +56 9</div>
							<input type="text" class="form-control" name="fono_consultas" placeholder="Teléfono">
						</div>
						<span class="help-block">Opcional</span>
					</div>
					<div class="form-group">
						<label for="mensaje_consultas">Tu consulta:</label>
						<textarea name="mensaje_consultas" class="form-control" rows="6" required ></textarea>
					</div>

					<div class="consultas-submitplaceholder">
						
					</div>
				</form>

<?php };