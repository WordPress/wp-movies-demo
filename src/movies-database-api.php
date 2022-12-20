<?php
add_action( 'admin_menu', 'wpmovies_register_my_api_keys_page' );
function wpmovies_register_my_api_keys_page() {
	add_submenu_page(
		'tools.php',
		'The Movie Database API Key',
		'The Movie Database API Key',
		'manage_options',
		'wp-movies-api-key',
		'wpmovies_add_api_keys_callback'
	);
}

// The admin page containing the form
function wpmovies_add_api_keys_callback() { ?>
	<div class="wrap"><div id="icon-tools" class="icon32"></div>
		<h2><?php _e( 'The Movie Database API Key', 'wp-movies-demo' ); ?></h2>
		<p>
		<?php
			_e( 'Enter your API key below to get started, you can get one at <a href="https://www.themoviedb.org/settings/api" target="_blank">https://www.themoviedb.org/settings/api</a>', 'wp-movies-demo' );
		?>
		</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
			<h3><?php _e( 'API Key', 'wp-movies-demo' ); ?></h3>
			<input style="min-width: 285px"  type="text" name="wp_movies_tmdb_api_key" placeholder="<?php _e( 'Enter TMDB API Key', 'wp-movies-demo' ); ?>">
			<input type="hidden" name="action" value="process_form">			 
			<input type="submit" name="submit" id="submit" class="update-button button button-primary" value="<?php _e( 'Update API Key', 'wp-movies-demo' ); ?>"  />
		</form> 
	</div>
	<?php
}

// Submit functionality
function wpmovies_submit_api_key() {
	if ( isset( $_POST['wp_movies_tmdb_api_key'] ) ) {
		$api_key    = sanitize_text_field( $_POST['wp_movies_tmdb_api_key'] );
		$api_exists = get_option( 'wp_movies_tmdb_api_key' );
		if ( ! empty( $api_key ) && ! empty( $api_exists ) ) {
			update_option( 'wp_movies_tmdb_api_key', $api_key );
		} else {
			add_option( 'wp_movies_tmdb_api_key', $api_key );
		}
	}
	wp_redirect( $_SERVER['HTTP_REFERER'] );
}
add_action( 'admin_post_nopriv_process_form', 'wpmovies_submit_api_key' );
add_action( 'admin_post_process_form', 'wpmovies_submit_api_key' );
