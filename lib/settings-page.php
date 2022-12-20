<?php

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

function wp_directives_render_admin_page() {?>
	<div class="wrap">
		<h1><?php _e( 'WP Directives Settings', 'wp-movies-demo' ); ?></h1>
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

function wp_directives_register_settings() {
	register_setting(
		'wp_directives_plugin_settings',
		'wp_directives_plugin_settings',
		array(
			'type'              => 'object',
			'default'           => array(
				'client_side_transitions' => false,
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
		'client_side_transitions',
		'Client Side Transitions',
		'wp_directives_client_side_transitions_input',
		'wp_directives_plugin_page',
		'wp_directives_plugin_section'
	);
}
add_action( 'admin_init', 'wp_directives_register_settings' );

function wp_directives_validate_settings( $input ) {
	$output                            = get_option( 'wp_directives_plugin_settings' );
	$output['client_side_transitions'] =
		isset( $input ) && $input['client_side_transitions'] ? true : false;
	return $output;
}

function wp_directives_client_side_transitions_input() {
	$options = get_option( 'wp_directives_plugin_settings' );
	?>

	<input type="checkbox" 
		name="
		<?php
		echo esc_attr(
			'wp_directives_plugin_settings[client_side_transitions]'
		)
		?>
			  " 
		<?php echo $options['client_side_transitions'] ? 'checked' : ''; ?>
	>

	<?php
}
