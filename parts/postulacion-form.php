<?php 
//Si es que se ha enviado el formulario ejecuto otras cosas

if($_POST && $_POST['postulacion_nonce']) {
		$nonce = $_POST['postulacion_nonce'];
	};
if($_POST && $nonce){	
	echo fpost_validate();
	} else { ?>

<form class="form" id="formulario-postulacion" action="<?php bloginfo('url');?>/formulario-postulacion" method="POST" enctype="multipart/form-data" >
			<!--nonce-->
			<?php echo wp_nonce_field('fpost_prepost', 'postulacion_nonce');?>
			<input type="hidden" name="" value="fpost_prepost" placeholder="">
			<!--formel-->
			<div class="form-group year-control">
				<h2>Año al que postula</h2>
					<div class="help-block">
						
					</div>
					<div class="year-post">
						<div class="radio">
							<label>
								<input type="radio" name="postulacion_year" value="2016">
								<span class="lname">2016</span>
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="postulacion_year" value="2017">
								<span class="lname">2017</span>
							</label>
						</div>
					</div>
				<div class="error-placement"></div>
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

			<div class="errorPlacement"></div>
						
						<h4>Fecha de nacimiento</h4>
						
						<label for="alumno_dia_nacimiento" class="control-label">Día</label>
						
							<select name="alumno_dia_nacimiento" class="form-control" required>
											<option value="">Escoja un día</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
											<option value="11">11</option>
											<option value="12">12</option>
											<option value="13">13</option>
											<option value="14">14</option>
											<option value="15">15</option>
											<option value="16">16</option>
											<option value="17">17</option>
											<option value="18">18</option>
											<option value="19">19</option>
											<option value="20">20</option>
											<option value="21">21</option>
											<option value="22">22</option>
											<option value="23">23</option>
											<option value="24">24</option>
											<option value="25">25</option>
											<option value="26">26</option>
											<option value="27">27</option>
											<option value="28">28</option>
											<option value="29">29</option>
											<option value="30">30</option>
											<option value="31">31</option>
							</select>
						
					
					<div class="form-group">
						<label for="alumno_mes_nacimiento" class="control-label">Mes</label>
						
							<select name="alumno_mes_nacimiento" class="form-control" required>
										<option value="">Escoja un mes</option>
										<option value="Enero">Enero</option>
										<option value="Febrero">Febrero</option>
										<option value="Marzo">Marzo</option>
										<option value="Abril">Abril</option>
										<option value="Mayo">Mayo</option>
										<option value="Junio">Junio</option>
										<option value="Julio">Julio</option>
										<option value="Agosto">Agosto</option>
										<option value="Septiembre">Septiembre</option>
										<option value="Octubre">Octubre</option>
										<option value="Noviembre">Noviembre</option>
										<option value="Diciembre">Diciembre</option>
									</select>
						
							
						
					</div>
			
					<div class="form-group">
						<label for="alumno_an_nacimiento" class="control-label">Año</label>
						
							<select name="alumno_an_nacimiento" class="form-control" required>
										<option value="">Escoja un año</option>
										<option value="1998">1998</option>
										<option value="1999">1999</option>
										<option value="2000">2000</option>
										<option value="2001">2001</option>
										<option value="2002">2002</option>
										<option value="2003">2003</option>
										<option value="2004">2004</option>
										<option value="2005">2005</option>
										<option value="2006">2006</option>
										<option value="2007">2007</option>
										<option value="2008">2008</option>
										<option value="2009">2009</option>
										<option value="2010">2010</option>
										<option value="2011">2011</option>
										<option value="2012">2012</option>
										<option value="2013">2013</option>
									</select>
						
					</div>

					<div class="errorPlacement"></div>
			</div>

			<div class="form-group">
				<label class="control-label" for="procedencia_alumno">Jardín o colegio del cual proviene</label>
				<div>
					<input type="text" name="procedencia_alumno" value="" placeholder="Nombre jardín o colegio" required class="form-control">
				</div>

				<div class="errorPlacement"></div>
			</div>

			<div class="form-group">
				<label for="curso_postula" class="control-label">Curso al que postula</label>
				<div>
					<select name="curso_postula" id="curso_postula" class="form-control" required>
						<option value="">Escoja un curso</option>
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

				<div class="errorPlacement"></div>
			</div>

			<div class="control-group otrocurso-control">
					<label class="control-label" for="otrocurso">¿Cuál?</label>
					<div class="controls">
						<input type="text" name="otrocurso" value="" placeholder="Curso" required>
						<span class="help-block">
							Indique a qué otro curso le interesa postular
						</span>
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
			
			<div class="errorPlacement"></div>

			<h2>Información adicional</h2>
			<!--formel-->
			<div class="form-group">
				<label class="control-label" for="postulacion_mensaje">Mensaje adicional</label>
				<div>
					<textarea class="form-control" name="postulacion_mensaje"></textarea>
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

			<div class="errors-placeholder">
				<span class="preError"></span>
			</div>

			<!--submit-->
			<div class="submitplaceholder">
				<div class="alert">
					Necesitas JavaScript activado para poder usar el formulario
				</div>
			</div>


		</form>

<?php	
//end conditional output	
		}
?>