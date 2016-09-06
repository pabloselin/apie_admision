<h2>Datos postulante</h2>
			<div class="form-group">
			
				
				<div class="row">
					<div class="col-md-5">
						<label class="control-label" for="nombre_alumno">Nombre</label>
						<div class="">
							<input type="text" name="nombre_alumno" value="" placeholder="Nombres postulante" required class="form-control">
						</div>
					</div>
					
					
					
					<div class="col-md-5">
						<label class="control-label" for="apellido_alumno">Apellidos</label>
						<div class="">
							<input type="text" name="apellido_alumno" value="" placeholder="Apellidos postulante" required class="form-control">
						</div>
					</div>
				</div>
				
				<br>


				<div class="row form-group-delimiter">

				<div class="col-md-10">
					<h2 class="subsectionh2">Tipo de documento</h2>
				</div>

				<div class="form-group tipodocal-control col-md-5">
					
						<div class="help-block">
							
						</div>
						<div class="tipodocal-post">
							<div class="radio">
								<label>
									<input type="radio" name="tipo_documento_alumno" value="rut" checked="checked" data-toggle="rut_alumno_field">
									<span class="lname">RUT</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="tipo_documento_alumno" value="otro" data-toggle="otrodoc_alumno_field">
									<span class="lname">Otro (solo extranjeros)</span>
								</label>
							</div>
						</div>
					<div class="error-placement"></div>
				</div>

					<div class="col-md-5 visible docfieldal" id="rut_alumno_field">
						<label class="control-label" for="rut_alumno">RUT</label>
						<div>
							<input type="text" name="rut_alumno" value="" placeholder="RUT" required class="form-control">
						</div>
					</div>

					<div class="col-md-5 docfieldal hidden" id="otrodoc_alumno_field">
						<label class="control-label" for="otrodoc_alumno">Número de Identificación</label>
						<div>
							<input type="text" name="otrodoc_alumno" value="" placeholder="Número de Identificación" required class="form-control">
						</div>
					</div>

				</div>

				
					
				

			<div class="errorPlacement"></div>
					<br>	
					<label class="control-label" for="alumno_fecha_nacimiento">Fecha de nacimiento</label>
				
					<div class="input-group-date input-group">
						<span class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</span>
						<input type="text" name="alumno_fecha_nacimiento" required class="form-control" placeholder="Clic para escoger fecha...">
					</div>

					<div class="errorPlacement"></div>
			</div>