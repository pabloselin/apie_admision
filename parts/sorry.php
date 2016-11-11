<h2>Contenido restringido</h2>
<p>Necesitas estar registrado para ver este contenido.</p>

 <section class="aa_loginForm">
        <?php 
            global $user_login;

            // In case of a login error.
            if ( isset( $_GET['login'] ) && $_GET['login'] == 'failed' ) : ?>
    	            <div class="aa_error">
    		            <p><?php _e( 'FAILED: Try again!', 'AA' ); ?></p>
    	            </div>
            <?php 
                endif;

            // If user is already logged in.
            if ( is_user_logged_in() ) : ?>

                <div class="aa_logout"> 
                    
                    <?php 
                        _e( 'Hola', 'AA' ); 
                        echo $user_login; 
                    ?>
                    
                    </br>
                    
                    <?php _e( 'Ya estás registrado.', 'AA' ); ?>

                </div>

                <a id="wp-submit" href="<?php echo wp_logout_url(); ?>" title="Logout">
                    <?php _e( 'Logout', 'AA' ); ?>
                </a>

            <?php 
                // If user is not logged in.
                else: 
                	
                	global $post;
                    // Login form arguments.
                    $args = array(
                        'echo'           => true,
                        'redirect'       => get_permalink( $post->ID ), 
                        'form_id'        => 'loginform',
                        'label_username' => __( 'Usuario' ),
                        'label_password' => __( 'Password' ),
                        'label_remember' => __( 'Recordar' ),
                        'label_log_in'   => __( 'Ingresar' ),
                        'id_username'    => 'user_login',
                        'id_password'    => 'user_pass',
                        'id_remember'    => 'rememberme',
                        'id_submit'      => 'wp-submit',
                        'remember'       => true,
                        'value_username' => NULL,
                        'value_remember' => true
                    ); 
                    
                    // Calling the login form.
                    wp_login_form( $args );

                endif;
        ?> 

	</section>