<h2>Datos apoderado/a</h2>
			<div class="form-group">
				
				<div class="row">
					<div class="col-md-5">
						<label class="control-label" for="nombre_apoderado">Nombre</label>
						<div>
							<input type="text" name="nombre_apoderado" value="" placeholder="Nombres" required class="form-control">
						</div>
					</div>
					
					<div class="col-md-5">
						<label class="control-label" for="apellido_apoderado">Apellidos</label>
						<div class="">
							<input type="text" name="apellido_apoderado" value="" placeholder="Apellidos apoderado/a" required class="form-control">
						</div>
					</div>
				</div>

				<br>

				<div class="row">

				<div class="col-md-10">
					<h2 class="first">Tipo de documento</h2>
				</div>

				<div class="form-group tipodocparent-control col-md-5">
						<div class="help-block">
							
						</div>
						<div class="tipodocparent-post">
							<div class="radio">
								<label>
									<input type="radio" name="tipo_documento_apoderado" value="rut" checked="checked" data-toggle="rut_apoderado_field">
									<span class="lname">RUT</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="tipo_documento_apoderado" value="otro" data-toggle="otrodoc_apoderado_field">
									<span class="lname">Otro (solo extranjeros)</span>
								</label>
							</div>
						</div>
					<div class="error-placement"></div>
				</div>

				
					<div class="col-md-5 visible docfieldpar" id="rut_apoderado_field">
						<label class="control-label" for="rut_apoderado">RUT</label>
						<div class="">
							<input type="text" name="rut_apoderado" value="" placeholder="RUT" required class="form-control">
						</div>
					</div>


					<div class="col-md-5 docfieldpar hidden" id="otrodoc_apoderado_field">
						<label class="control-label" for="otrodoc_apoderado">Número de Identificación</label>
						<div class="">
							<input type="text" name="otrodoc_apoderado" value="" placeholder="Número de Identificación" required class="form-control">
						</div>
					</div>


				</div>

				

				<br>
				
				<div class="row">
					<div class="col-md-5">
						<label class="control-label " for="fono_apoderado">Celular apoderado(a)</label>
						<div class="input-group">
							<span class="input-group-addon">+56 9</span>
							<input class="form-control" type="number" name="fono_apoderado" value="" placeholder="" required>
						</div>
					</div>
					
					<div class="col-md-5">
						<label class="control-label " for="fono_apoderado">Teléfono fijo apoderado(a) (Opcional)</label>
						<div class="input-group">
							<span class="input-group-addon">+56 2</span>
							<input class="form-control" type="number" name="fonofijo_apoderado" value="" placeholder="">
						</div>
					</div>
				</div>
			</div>
			<br>
			<!--formel-->
			<div class="form-group">
				<label class="control-label" for="email_apoderado">E-Mail apoderado(a)</label>
				<div class="">
					<input class="form-control" type="email" name="email_apoderado" value="" placeholder="Email Apoderado(a)" required>
				</div>
			</div>
			
			<div class="errorPlacement"></div>