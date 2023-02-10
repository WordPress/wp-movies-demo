<?php

/**
 * Add the WP Directives Menu Item on options side.
 */
function wp_directives_register_menu() {
	add_options_page(
		'WP Directives',
		'WP Directives',
		'manage_options',
		'wp-directives-plugin',
		'wp_directives_render_admin_page'
	);
}
add_action( 'admin_menu', 'wp_directives_register_menu' );

/**
 * Render the Admin Page.
 */
function wp_directives_render_admin_page() {    ?>
	<div class="wrap">
	  <h2>WP Directives</h2>
	  <form method="POST" action="options.php">
		  <?php
			settings_fields( 'wp_directives_plugin_settings' );
			do_settings_sections( 'wp_directives_plugin_page' );
			?>
		  <?php submit_button(); ?>
	  </form>
	</div>
	<?php
}

/**
 * Register the client side transition settings.
 */
function wp_directives_register_settings() {
	register_setting(
		'wp_directives_plugin_settings',
		'wp_directives_plugin_settings',
		array(
			'type'              => 'object',
			'default'           => array(
				'client_side_navigation' => false,
			),
			'sanitize_callback' => 'wp_directives_validate_settings',
		)
	);

	add_settings_section(
		'wp_directives_plugin_section',
		'',
		null,
		'wp_directives_plugin_page'
	);

	add_settings_field(
		'client_side_navigation',
		'Client Side Navigation',
		'wp_directives_client_side_navigation_input',
		'wp_directives_plugin_page',
		'wp_directives_plugin_section'
	);
}
add_action( 'admin_init', 'wp_directives_register_settings' );

/**
 * Validate the settings.
 *
 * @param array $input The input from the settings page.
 *
 * @return bool Is client side navigation enabled.
 */
function wp_directives_validate_settings( $input ) {
	$output                           = get_option( 'wp_directives_plugin_settings' );
	$output['client_side_navigation'] =
		isset( $input ) && $input['client_side_navigation'] ? true : false;
	return $output;
}

/**
 * Render the client side navigation option input.
 */
function wp_directives_client_side_navigation_input() {
	$options = get_option( 'wp_directives_plugin_settings' );
	?>
	
	<input type="checkbox" name="<?php echo esc_attr( 'wp_directives_plugin_settings[client_side_navigation]' ); ?>"
		<?php echo $options['client_side_navigation'] ? 'checked' : ''; ?>
	>
	<?php
}
