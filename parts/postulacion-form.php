<?php 
//Si es que se ha enviado el formulario ejecuto otras cosas
if($_POST && $_POST['prepostnonce']) {
		$nonce = $_POST['prepostnonce'];
	};
if($_POST && $nonce){	
	echo fpost_validate();
	} else { ?>

<form class="form" id="formulario-postulacion" action="" method="POST">
			<!--nonce-->
			<?php echo wp_nonce_field('fpost_prepost', 'prepostnonce');?>
			<input type="hidden" name="" value="FPOST_prepost" placeholder="">
			<!--formel-->
			<div class="form-group year-control">
				<h2>Año al que postula</h2>
					<div class="help-block">
						
					</div>
					<div class=" year-post">
						<div class="radio">
							<label>
								<input type="radio" name="year" value="2016">
								<span class="lname">2016</span>
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="year" value="2017">
								<span class="lname">2017</span>
							</label>
						</div>
					</div>
			</div>	

			<h2>Datos alumno/a postulante</h2>
			<div class="form-group">
			
				<label class="control-label" for="nombre_alumno">Nombres</label>
				<div class="">
					<input type="text" name="nombre_alumno" value="" placeholder="Nombre(s) alumno/a" required class="form-control">
				</div>

				<label class="control-label" for="apellido_alumno">Apellidos</label>
				<div class="">
					<input type="text" name="apellido_alumno" value="" placeholder="Apellidos alumno/a" required class="form-control">
				</div>

				<label class="control-label" for="rut_alumno">RUT</label>
				<div class="">
					<input type="text" name="rut_alumno" value="" placeholder="RUT" required class="form-control">
				</div>

				<label class="control-label" for="nacimiento_alumno">Fecha de Nacimiento</label>
				<div class="">
					<input type="text" name="nacimiento_alumno" value="" placeholder="Fecha nacimiento" required class="form-control">
				</div>

				<label class="control-label" for="procedencia_alumno">Jardín o colegio del cual proviene</label>
				<div>
					<input type="text" name="procedencia_alumno" value="" placeholder="Nombre jardín o colegio" required class="form-control">
				</div>
			</div>

			<div class="form-group">
				<label for="curso" class="control-label">Curso al que postula</label>
				<div>
					<select name="curso" id="curso_postula" class="form-control">
						<option value="pk">PreKinder</option>
						<option value="k">Kinder</option>
						<option value="1">1º Básico</option>
						<option value="2">2º Básico</option>
						<option value="3">3º Básico</option>
						<option value="4">4º Básico</option>
						<option value="5">5º Básico</option>
						<option value="6">6º Básico</option>
						<option value="7">7º Básico</option>
						<option value="8">8º Básico</option>
						<option value="8">8º Básico</option>
						<option value="9">Iº Medio</option>
						<option value="10">IIº Medio</option>
						<option value="otro">Otro</option>
					</select>
				</div>
			</div>
			<!--formel-->
			<h2>Datos apoderado/a</h2>
			<div class="form-group">
			<label class="control-label" for="nombre_apoderado">Nombres</label>
				<div>
					<input type="text" name="nombre_apoderado" value="" placeholder="Nombre(s) apoderado/a" required class="form-control">
				</div>

				<label class="control-label" for="apellido_apoderado">Apellidos</label>
				<div class="">
					<input type="text" name="apellido_apoderado" value="" placeholder="Apellidos apoderado/a" required class="form-control">
				</div>

				<label class="control-label" for="rut_alumno">RUT</label>
				<div class="">
					<input type="text" name="rut_alumno" value="" placeholder="RUT" required class="form-control">
				</div>
				<label class="control-label " for="fono_apoderado">Celular apoderado(a)</label>
				<div class="">
					<div class="input-group">
						<span class="input-group-addon">+56 9</span>
						<input class="form-control" type="text" name="fono_apoderado" value="" placeholder="" required>
					</div>
				</div>
			</div>
			<!--formel-->
			<div class="form-group">
				<label class="control-label" for="email_apoderado">E-Mail apoderado(a)</label>
				<div class="">
					<input class="form-control" type="email" name="email_apoderado" value="" placeholder="Email Apoderado(a)" required>
				</div>
			</div>
			
			<h2>Información adicional</h2>
			<!--formel-->
			<div class="form-group">
				<label class="control-label" for="mensaje_apoderado">Mensaje adicional</label>
				<div>
					<textarea class="form-control" name="mensaje"></textarea>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label" for="xtra_apoderado">¿Cómo supo del colegio?</label>
				<div>
					<textarea class="form-control" name="xtra_apoderado"></textarea>
					<div class="help-block">ej: amistades, facebook, colegas, parientes, prensa, google, revistas</div>
				</div>
			</div>
			<!--formel-->			

			<!--submit-->
			<div class="submitplaceholder">
				<div class="alert">
					Necesitas JavaScript activado para poder usar el formulario
				</div>
			</div>
		</form>';

<?php	
//end conditional output	
		}
?>