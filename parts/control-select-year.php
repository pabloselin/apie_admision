<?php 
$options = get_option( 'apadm_settings' );
?>

<div class="form-group year-control">

				<p class="info-label">Año</p>

					<div class="help-block">
						
					</div>
					<div class="year-post">
						<div class="radio">
							<label>
								<input type="radio" name="postulacion_year" value="<?php echo $options['apadm_year_current']; ?>">
								<span class="lname"><?php echo $options['apadm_year_current']; ?></span>
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="postulacion_year" value="<?php echo $options['apadm_year_next']; ?>">
								<span class="lname"><?php echo $options['apadm_year_next']; ?></span>
							</label>
						</div>
					</div>
				<div class="error-placement"></div>
			</div>