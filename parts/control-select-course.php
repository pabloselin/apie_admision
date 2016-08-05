<div class="form-group">
	<label for="curso_postula" class="control-label">Curso al que postula</label>
	<div>
		<select name="curso_postula" id="curso_postula" class="form-control" required>
			<option value="">Escoja un curso</option>
			<option value="pg">Playgroup</option>
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
			<option value="9">Iº Medio</option>
			<option value="10">IIº Medio</option>
			<option value="otro">Otro</option>
		</select>
	</div>

	<div class="errorPlacement"></div>
</div>

<div class="form-group hidden" data-toggle="jornada-control">
	<label for="jornada" class="control-label">¿Tiene alguna preferencia de tipo de jornada para el curso?</label>
	<div>
		<div class="radio">
			<label>
				<input type="radio" name="jornada" value="manana">
				<span class="lname">Mañana</span>
			</label>
		</div>
		<div class="radio">
			<label>
				<input type="radio" name="jornada" value="tarde">
				<span class="lname">Tarde</span>
			</label>
		</div>
		<div class="radio">
			<label>
				<input type="radio" name="jornada" value="cualquiera">
				<span class="lname">Cualquiera</span>
			</label>
		</div>
	</div>
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